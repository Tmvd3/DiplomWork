<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailTwoFactorCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class AuthController extends Controller
{
    private const TWO_FACTOR_USER_ID = 'auth.two_factor.user_id';
    private const TWO_FACTOR_REMEMBER = 'auth.two_factor.remember';
    private const TWO_FACTOR_INTENT = 'auth.two_factor.intent';
    private const TWO_FACTOR_TTL_MINUTES = 10;
    private const TWO_FACTOR_INTENT_LOGIN = 'login';
    private const TWO_FACTOR_INTENT_REGISTER = 'register';

    private function passwordRules(): array
    {
        return [
            'required',
            'string',
            'min:6',
            'confirmed',
            'regex:/^[A-Z][A-Za-z0-9]*$/',
        ];
    }

    private function passwordMessages(): array
    {
        return [
            'password.min' => 'Пароль должен содержать не менее 6 символов.',
            'password.confirmed' => 'Подтверждение пароля не совпадает.',
            'password.regex' => 'Пароль должен начинаться с заглавной буквы и содержать только буквы и цифры. Символы -, ,, /, \\, | и другие специальные знаки запрещены.',
        ];
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ], $this->passwordMessages());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->startTwoFactorChallenge(
            $request,
            $user,
            false,
            self::TWO_FACTOR_INTENT_REGISTER,
            'Аккаунт создан. Мы отправили код подтверждения на вашу почту.',
        );
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function resetPasswordDirect(Request $request)
    {
        $validated = $request->validateWithBag('directPasswordReset', [
            'reset_email' => ['required', 'email', 'exists:users,email'],
            'password' => $this->passwordRules(),
        ], array_merge($this->passwordMessages(), [
            'reset_email.exists' => 'Пользователь с таким email не найден.',
        ]));

        $user = User::where('email', $validated['reset_email'])->firstOrFail();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()
            ->route('login')
            ->with('success', 'Пароль изменён. Теперь можно войти с новым паролем.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $this->startTwoFactorChallenge(
                $request,
                $user,
                $request->boolean('remember'),
                self::TWO_FACTOR_INTENT_LOGIN,
                'Мы отправили код подтверждения на вашу почту.',
            );
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ])->onlyInput('email');
    }

    public function showTwoFactorChallenge(Request $request)
    {
        $user = $this->getPendingTwoFactorUser($request);

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'Сначала введите email и пароль.');
        }

        return view('auth.two-factor', [
            'email' => $user->email,
            'maskedEmail' => $this->maskEmail($user->email),
            'intent' => $this->getPendingTwoFactorIntent($request),
        ]);
    }

    public function verifyTwoFactorChallenge(Request $request)
    {
        $user = $this->getPendingTwoFactorUser($request);

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'Сессия подтверждения истекла. Войдите заново.');
        }

        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Введите код подтверждения.',
            'code.digits' => 'Код подтверждения должен состоять из 6 цифр.',
        ]);

        if (! $user->email_two_factor_code || ! $user->email_two_factor_expires_at) {
            return redirect()
                ->route('two-factor.challenge')
                ->with('error', 'Сначала запросите новый код подтверждения.');
        }

        if ($user->email_two_factor_expires_at->isPast()) {
            $this->clearStoredTwoFactorCode($user);

            return back()->withErrors([
                'code' => 'Срок действия кода истёк. Запросите новый код.',
            ]);
        }

        if (! Hash::check($validated['code'], $user->email_two_factor_code)) {
            return back()->withErrors([
                'code' => 'Неверный код подтверждения.',
            ]);
        }

        $remember = (bool) $request->session()->get(self::TWO_FACTOR_REMEMBER, false);
        $intent = $this->getPendingTwoFactorIntent($request);

        $this->clearTwoFactorChallenge($request, $user);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        $redirect = redirect()->intended('dashboard');

        if ($intent === self::TWO_FACTOR_INTENT_REGISTER) {
            return $redirect->with('success', 'Registration completed successfully.');
        }

        return $redirect;
    }

    public function resendTwoFactorChallenge(Request $request)
    {
        $user = $this->getPendingTwoFactorUser($request);

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'Сессия подтверждения истекла. Войдите заново.');
        }

        if ($user->email_two_factor_sent_at && $user->email_two_factor_sent_at->gt(now()->subMinute())) {
            return back()->with('error', 'Новый код можно запросить не чаще одного раза в минуту.');
        }

        return $this->startTwoFactorChallenge(
            $request,
            $user,
            (bool) $request->session()->get(self::TWO_FACTOR_REMEMBER, false),
            $this->getPendingTwoFactorIntent($request),
            'Мы отправили новый код подтверждения.',
        );
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $this->clearStoredTwoFactorCode($request->user());
        }

        $this->clearTwoFactorChallenge($request);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validateWithBag('profileUpdate', [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'driver_license_number' => ['nullable', 'string', 'max:50'],
            'insurance_policy_starts_at' => ['nullable', 'date'],
            'insurance_policy_expires_at' => ['nullable', 'date', 'after_or_equal:insurance_policy_starts_at'],
        ], [
            'insurance_policy_expires_at.after_or_equal' => 'Дата окончания страхового полиса не может быть раньше даты начала.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('profile.settings')
            ->with('success', 'Данные пользователя обновлены.');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required'],
            'password' => $this->passwordRules(),
        ], array_merge($this->passwordMessages(), [
            'current_password.required' => 'Введите текущий пароль.',
        ]));

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Текущий пароль указан неверно.'], 'passwordUpdate')
                ->with('open_password_modal', true);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.settings')
            ->with('success', 'Пароль успешно изменён.');
    }

    private function startTwoFactorChallenge(Request $request, User $user, bool $remember, string $intent, string $message)
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_two_factor_code' => Hash::make($code),
            'email_two_factor_expires_at' => now()->addMinutes(self::TWO_FACTOR_TTL_MINUTES),
            'email_two_factor_sent_at' => now(),
        ])->save();

        $request->session()->put([
            self::TWO_FACTOR_USER_ID => $user->getKey(),
            self::TWO_FACTOR_REMEMBER => $remember,
            self::TWO_FACTOR_INTENT => $intent,
        ]);

        try {
            $user->notify(new EmailTwoFactorCodeNotification($code, self::TWO_FACTOR_TTL_MINUTES, $intent));
        } catch (Throwable $exception) {
            Log::error('Не удалось отправить email-код для двухфакторной аутентификации.', [
                'user_id' => $user->getKey(),
                'email' => $user->email,
                'exception' => $exception,
            ]);

            return redirect()
                ->route('two-factor.challenge')
                ->with('error', 'Не удалось отправить код на email. Проверьте SMTP и нажмите "Отправить код повторно".');
        }

        return redirect()
            ->route('two-factor.challenge')
            ->with('success', $message);
    }

    private function getPendingTwoFactorUser(Request $request): ?User
    {
        $userId = $request->session()->get(self::TWO_FACTOR_USER_ID);

        if (! $userId) {
            return null;
        }

        $user = User::find($userId);

        if (! $user) {
            $this->clearTwoFactorChallenge($request);
        }

        return $user;
    }

    private function getPendingTwoFactorIntent(Request $request): string
    {
        $intent = $request->session()->get(self::TWO_FACTOR_INTENT, self::TWO_FACTOR_INTENT_LOGIN);

        if (! in_array($intent, [self::TWO_FACTOR_INTENT_LOGIN, self::TWO_FACTOR_INTENT_REGISTER], true)) {
            return self::TWO_FACTOR_INTENT_LOGIN;
        }

        return $intent;
    }

    private function clearTwoFactorChallenge(Request $request, ?User $user = null): void
    {
        $request->session()->forget([
            self::TWO_FACTOR_USER_ID,
            self::TWO_FACTOR_REMEMBER,
            self::TWO_FACTOR_INTENT,
        ]);

        if ($user) {
            $this->clearStoredTwoFactorCode($user);
        }
    }

    private function clearStoredTwoFactorCode(User $user): void
    {
        $user->forceFill([
            'email_two_factor_code' => null,
            'email_two_factor_expires_at' => null,
            'email_two_factor_sent_at' => null,
        ])->save();
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email, 2);

        if (mb_strlen($name) <= 2) {
            $maskedName = mb_substr($name, 0, 1).'*';
        } else {
            $maskedName = mb_substr($name, 0, 2).str_repeat('*', max(mb_strlen($name) - 2, 1));
        }

        return $maskedName.'@'.$domain;
    }
}
