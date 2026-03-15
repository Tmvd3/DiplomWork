@extends('layouts.app')

@php
    $isRegistrationFlow = ($intent ?? 'login') === 'register';
@endphp

@section('title', $isRegistrationFlow ? 'Registration verification' : 'Login verification')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope-open-text me-2"></i>
                        {{ $isRegistrationFlow ? 'Confirm registration' : 'Confirm login' }}
                    </h4>
                </div>

                <div class="card-body">
                    <p class="mb-3">
                        We sent a 6-digit code to <strong>{{ $maskedEmail }}</strong>.
                        Enter it to {{ $isRegistrationFlow ? 'finish registration' : 'finish login' }}.
                    </p>

                    <form method="POST" action="{{ route('two-factor.verify') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">Verification code</label>
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
                                The code is valid for 10 minutes. The message was sent to {{ $email }}.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shield-alt me-2"></i>
                                {{ $isRegistrationFlow ? 'Complete registration' : 'Confirm login' }}
                            </button>
                        </div>
                    </form>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center mt-4">
                        <form method="POST" action="{{ route('two-factor.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-paper-plane me-2"></i>Send code again
                            </button>
                        </form>

                        <a href="{{ $isRegistrationFlow ? route('register') : route('login') }}" class="btn btn-link px-0">
                            {{ $isRegistrationFlow ? 'Back to registration' : 'Back to login' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
