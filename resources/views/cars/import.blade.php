@extends('layouts.app')

@section('title', 'Импорт автомобиля')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-file-import me-2"></i>Импорт автомобиля из JSON</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Вы можете импортировать данные об автомобиле из JSON файла,
                        который был ранее экспортирован из системы.
                    </div>

                    <form method="POST" action="{{ route('cars.import') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="json_file" class="form-label">JSON файл</label>
                            <input type="file"
                                   class="form-control @error('json_file') is-invalid @enderror"
                                   id="json_file"
                                   name="json_file"
                                   accept=".json"
                                   required>
                            @error('json_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Поддерживаются только файлы в формате JSON (максимум 2MB)
                            </small>
                        </div>

                        <div class="mb-4">
                            <h5>Что будет импортировано:</h5>
                            <ul>
                                <li>Информация об автомобиле (марка, модель, год, пробег)</li>
                                <li>Список запчастей с их характеристиками</li>
                                <li>История замен запчастей</li>
                            </ul>
                            <p class="text-muted">
                                Примечание: Изображение автомобиля не импортируется, будет использовано стандартное.
                            </p>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('cars.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Импортировать
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Пример структуры JSON -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-code me-2"></i>Пример структуры JSON файла</h5>
                </div>
                <div class="card-body">
                <pre class="bg-light p-3 rounded" style="font-size: 0.9rem;">
{
    "car": {
        "brand": "Toyota",
        "model": "Camry",
        "year": 2020,
        "color": "#3498db",
        "initial_mileage": 15000,
        "current_mileage": 65430,
        "vin": "JTDBU4E40CL000000",
        "license_plate": "А123ВС77"
    },
    "parts": [
        {
            "name": "Масло двигателя",
            "category": "engine",
            "replacement_interval": 15000,
            "last_replacement_mileage": 50000,
            "next_replacement_mileage": 65000,
            "status": "warning",
            "notes": null
        }
    ],
    "export_date": "2024-01-20 15:30:00"
}</pre>
                    <div class="mt-3">
                        <a href="{{ route('cars.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>
                            Перейти к списку автомобилей (экспорт на странице каждого авто)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
