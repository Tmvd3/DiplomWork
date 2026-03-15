@extends('layouts.app')

@section('title', 'Редактировать автомобиль')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Редактировать автомобиль</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('cars.update', $car) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Марка *</label>
                                @php
                                    $brandOptions = ['Mercedes-Benz', 'BMW', 'Ford', 'Hyundai', 'KIA', 'Renault', 'Skoda', 'Toyota', 'Volkswagen', 'LADA', 'Nissan', 'Chevrolet', 'Honda', 'Audi'];
                                    $currentBrand = old('brand', $car->brand);
                                    $isBrandInList = in_array($currentBrand, $brandOptions);
                                @endphp
                                <select class="form-select @error('brand') is-invalid @enderror"
                                        id="brand_select"
                                        name="">
                                    <option value="">Выберите из списка или введите вручную</option>
                                    @foreach($brandOptions as $option)
                                        <option value="{{ $option }}" {{ $currentBrand == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                <input type="text"
                                       class="form-control @error('brand') is-invalid @enderror mt-2"
                                       id="brand"
                                       name="brand"
                                       value="{{ $currentBrand }}"
                                       placeholder="Или введите марку вручную"
                                       required>
                                @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="model" class="form-label">Модель *</label>
                                <input type="text"
                                       class="form-control @error('model') is-invalid @enderror"
                                       id="model"
                                       name="model"
                                       value="{{ old('model', $car->model) }}"
                                       required>
                                @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            @include('cars.partials.preview', [
                                'color' => old('color', $car->color ?? '#3498db'),
                                'title' => $car->brand . ' ' . $car->model,
                                'subtitle' => 'Предпросмотр цвета',
                                'note' => 'Выбранный цвет сразу применяется к кузову и помогает оценить внешний вид до сохранения.',
                            ])
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label">Год выпуска *</label>
                                <input type="number"
                                       class="form-control @error('year') is-invalid @enderror"
                                       id="year"
                                       name="year"
                                       value="{{ old('year', $car->year) }}"
                                       min="1900"
                                       max="{{ date('Y') }}"
                                       required>
                                @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="color" class="form-label">Цвет</label>
                                <input type="color"
                                       class="form-control form-control-color"
                                       id="color"
                                       name="color"
                                       value="{{ old('color', $car->color ?? '#3498db') }}"
                                       title="Выберите цвет">
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Текущий пробег</label>
                                    <div class="form-control-plaintext">
                                        <strong>{{ number_format($car->current_mileage, 0, '', ' ') }} км</strong>
                                    </div>
                                    <small class="text-muted">
                                        Чтобы изменить пробег, используйте функцию "Обновить пробег" на странице автомобиля
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vin" class="form-label">VIN (идентификационный номер)</label>
                                <input type="text"
                                       class="form-control @error('vin') is-invalid @enderror"
                                       id="vin"
                                       name="vin"
                                       value="{{ old('vin', $car->vin) }}"
                                       maxlength="17">
                                @error('vin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="license_plate" class="form-label">Государственный номер</label>
                                <input type="text"
                                       class="form-control @error('license_plate') is-invalid @enderror"
                                       id="license_plate"
                                       name="license_plate"
                                       value="{{ old('license_plate', $car->license_plate) }}">
                                @error('license_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение автомобиля</label>

                            @if($car->image && $car->image !== 'default-car.png')
                                <div class="mb-2">
                                    <p class="mb-1">Текущее изображение:</p>
                                    <img src="{{ asset('storage/cars/' . $car->image) }}"
                                         alt="{{ $car->brand }} {{ $car->model }}"
                                         class="img-thumbnail"
                                         style="max-height: 150px;">
                                </div>
                            @endif

                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Оставьте пустым, чтобы сохранить текущее изображение</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('cars.show', $car) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-control-color {
            height: 38px;
            padding: 2px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brandSelect = document.getElementById('brand_select');
            const brandInput = document.getElementById('brand');
            // При выборе из списка автоматически заполняем поле ввода
            brandSelect.addEventListener('change', function() {
                if (this.value) {
                    brandInput.value = this.value;
                }
            });

            // При вводе вручную сбрасываем выбор в списке
            brandInput.addEventListener('input', function() {
                if (this.value && brandSelect.value && this.value !== brandSelect.value) {
                    brandSelect.value = '';
                }
            });

            const colorInput = document.getElementById('color');
            const previews = document.querySelectorAll('[data-car-preview]');

            function updatePreviewColor(color) {
                previews.forEach(preview => {
                    preview.style.setProperty('--car-paint', color);

                    const label = preview.querySelector('[data-car-preview-value]');
                    if (label) {
                        label.textContent = color.toUpperCase();
                    }

                    preview.querySelectorAll('[data-car-preview-body]').forEach(body => {
                        body.style.fill = color;
                    });
                });
            }

            if (colorInput) {
                updatePreviewColor(colorInput.value);
                colorInput.addEventListener('input', function() {
                    updatePreviewColor(this.value);
                });
            }

        });
    </script>
@endpush
