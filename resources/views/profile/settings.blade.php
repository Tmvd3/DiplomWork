@extends('layouts.app')

@section('title', 'Настройки')

@section('content')
    @php($user = Auth::user())

    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Настройки</li>
                </ol>
            </nav>
            <h1><i class="fas fa-cog me-2"></i>Настройки пользователя</h1>
            <p class="text-muted mb-0">Управляйте данными профиля, страховкой и доступом к аккаунту.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2 text-primary"></i>Страница пользователя</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Имя пользователя</label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control @error('name', 'profileUpdate') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name', 'profileUpdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Электронная почта</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control @error('email', 'profileUpdate') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email', 'profileUpdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="driver_license_number" class="form-label">Водительские права</label>
                            <input type="text"
                                   id="driver_license_number"
                                   name="driver_license_number"
                                   class="form-control @error('driver_license_number', 'profileUpdate') is-invalid @enderror"
                                   value="{{ old('driver_license_number', $user->driver_license_number) }}"
                                   maxlength="50"
                                   placeholder="Например: 77 00 123456">
                            @error('driver_license_number', 'profileUpdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insurance_policy_starts_at" class="form-label">Дата начала действия страхового полиса</label>
                                <input type="date"
                                       id="insurance_policy_starts_at"
                                       name="insurance_policy_starts_at"
                                       class="form-control @error('insurance_policy_starts_at', 'profileUpdate') is-invalid @enderror"
                                       value="{{ old('insurance_policy_starts_at', optional($user->insurance_policy_starts_at)->format('Y-m-d')) }}">
                                @error('insurance_policy_starts_at', 'profileUpdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="insurance_policy_expires_at" class="form-label">Дата окончания страхового полиса</label>
                                <input type="date"
                                       id="insurance_policy_expires_at"
                                       name="insurance_policy_expires_at"
                                       class="form-control @error('insurance_policy_expires_at', 'profileUpdate') is-invalid @enderror"
                                       value="{{ old('insurance_policy_expires_at', optional($user->insurance_policy_expires_at)->format('Y-m-d')) }}">
                                @error('insurance_policy_expires_at', 'profileUpdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 pt-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Сохранить данные
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Назад
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2 text-danger"></i>Безопасность</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Ниже можно сменить пароль пользователя. Сначала потребуется подтвердить прошлый пароль.
                    </p>

                    <div class="profile-security-note rounded-3 p-3 mb-3">
                        <div class="fw-semibold mb-1">Правила нового пароля</div>
                        <div class="small text-muted">
                            Пароль должен начинаться с заглавной буквы, содержать минимум 6 символов и состоять только из букв и цифр.
                        </div>
                    </div>

                    <button type="button"
                            class="btn btn-danger w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-2"></i>Сменить пароль
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.change-password') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">
                            <i class="fas fa-lock me-2"></i>Смена пароля
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Прошлый пароль</label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   class="form-control @error('current_password', 'passwordUpdate') is-invalid @enderror"
                                   required>
                            @error('current_password', 'passwordUpdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password', 'passwordUpdate') is-invalid @enderror"
                                   minlength="6"
                                   pattern="^[A-Z][A-Za-z0-9]*$"
                                   data-new-password-field
                                   disabled
                                   required>
                            @error('password', 'passwordUpdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Только буквы и цифры, первый символ должен быть заглавной буквой.</div>
                        </div>

                        <div class="mb-0">
                            <label for="password_confirmation" class="form-label">Подтвердите новый пароль</label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   minlength="6"
                                   pattern="^[A-Z][A-Za-z0-9]*$"
                                   data-new-password-field
                                   disabled
                                   required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-check me-2"></i>Обновить пароль
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .profile-security-note {
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.95), rgba(233, 236, 239, 0.95));
            border: 1px solid rgba(220, 53, 69, 0.12);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shouldOpenPasswordModal = @json($errors->passwordUpdate->any() || session('open_password_modal'));
            const currentPasswordInput = document.getElementById('current_password');
            const newPasswordFields = document.querySelectorAll('[data-new-password-field]');

            function toggleNewPasswordFields() {
                const hasCurrentPasswordValue = currentPasswordInput && currentPasswordInput.value.trim().length > 0;

                newPasswordFields.forEach(field => {
                    field.disabled = !hasCurrentPasswordValue;
                });
            }

            if (currentPasswordInput) {
                toggleNewPasswordFields();
                currentPasswordInput.addEventListener('input', toggleNewPasswordFields);
            }

            if (shouldOpenPasswordModal) {
                const passwordModalElement = document.getElementById('changePasswordModal');

                if (passwordModalElement) {
                    const passwordModal = new bootstrap.Modal(passwordModalElement);
                    passwordModal.show();
                }
            }
        });
    </script>
@endpush
