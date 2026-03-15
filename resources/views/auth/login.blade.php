@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Вход в систему</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="remember"
                                   name="remember">
                            <label class="form-check-label" for="remember">
                                Запомнить меня
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Войти
                            </button>
                        </div>
                    </form>

                    <p class="text-muted small mt-3 mb-0">
                        После ввода пароля мы отправим одноразовый код подтверждения на ваш email.
                    </p>

                    <div class="text-center mt-3">
                        <p class="mb-2">
                            <a href="#passwordResetCollapse"
                               data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{ $errors->directPasswordReset->any() ? 'true' : 'false' }}"
                               aria-controls="passwordResetCollapse">
                                Сбросить пароль
                            </a>
                        </p>
                        <p class="mb-0">
                            Нет аккаунта?
                            <a href="{{ route('register') }}">Зарегистрироваться</a>
                        </p>
                    </div>

                    <div class="collapse text-start mt-4 {{ $errors->directPasswordReset->any() ? 'show' : '' }}"
                         id="passwordResetCollapse">
                        <div class="border rounded-3 p-3 bg-light">
                            <h5 class="mb-3"><i class="fas fa-key me-2"></i>Сброс пароля</h5>
                            <p class="text-muted small">
                                Здесь можно сразу поменять пароль без ввода старого пароля и без перехода по ссылкам.
                            </p>

                            <form method="POST" action="{{ route('password.direct-reset') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="reset_email" class="form-label">Email аккаунта</label>
                                    <input type="email"
                                           class="form-control @error('reset_email', 'directPasswordReset') is-invalid @enderror"
                                           id="reset_email"
                                           name="reset_email"
                                           value="{{ old('reset_email') }}"
                                           required>
                                    @error('reset_email', 'directPasswordReset')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reset_password" class="form-label">Новый пароль</label>
                                    <input type="password"
                                           class="form-control @error('password', 'directPasswordReset') is-invalid @enderror"
                                           id="reset_password"
                                           name="password"
                                           pattern="^[A-Z][A-Za-z0-9]*$"
                                           minlength="6"
                                           title="Пароль должен начинаться с заглавной буквы, содержать только буквы и цифры и быть не короче 6 символов"
                                           required>
                                    @error('password', 'directPasswordReset')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Минимум 6 символов. Первый символ - заглавная буква. Разрешены только буквы и цифры.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="reset_password_confirmation" class="form-label">Подтвердите новый пароль</label>
                                    <input type="password"
                                           class="form-control"
                                           id="reset_password_confirmation"
                                           name="password_confirmation"
                                           pattern="^[A-Z][A-Za-z0-9]*$"
                                           minlength="6"
                                           title="Повторите пароль в том же формате"
                                           required>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-rotate-right me-2"></i>Изменить пароль
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
