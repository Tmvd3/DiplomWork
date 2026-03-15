@extends('layouts.app')

@section('title', 'Подтверждение входа')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Подтверждение входа</h4>
                </div>

                <div class="card-body">
                    <p class="mb-3">
                        Мы отправили 6-значный код на <strong>{{ $maskedEmail }}</strong>.
                        Введите его, чтобы завершить вход.
                    </p>

                    <form method="POST" action="{{ route('two-factor.verify') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">Код подтверждения</label>
                            <input type="text"
                                   class="form-control @error('code') is-invalid @enderror"
                                   id="code"
                                   name="code"
                                   inputmode="numeric"
                                   pattern="[0-9]{6}"
                                   maxlength="6"
                                   value="{{ old('code') }}"
                                   autocomplete="one-time-code"
                                   required
                                   autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Код действует 10 минут. Письмо отправлено на {{ $email }}.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shield-alt me-2"></i>Подтвердить вход
                            </button>
                        </div>
                    </form>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center mt-4">
                        <form method="POST" action="{{ route('two-factor.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-paper-plane me-2"></i>Отправить код повторно
                            </button>
                        </form>

                        <a href="{{ route('login') }}" class="btn btn-link px-0">
                            Вернуться к входу
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
