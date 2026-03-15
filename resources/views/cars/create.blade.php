@extends('layouts.app')

@section('title', 'Добавить автомобиль')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-car me-2"></i>Добавить новый автомобиль</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('cars.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Марка *</label>
                                <select class="form-select @error('brand') is-invalid @enderror"
                                        id="brand_select"
                                        name="">
                                    <option value="">Выберите из списка или введите вручную</option>
                                    <option value="Mercedes-Benz" {{ old('brand') == 'Mercedes-Benz' ? 'selected' : '' }}>Mercedes-Benz</option>
                                    <option value="BMW" {{ old('brand') == 'BMW' ? 'selected' : '' }}>BMW</option>
                                    <option value="Ford" {{ old('brand') == 'Ford' ? 'selected' : '' }}>Ford</option>
                                    <option value="Hyundai" {{ old('brand') == 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                                    <option value="KIA" {{ old('brand') == 'KIA' ? 'selected' : '' }}>KIA</option>
                                    <option value="Renault" {{ old('brand') == 'Renault' ? 'selected' : '' }}>Renault</option>
                                    <option value="Skoda" {{ old('brand') == 'Skoda' ? 'selected' : '' }}>Skoda</option>
                                    <option value="Toyota" {{ old('brand') == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                                    <option value="Volkswagen" {{ old('brand') == 'Volkswagen' ? 'selected' : '' }}>Volkswagen</option>
                                    <option value="LADA" {{ old('brand') == 'LADA' ? 'selected' : '' }}>LADA(ВАЗ)</option>
                                    <option value="Nissan" {{ old('brand') == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                                    <option value="Chevrolet" {{ old('brand') == 'Chevrolet' ? 'selected' : '' }}>Chevrolet</option>
                                    <option value="Honda" {{ old('brand') == 'Honda' ? 'selected' : '' }}>Honda</option>
                                    <option value="Audi" {{ old('brand') == 'Audi' ? 'selected' : '' }}>Audi</option>
                                </select>
                                <input type="text"
                                       class="form-control @error('brand') is-invalid @enderror mt-2"
                                       id="brand"
                                       name="brand"
                                       value="{{ old('brand') }}"
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
                                       value="{{ old('model') }}"
                                       required>
                                @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            @include('cars.partials.preview', [
                                'color' => old('color', '#3498db'),
                                'title' => 'Предпросмотр кузова',
                                'subtitle' => 'Подбор цвета',
                                'note' => 'Меняйте цвет в форме, и машина сразу обновится в предпросмотре.',
                            ])
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label">Год выпуска *</label>
                                <input type="number"
                                       class="form-control @error('year') is-invalid @enderror"
                                       id="year"
                                       name="year"
                                       value="{{ old('year', date('Y') - 1) }}"
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
                                       value="{{ old('color', '#3498db') }}"
                                       title="Выберите цвет">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="initial_mileage" class="form-label">Начальный пробег (км) *</label>
                                <input type="number"
                                       class="form-control @error('initial_mileage') is-invalid @enderror"
                                       id="initial_mileage"
                                       name="initial_mileage"
                                       value="{{ old('initial_mileage', 0) }}"
                                       min="0"
                                       required>
                                @error('initial_mileage')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vin" class="form-label">VIN (идентификационный номер)</label>
                                <input type="text"
                                       class="form-control @error('vin') is-invalid @enderror"
                                       id="vin"
                                       name="vin"
                                       value="{{ old('vin') }}"
                                       maxlength="17">
                                @error('vin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">17 символов, можно оставить пустым</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="license_plate" class="form-label">Государственный номер</label>
                                <input type="text"
                                       class="form-control @error('license_plate') is-invalid @enderror"
                                       id="license_plate"
                                       name="license_plate"
                                       value="{{ old('license_plate') }}">
                                @error('license_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение автомобиля</label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Поддерживаются форматы: jpeg, png, jpg, gif (макс. 2MB)</small>
                        </div>

                        <div class="mb-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                При добавлении автомобиля автоматически создается набор стандартных запчастей
                                с рекомендуемыми интервалами замены.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('cars.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Сохранить автомобиль
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
