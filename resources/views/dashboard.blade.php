@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Главная панель</h1>
            <p class="text-muted">Обзор ваших автомобилей и состояния запчастей</p>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Автомобили</h6>
                            <h2 class="mb-0">{{ $totalCars }}</h2>
                        </div>
                        <i class="fas fa-car fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Хорошие запчасти</h6>
                            <h2 class="mb-0">{{ $partsGood }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Требуют внимания</h6>
                            <h2 class="mb-0">{{ $partsWarning }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Требуют замены</h6>
                            <h2 class="mb-0">{{ $partsNeedingReplacement }}</h2>
                        </div>
                        <i class="fas fa-exclamation-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Список автомобилей -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Мои автомобили</h5>
                </div>
                <div class="card-body">
                    @if($cars->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-car fa-4x text-muted mb-3"></i>
                            <h5>У вас нет автомобилей</h5>
                            <p class="text-muted">Добавьте свой первый автомобиль для отслеживания состояния запчастей</p>
                            <a href="{{ route('cars.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Добавить автомобиль
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($cars as $car)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="card-title mb-1">
                                                        {{ $car->brand }} {{ $car->model }}
                                                    </h5>
                                                    <small class="text-muted">{{ $car->year }} год</small>
                                                </div>
                                                <span class="badge bg-primary">
                                            {{ number_format($car->current_mileage, 0, '', ' ') }} км
                                        </span>
                                            </div>

                                            <!-- Мини-статистика запчастей -->
                                            @php
                                                $dangerCount = 0;
                                                $warningCount = 0;
                                                $goodCount = 0;

                                                foreach($car->parts as $part) {
                                                    switch($part->status) {
                                                        case 'danger': $dangerCount++; break;
                                                        case 'warning': $warningCount++; break;
                                                        default: $goodCount++; break;
                                                    }
                                                }
                                            @endphp

                                            <div class="d-flex justify-content-between mb-3">
                                                <div class="text-center">
                                                    <span class="badge bg-danger">{{ $dangerCount }}</span>
                                                    <small class="d-block text-muted">Срочно</small>
                                                </div>
                                                <div class="text-center">
                                                    <span class="badge bg-warning">{{ $warningCount }}</span>
                                                    <small class="d-block text-muted">Внимание</small>
                                                </div>
                                                <div class="text-center">
                                                    <span class="badge bg-success">{{ $goodCount }}</span>
                                                    <small class="d-block text-muted">Норма</small>
                                                </div>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Подробнее
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('cars.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>Все автомобили
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Боковая панель -->
        <div class="col-lg-4">
            <!-- Быстрые действия -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('cars.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Добавить автомобиль
                        </a>
                        @if($partsNeedingReplacement > 0)
                            <button class="btn btn-danger" id="show-critical-parts">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Критические запчасти ({{ $partsNeedingReplacement }})
                            </button>
                        @endif
                        <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tachometer-alt me-2"></i>Обновить пробег
                        </a>
                    </div>
                </div>
            </div>

            <!-- Последние обновления -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Последние обновления</h5>
                </div>
                <div class="card-body">
                    @if($recentUpdates->isEmpty())
                        <p class="text-muted mb-0">Нет обновлений</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($recentUpdates as $update)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $update['car'] }}</span>
                                        <strong>{{ number_format($update['mileage'], 0, '', ' ') }} км</strong>
                                    </div>
                                    <small class="text-muted">{{ $update['updated_at'] }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для критических запчастей -->
    <div class="modal fade" id="criticalPartsModal" tabindex="-1">
        @php
            $criticalPartIds = $cars->flatMap(function ($car) {
                return $car->parts->where('status', 'danger')->pluck('id');
            })->values();
        @endphp
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-circle me-2"></i>Критические запчасти
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($partsNeedingReplacement > 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Внимание! Эти запчасти требуют немедленной замены
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Автомобиль</th>
                                    <th>Запчасть</th>
                                    <th>Категория</th>
                                    <th>Пробег</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cars as $car)
                                    @foreach($car->parts->where('status', 'danger') as $part)
                                        <tr class="table-danger">
                                            <td>{{ $car->brand }} {{ $car->model }}</td>
                                            <td>{{ $part->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ trans("categories.{$part->category}", [], 'ru') }}</span>
                                            </td>
                                            <td>
                                                {{ number_format($car->current_mileage, 0, '', ' ') }} км
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success replace-part-btn"
                                                        data-part-id="{{ $part->id }}">
                                                    <i class="fas fa-check me-1"></i>Заменена
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h5>Отлично!</h5>
                            <p class="text-muted">Нет запчастей, требующих срочной замены</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($criticalPartIds->isNotEmpty())
                        <button type="button"
                                class="btn btn-success replace-all-parts-btn"
                                data-part-ids='@json($criticalPartIds->all())'>
                            <i class="fas fa-check-double me-1"></i>Заменить все
                        </button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Показать модальное окно с критическими запчастями
        document.getElementById('show-critical-parts')?.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('criticalPartsModal'));
            modal.show();
        });

        // Обработка замены запчасти в модальном окне
        document.addEventListener('click', function(e) {
            if (e.target.closest('.replace-part-btn')) {
                const button = e.target.closest('.replace-part-btn');
                const partId = button.dataset.partId;

                if (confirm('Отметить запчасть как замененную?')) {
                    replaceParts([partId]);
                }
            }

            if (e.target.closest('.replace-all-parts-btn')) {
                const button = e.target.closest('.replace-all-parts-btn');
                const partIds = JSON.parse(button.dataset.partIds || '[]');

                if (!partIds.length) {
                    return;
                }

                if (confirm('Отметить все критические запчасти как замененные?')) {
                    replaceParts(partIds, button);
                }
            }
        });

        function replaceParts(partIds, button = null) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData();

            formData.append('_token', csrfToken);
            partIds.forEach(id => formData.append('part_ids[]', id));

            if (button) {
                button.disabled = true;
            }

            fetch('{{ route("parts.batch-replace") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(() => {
                    if (button) {
                        button.disabled = false;
                    }

                    alert('Не удалось отметить запчасти как замененные');
                });
        }
    </script>
@endpush

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.35em 0.65em;
        }

        .list-group-item {
            border: none;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
@endpush
