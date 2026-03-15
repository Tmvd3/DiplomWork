@extends('layouts.app')

@section('title', 'Мои автомобили')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-car me-2"></i>Мои автомобили</h1>
            <p class="text-muted">Список автомобилей и состояние их запчастей</p>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="{{ route('cars.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Добавить автомобиль
                </a>
                <a href="{{ route('cars.import.form') }}" class="btn btn-outline-primary">
                    <i class="fas fa-file-import me-2"></i>Импорт
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($cars->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-car fa-4x text-muted mb-3"></i>
                    <h5>У вас нет автомобилей</h5>
                    <p class="text-muted mb-4">Добавьте автомобиль вручную или импортируйте из JSON</p>
                    <a href="{{ route('cars.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus-circle me-2"></i>Добавить автомобиль
                    </a>
                    <a href="{{ route('cars.import.form') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-file-import me-2"></i>Импорт из JSON
                    </a>
                </div>
            @else
                <div class="row">
                    @foreach($cars as $car)
                        <div class="col-md-6 col-lg-4 mb-3">
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

                                    @php
                                        $dangerCount = $car->parts->where('status', 'danger')->count();
                                        $warningCount = $car->parts->where('status', 'warning')->count();
                                        $goodCount = $car->parts->where('status', 'good')->count();
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
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm delete-car-btn"
                                                data-car-id="{{ $car->id }}"
                                                data-car-name="{{ $car->brand }} {{ $car->model }}">
                                            <i class="fas fa-trash-alt me-1"></i>Удалить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteCarModal');
            const confirmBtn = document.getElementById('confirmDeleteCar');
            const carNamePlaceholder = document.getElementById('deleteCarName');
            const deleteForm = document.getElementById('deleteCarForm');

            document.querySelectorAll('.delete-car-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const carId = btn.dataset.carId;
                    const carName = btn.dataset.carName || 'этот автомобиль';

                    carNamePlaceholder.textContent = carName;
                    deleteForm.action = `{{ url('/cars') }}/${carId}`;

                    const modal = new bootstrap.Modal(deleteModal);
                    modal.show();
                });
            });

            confirmBtn.addEventListener('click', () => {
                deleteForm.submit();
            });
        });
    </script>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal fade" id="deleteCarModal" tabindex="-1" aria-labelledby="deleteCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteCarModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Удалить автомобиль
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены, что хотите удалить <strong id="deleteCarName"></strong>? Действие необратимо.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCar">
                        <i class="fas fa-trash-alt me-1"></i>Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Форма удаления -->
    <form id="deleteCarForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endpush
