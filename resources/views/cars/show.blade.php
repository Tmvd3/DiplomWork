@extends('layouts.app')

@section('title', $car->brand . ' ' . $car->model . ' - Детальная информация')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">Автомобили</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $car->brand }} {{ $car->model }}</li>
                </ol>
            </nav>
            <h1>
                <i class="fas fa-car me-2"></i>{{ $car->brand }} {{ $car->model }}
                <small class="text-muted">({{ $car->year }})</small>
            </h1>
            @if($car->license_plate)
                <p class="text-muted">
                    <i class="fas fa-tag me-1"></i>Гос. номер: {{ $car->license_plate }}
                </p>
            @endif
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="{{ route('cars.edit', $car) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-2"></i>Редактировать
                </a>
                <a href="{{ route('cars.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Назад
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Левая колонка - Информация и статистика -->
        <div class="col-lg-4 mb-4">
            <!-- Карточка с основной информацией -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Основная информация</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <strong>Марка:</strong>
                        </div>
                        <div class="col-8">
                            {{ $car->brand }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <strong>Модель:</strong>
                        </div>
                        <div class="col-8">
                            {{ $car->model }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <strong>Год:</strong>
                        </div>
                        <div class="col-8">
                            {{ $car->year }}
                        </div>
                    </div>
                    @if($car->color)
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Цвет:</strong>
                            </div>
                            <div class="col-8">
                        <span class="badge" style="background-color: {{ $car->color }}; color: white; padding: 5px 10px;">
                            <i class="fas fa-palette me-1"></i>{{ $car->color }}
                        </span>
                            </div>
                        </div>
                    @endif
                    @if($car->vin)
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>VIN:</strong>
                            </div>
                            <div class="col-8">
                                <code>{{ $car->vin }}</code>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-4">
                            <strong>Добавлен:</strong>
                        </div>
                        <div class="col-8">
                            {{ $car->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Карточка с пробегом -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Пробег</h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4 text-primary">
                        {{ number_format($car->current_mileage, 0, '', ' ') }}
                    </h1>
                    <p class="mb-0">километров</p>
                    <small class="text-muted">
                        Начальный пробег: {{ number_format($car->initial_mileage, 0, '', ' ') }} км
                    </small>
                </div>
            </div>

            <!-- Статистика запчастей -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Статистика запчастей</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="text-danger">
                                <h2 class="mb-0">{{ $stats['danger'] }}</h2>
                                <small>Срочно</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <h2 class="mb-0">{{ $stats['warning'] }}</h2>
                                <small>Внимание</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-success">
                                <h2 class="mb-0">{{ $stats['good'] }}</h2>
                                <small>Норма</small>
                            </div>
                        </div>
                    </div>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-danger" style="width: {{ ($stats['danger'] / $stats['total']) * 100 }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ ($stats['warning'] / $stats['total']) * 100 }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ ($stats['good'] / $stats['total']) * 100 }}%"></div>
                    </div>
                    <p class="text-center mb-0">Всего запчастей: <strong>{{ $stats['total'] }}</strong></p>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateMileageModal">
                            <i class="fas fa-sync-alt me-2"></i>Обновить пробег
                        </button>
                        <a href="{{ route('cars.export', $car) }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Экспортировать данные
                        </a>
                        @if($stats['danger'] > 0)
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#criticalPartsModal">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Критические запчасти ({{ $stats['danger'] }})
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая колонка - Визуализация и запчасти -->
        <div class="col-lg-8">
            <!-- Визуализация автомобиля -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Визуализация автомобиля</h5>
                </div>
                <div class="card-body">
                    <div class="car-visualization-modern mb-4">
                        <div class="row g-4 align-items-stretch">
                            <div class="col-xl-8">
                                @include('cars.partials.preview', [
                                    'color' => $car->color ?? '#3498db',
                                    'title' => $car->brand . ' ' . $car->model,
                                    'subtitle' => 'Визуализация автомобиля',
                                ])
                            </div>

                            <div class="col-xl-4">
                                <div class="car-preview-summary">
                                    <div class="car-preview-kicker">Кратко</div>
                                    <h5 class="mb-3">Основные параметры</h5>

                                    <div class="car-preview-summary-item">
                                        <span class="text-muted">Год</span>
                                        <strong>{{ $car->year }}</strong>
                                    </div>

                                    <div class="car-preview-summary-item">
                                        <span class="text-muted">Пробег</span>
                                        <strong>{{ number_format($car->current_mileage, 0, '', ' ') }} км</strong>
                                    </div>

                                    <div class="car-preview-summary-item">
                                        <span class="text-muted">Цвет кузова</span>
                                        <strong>{{ strtoupper($car->color ?? '#3498db') }}</strong>
                                    </div>

                                    @if($car->license_plate)
                                        <div class="car-preview-summary-item">
                                            <span class="text-muted">Гос. номер</span>
                                            <strong>{{ $car->license_plate }}</strong>
                                        </div>
                                    @endif

                                    <div class="pt-3">
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-circle me-1"></i>Требует замены
                                            </span>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Скоро обслуживание
                                            </span>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>В норме
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($car->image && $car->image !== 'default-car.png')
                                <div class="col-12">
                                    <div class="car-photo-card p-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                            <div>
                                                <div class="car-preview-kicker">Фото</div>
                                                <h6 class="mb-0">Изображение автомобиля</h6>
                                            </div>
                                            <span class="badge bg-light text-dark border">Загруженное изображение</span>
                                        </div>
                                        <img src="{{ asset('storage/cars/' . $car->image) }}"
                                             alt="{{ $car->brand }} {{ $car->model }}"
                                             class="img-fluid rounded-4 w-100">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="car-visualization text-center">
                        <!-- Изображение автомобиля -->
                        <div class="car-image-container mb-4">
                            @if($car->image && $car->image !== 'default-car.png')
                                <img src="{{ asset('storage/cars/' . $car->image) }}"
                                     alt="{{ $car->brand }} {{ $car->model }}"
                                     class="img-fluid rounded"
                                     style="max-height: 380px; filter: grayscale(1);">
                            @else
                                <div class="car-silhouette-container mx-auto" style="max-width: 650px;">
                                    <div class="car-silhouette rounded"
                                         style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 30px;">
                                        <svg width="100%" height="240" viewBox="0 0 600 240" role="img" aria-label="Силуэт автомобиля">
                                            <defs>
                                                <linearGradient id="glass" x1="0" y1="0" x2="0" y2="1">
                                                    <stop offset="0%" stop-color="#ffffff" stop-opacity="0.95"/>
                                                    <stop offset="100%" stop-color="#dee2e6" stop-opacity="0.9"/>
                                                </linearGradient>
                                                <linearGradient id="bodyShade" x1="0" y1="0" x2="1" y2="1">
                                                    <stop offset="0%" stop-color="#868e96" stop-opacity="0.85"/>
                                                    <stop offset="100%" stop-color="#495057" stop-opacity="0.95"/>
                                                </linearGradient>
                                            </defs>

                                            <!-- Тень -->
                                            <ellipse cx="300" cy="214" rx="235" ry="16" fill="#000" opacity="0.08"/>

                                            <!-- Кузов (основа) -->
                                            <path id="carBody"
                                                  d="M70 162
                                                     Q84 128 118 120
                                                     L160 98
                                                     Q195 70 250 70
                                                     L350 70
                                                     Q405 70 440 98
                                                     L482 120
                                                     Q516 128 530 162
                                                     L530 182
                                                     Q530 196 516 196
                                                     L512 196
                                                     Q498 196 494 182
                                                     Q486 148 450 148
                                                     Q414 148 406 182
                                                     Q402 196 388 196
                                                     L212 196
                                                     Q198 196 194 182
                                                     Q186 148 150 148
                                                     Q114 148 106 182
                                                     Q102 196 88 196
                                                     L84 196
                                                     Q70 196 70 182
                                                     Z"
                                                  fill="url(#bodyShade)"/>

                                            <!-- Цвет автомобиля (оверлей) -->
                                            <path d="M70 162
                                                     Q84 128 118 120
                                                     L160 98
                                                     Q195 70 250 70
                                                     L350 70
                                                     Q405 70 440 98
                                                     L482 120
                                                     Q516 128 530 162
                                                     L530 182
                                                     Q530 196 516 196
                                                     L512 196
                                                     Q498 196 494 182
                                                     Q486 148 450 148
                                                     Q414 148 406 182
                                                     Q402 196 388 196
                                                     L212 196
                                                     Q198 196 194 182
                                                     Q186 148 150 148
                                                     Q114 148 106 182
                                                     Q102 196 88 196
                                                     L84 196
                                                     Q70 196 70 182
                                                     Z"
                                                  fill="{{ $car->color ?? '#3498db' }}" opacity="0.22"/>

                                            <!-- Линия крыши -->
                                            <path d="M182 110
                                                     L210 90
                                                     Q235 72 262 72
                                                     L338 72
                                                     Q365 72 390 90
                                                     L418 110"
                                                  fill="none" stroke="#343a40" stroke-opacity="0.35" stroke-width="4" stroke-linecap="round"/>

                                            <!-- Стекла -->
                                            <path d="M214 108
                                                     L236 92
                                                     Q248 84 262 84
                                                     L292 84
                                                     L292 120
                                                     L214 120
                                                     Z"
                                                  fill="url(#glass)"/>
                                            <path d="M308 84
                                                     L338 84
                                                     Q352 84 364 92
                                                     L386 108
                                                     L386 120
                                                     L308 120
                                                     Z"
                                                  fill="url(#glass)"/>

                                            <!-- Разделитель дверей -->
                                            <path d="M300 124 L300 188" stroke="#212529" stroke-opacity="0.25" stroke-width="3" stroke-linecap="round"/>

                                            <!-- Фары -->
                                            <path d="M520 158 Q534 164 534 174 Q534 184 520 188"
                                                  fill="#ffd43b" opacity="0.55"/>
                                            <path d="M80 158 Q66 164 66 174 Q66 184 80 188"
                                                  fill="#f8f9fa" opacity="0.35"/>

                                            <!-- Колеса -->
                                            <g>
                                                <circle cx="150" cy="196" r="34" fill="#212529"/>
                                                <circle cx="150" cy="196" r="24" fill="#495057"/>
                                                <circle cx="150" cy="196" r="8" fill="#adb5bd"/>
                                                <path d="M150 172 L150 220 M126 196 L174 196 M134 180 L166 212 M166 180 L134 212"
                                                      stroke="#ced4da" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round"/>
                                            </g>
                                            <g>
                                                <circle cx="450" cy="196" r="34" fill="#212529"/>
                                                <circle cx="450" cy="196" r="24" fill="#495057"/>
                                                <circle cx="450" cy="196" r="8" fill="#adb5bd"/>
                                                <path d="M450 172 L450 220 M426 196 L474 196 M434 180 L466 212 M466 180 L434 212"
                                                      stroke="#ced4da" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round"/>
                                            </g>

                                            <!-- Контур -->
                                            <use href="#carBody" fill="none" stroke="#212529" stroke-opacity="0.18" stroke-width="3"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Легенда статусов -->
                        <div class="status-legend row justify-content-center">
                            <div class="col-auto">
                            <span class="badge bg-danger me-2">
                                <i class="fas fa-exclamation-circle"></i> Требует замены
                            </span>
                            </div>
                            <div class="col-auto">
                            <span class="badge bg-warning me-2">
                                <i class="fas fa-exclamation-triangle"></i> Скоро потребует
                            </span>
                            </div>
                            <div class="col-auto">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> В норме
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Список запчастей по категориям -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Запчасти автомобиля</h5>
                            <span class="badge bg-primary">{{ $stats['total'] }} запчастей</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPartModal">
                                <i class="fas fa-plus me-1"></i>Добавить запчасть
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="deleteSelectedPartsBtn" disabled title="Выберите запчасти для удаления">
                                <i class="fas fa-trash-alt me-1"></i>Удалить выбранные
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Быстрый переход по категориям -->
                    <div class="mb-3 category-nav">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($partsByCategory as $catKey => $parts)
                                <a class="btn btn-outline-secondary btn-sm category-jump"
                                   href="#category-{{ $catKey }}"
                                   aria-label="Перейти к категории: {{ trans("categories.$catKey", [], 'ru') }}">
                                    {{ trans("categories.$catKey", [], 'ru') }}
                                    <span class="badge bg-secondary ms-1">{{ $parts->count() }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Категории запчастей -->
                    @foreach($partsByCategory as $category => $parts)
                        <div class="category-section mb-4" id="category-{{ $category }}">
                            <h6 class="category-title mb-3">
                                <i class="fas fa-folder me-2"></i>{{ trans("categories.$category", [], 'ru') }}
                                <span class="badge bg-secondary">{{ $parts->count() }}</span>
                            </h6>

                            <div class="row">
                                @foreach($parts as $part)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 part-card part-card-{{ $part->status }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="d-flex align-items-start gap-2">
                                                        <input type="checkbox"
                                                               class="form-check-input mt-1 part-select-checkbox"
                                                               value="{{ $part->id }}"
                                                               data-part-id="{{ $part->id }}"
                                                               aria-label="Выбрать запчасть">
                                                        <h6 class="card-title mb-0">{{ $part->name }}</h6>
                                                    </div>
                                                    <span class="badge bg-{{ $part->getStatusClass() }}">
                                            @if($part->status == 'good')
                                                            <i class="fas fa-check me-1"></i>
                                                        @elseif($part->status == 'warning')
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                        @else
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                        @endif
                                        </span>
                                                </div>

                                                <!-- Прогресс бар -->
                                                <div class="part-progress mb-2">
                                                    @php
                                                        $remaining = $car->getRemainingMileageForPart($part);
                                                        $percentage = $car->getPartWearPercentage($part);
                                                        $percentageDisplay = min(100, max(0, 100 - $percentage));
                                                    @endphp
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-{{ $part->getStatusClass() }}"
                                                             style="width: {{ $percentageDisplay }}%"></div>
                                                    </div>
                                                    <small class="text-muted">
                                                        Осталось: {{ number_format(max(0, $remaining), 0, '', ' ') }} км
                                                    </small>
                                                </div>

                                                <!-- Информация о замене -->
                                                <div class="part-info">
                                                    <small class="d-block text-muted">
                                                        <i class="fas fa-history me-1"></i>
                                                        Последняя замена: {{ number_format($part->last_replacement_mileage, 0, '', ' ') }} км
                                                    </small>
                                                    <small class="d-block text-muted">
                                                        <i class="fas fa-redo me-1"></i>
                                                        Интервал замены: {{ number_format($part->replacement_interval, 0, '', ' ') }} км
                                                    </small>
                                                    @if($part->notes)
                                                        <small class="d-block text-muted mt-1">
                                                            <i class="fas fa-sticky-note me-1"></i>
                                                            {{ Str::limit($part->notes, 50) }}
                                                        </small>
                                                    @endif
                                                </div>

                                                <!-- Кнопка замены -->
                                                @if($part->status == 'danger')
                                                    <div class="mt-3">
                                                        <button class="btn btn-sm btn-success w-100 replace-part-btn"
                                                                data-part-id="{{ $part->id }}"
                                                                data-part-name="{{ $part->name }}">
                                                            <i class="fas fa-check me-1"></i>Отметить как замененную
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно обновления пробега -->
    <div class="modal fade" id="updateMileageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-tachometer-alt me-2"></i>Обновить пробег
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateMileageForm">
                        <div class="mb-3">
                            <label for="newMileage" class="form-label">Новый пробег (км)</label>
                            <input type="number"
                                   class="form-control"
                                   id="newMileage"
                                   name="current_mileage"
                                   min="{{ $car->current_mileage }}"
                                   value="{{ $car->current_mileage + 1000 }}"
                                   required>
                            <div class="form-text">
                                Текущий пробег: {{ number_format($car->current_mileage, 0, '', ' ') }} км
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateMileage">
                        <i class="fas fa-sync-alt me-2"></i>Обновить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно добавления запчасти -->
    <div class="modal fade" id="addPartModal" tabindex="-1" aria-labelledby="addPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPartModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Добавить запчасть
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="addPartForm">
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        <div class="mb-3">
                            <label for="partName" class="form-label">Название *</label>
                            <input type="text"
                                   class="form-control"
                                   id="partName"
                                   name="name"
                                   required
                                   maxlength="255"
                                   placeholder="Например: Масло двигателя">
                        </div>
                        <div class="mb-3">
                            <label for="partCategory" class="form-label">Категория *</label>
                            <select class="form-select" id="partCategory" name="category" required>
                                <option value="">Выберите категорию</option>
                                @foreach($partCategories as $catKey)
                                    <option value="{{ $catKey }}">{{ trans("categories.$catKey", [], 'ru') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="partInterval" class="form-label">Максимальный пробег запчасти (км) *</label>
                            <input type="number"
                                   class="form-control"
                                   id="partInterval"
                                   name="replacement_interval"
                                   min="1000"
                                   step="1000"
                                   value="15000"
                                   required
                                   placeholder="Интервал замены в км">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success" id="submitAddPart">
                        <i class="fas fa-plus me-1"></i>Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления запчастей -->
    <div class="modal fade" id="deletePartsModal" tabindex="-1" aria-labelledby="deletePartsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deletePartsModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Удалить запчасти
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    Вы уверены, что хотите удалить выбранные запчасти? Действие необратимо.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteParts">
                        <i class="fas fa-trash-alt me-1"></i>Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно критических запчастей -->
    <div class="modal fade" id="criticalPartsModal" tabindex="-1">
        @php
            $criticalPartIds = $partsByCategory->flatMap(function ($parts) {
                return $parts->where('status', 'danger')->pluck('id');
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
                    @if($stats['danger'] > 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Внимание!</strong> Эти запчасти требуют немедленной замены:
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr class="table-danger">
                                    <th>Запчасть</th>
                                    <th>Категория</th>
                                    <th>Пробег до замены</th>
                                    <th>Последняя замена</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($partsByCategory as $category => $parts)
                                    @foreach($parts->where('status', 'danger') as $part)
                                        <tr>
                                            <td>{{ $part->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ trans("categories.$category", [], 'ru') }}</span>
                                            </td>
                                            <td>
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Просрочено на {{ number_format(abs($car->getRemainingMileageForPart($part)), 0, '', ' ') }} км
                                            </span>
                                            </td>
                                            <td>
                                                {{ number_format($part->last_replacement_mileage, 0, '', ' ') }} км
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

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Рекомендация:</strong> Замените эти запчасти как можно скорее,
                            чтобы избежать поломок и обеспечить безопасность.
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

@push('styles')
    <style>
        .car-card {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .car-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .part-card-danger {
            border-left-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
            animation: pulse-danger 2s infinite;
        }

        .part-card-warning {
            border-left-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.05);
        }

        .part-card-good {
            border-left-color: #28a745;
            background-color: rgba(40, 167, 69, 0.05);
        }

        .category-title {
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
        }

        .category-section {
            scroll-margin-top: 90px;
        }

        .car-silhouette {
            transition: all 0.3s ease;
        }

        .car-silhouette:hover {
            transform: scale(1.02);
        }

        .car-visualization.text-center {
            display: none;
        }

        @keyframes pulse-danger {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 5px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        .progress {
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Плавная прокрутка к категории запчастей
            document.querySelectorAll('.category-jump').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (!href || !href.startsWith('#')) return;
                    const target = document.querySelector(href);
                    if (!target) return;

                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    history.replaceState(null, '', href);
                });
            });

            // Обновление пробега
            document.getElementById('confirmUpdateMileage').addEventListener('click', function() {
                const newMileage = document.getElementById('newMileage').value;
                const currentMileage = {{ $car->current_mileage }};

                if (!newMileage || parseInt(newMileage) < currentMileage) {
                    alert('Введите пробег больше текущего');
                    return;
                }

                updateCarMileage({{ $car->id }}, newMileage);
            });

            // Замена запчасти
            document.querySelectorAll('.replace-part-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const partId = this.dataset.partId;
                    const partName = this.dataset.partName || 'эту запчасть';

                    if (confirm(`Отметить "${partName}" как замененную?`)) {
                        replacePart(partId);
                    }
                });
            });

            document.querySelectorAll('.replace-all-parts-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const partIds = JSON.parse(this.dataset.partIds || '[]');

                    if (!partIds.length) {
                        return;
                    }

                    if (confirm('Отметить все критические запчасти как замененные?')) {
                        replaceParts(partIds, this);
                    }
                });
            });

            // Включение/выключение кнопки «Удалить выбранные»
            function updateDeleteSelectedButton() {
                const checked = document.querySelectorAll('.part-select-checkbox:checked');
                document.getElementById('deleteSelectedPartsBtn').disabled = checked.length === 0;
            }
            document.querySelectorAll('.part-select-checkbox').forEach(cb => {
                cb.addEventListener('change', updateDeleteSelectedButton);
            });
            updateDeleteSelectedButton();

            // Удалить выбранные запчасти — открыть модальное окно
            document.getElementById('deleteSelectedPartsBtn').addEventListener('click', function() {
                const ids = Array.from(document.querySelectorAll('.part-select-checkbox:checked')).map(cb => cb.value);
                if (ids.length === 0) return;
                const modal = new bootstrap.Modal(document.getElementById('deletePartsModal'));
                modal.show();
                window._partsToDelete = ids;
            });

            // Подтверждение удаления запчастей
            document.getElementById('confirmDeleteParts').addEventListener('click', function() {
                const ids = window._partsToDelete;
                if (!ids || ids.length === 0) return;
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Удаление...';
                btn.disabled = true;

                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                ids.forEach(id => formData.append('part_ids[]', id));

                fetch('{{ route("parts.batch-destroy") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(document.getElementById('deletePartsModal')).hide();
                            showAlert('success', 'Запчасти удалены.');
                            setTimeout(() => location.reload(), 800);
                        }
                    })
                    .catch(() => {
                        showAlert('error', 'Ошибка при удалении');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
            });

            // Добавить запчасть
            document.getElementById('submitAddPart').addEventListener('click', function() {
                const form = document.getElementById('addPartForm');
                const name = document.getElementById('partName').value.trim();
                const category = document.getElementById('partCategory').value;
                const interval = document.getElementById('partInterval').value;

                if (!name || !category || !interval || parseInt(interval, 10) < 1000) {
                    alert('Заполните все поля. Максимальный пробег — не менее 1000 км.');
                    return;
                }

                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Добавление...';
                btn.disabled = true;

                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('car_id', form.querySelector('[name="car_id"]').value);
                formData.append('name', name);
                formData.append('category', category);
                formData.append('replacement_interval', interval);

                fetch('{{ route("parts.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                    .then(r => r.json().then(data => ({ ok: r.ok, data })))
                    .then(({ ok, data }) => {
                        if (ok && data.success) {
                            bootstrap.Modal.getInstance(document.getElementById('addPartModal')).hide();
                            form.reset();
                            document.getElementById('partInterval').value = 15000;
                            showAlert('success', 'Запчасть добавлена.');
                            setTimeout(() => location.reload(), 800);
                        } else {
                            const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Ошибка при добавлении');
                            showAlert('error', msg);
                        }
                    })
                    .catch(err => {
                        showAlert('error', 'Ошибка при добавлении запчасти');
                        console.error(err);
                    })
                    .finally(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
            });
        });

        // Функция обновления пробега
        function updateCarMileage(carId, newMileage) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Показываем индикатор загрузки
            const button = document.getElementById('confirmUpdateMileage');
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Обновление...';
            button.disabled = true;

            fetch(`/cars/${carId}/update-mileage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_mileage: parseInt(newMileage)
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Закрываем модальное окно
                        const modal = bootstrap.Modal.getInstance(document.getElementById('updateMileageModal'));
                        modal.hide();

                        // Показываем уведомление
                        showAlert('success', 'Пробег успешно обновлен!');

                        // Перезагружаем страницу для обновления данных
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Произошла ошибка при обновлении пробега');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Функция замены запчасти
        function replacePart(partId) {
            replaceParts([partId]);
        }

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
                        showAlert('success', partIds.length > 1
                            ? 'Критические запчасти отмечены как замененные!'
                            : 'Запчасть отмечена как замененная!');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (button) {
                        button.disabled = false;
                    }
                    showAlert('error', 'Произошла ошибка');
                });
        }

        // Показать уведомление
        function showAlert(type, message) {
            const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3"
                 role="alert" style="z-index: 9999;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

            document.body.insertAdjacentHTML('beforeend', alertHtml);

            // Автоматически скрыть через 5 секунд
            setTimeout(() => {
                const alert = document.querySelector('.alert.position-fixed');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
@endpush
