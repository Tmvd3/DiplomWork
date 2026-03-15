@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-user me-2"></i>Профиль</h1>
            <p class="text-muted">{{ Auth::user()->name }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <p class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <a href="{{ route('profile.settings') }}" class="btn btn-primary">
                <i class="fas fa-cog me-2"></i>Настройки
            </a>
        </div>
    </div>
@endsection
