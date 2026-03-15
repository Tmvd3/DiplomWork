@extends('layouts.app')

@section('title', 'История обслуживания')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-history me-2"></i>История обслуживания</h1>
            <p class="text-muted">Отчёты по обслуживанию автомобилей</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <i class="fas fa-wrench fa-2x text-primary mb-2"></i>
                    <h5>Обслуживание</h5>
                    <p class="text-muted mb-0">История замен запчастей</p>
                    <span class="badge bg-primary mt-2">Текущий раздел</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('reports.costs') }}" class="text-decoration-none text-dark">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-ruble-sign fa-2x text-success mb-2"></i>
                        <h5>Расходы</h5>
                        <p class="text-muted mb-0">Затраты на обслуживание</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('reports.mileage') }}" class="text-decoration-none text-dark">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-tachometer-alt fa-2x text-info mb-2"></i>
                        <h5>Пробег</h5>
                        <p class="text-muted mb-0">Динамика пробега</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-0">Выберите отчёт выше или перейдите к <a href="{{ route('cars.index') }}">списку автомобилей</a>.</p>
        </div>
    </div>
@endsection
