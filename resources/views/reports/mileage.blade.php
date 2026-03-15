@extends('layouts.app')

@section('title', 'Пробег')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('reports.maintenance') }}">История</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Пробег</li>
                </ol>
            </nav>
            <h1><i class="fas fa-tachometer-alt me-2"></i>Динамика пробега</h1>
            <p class="text-muted">Отчёт по пробегу автомобилей</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-0">Раздел в разработке. <a href="{{ route('reports.maintenance') }}">Вернуться к отчётам</a>.</p>
        </div>
    </div>
@endsection
