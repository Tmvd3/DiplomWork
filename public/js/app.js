/**
 * CarCare - Основной JavaScript файл
 */

// Глобальные переменные
const CarCare = {
    // Инициализация приложения
    init: function() {
        this.bindEvents();
        this.setupAjax();
        console.log('CarCare инициализирован');
    },

    // Привязка событий
    bindEvents: function() {
        // Глобальные обработчики
        this.bindGlobalHandlers();

        // События для автомобилей
        this.bindCarEvents();

        // События для запчастей
        this.bindPartEvents();
    },

    // Настройка AJAX
    setupAjax: function() {
        // Получаем CSRF токен
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Настройка глобальных заголовков для AJAX запросов
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
    },

    // Глобальные обработчики событий
    bindGlobalHandlers: function() {
        // Форматирование чисел (пробег)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('format-mileage')) {
                CarCare.formatMileageInput(e.target);
            }
        });

        // Подтверждение удаления
        document.addEventListener('click', function(e) {
            if (e.target.closest('.confirm-delete')) {
                e.preventDefault();
                CarCare.confirmAction(e.target.closest('.confirm-delete'));
            }
        });
    },

    // События для автомобилей
    bindCarEvents: function() {
        // Обновление пробега
        document.addEventListener('click', function(e) {
            if (e.target.closest('.update-mileage-btn')) {
                const button = e.target.closest('.update-mileage-btn');
                CarCare.updateCarMileage(button);
            }
        });

        // Enter в поле пробега
        document.addEventListener('keypress', function(e) {
            if (e.target.classList.contains('mileage-input') && e.key === 'Enter') {
                const carId = e.target.dataset.carId;
                const button = document.querySelector(`.update-mileage-btn[data-car-id="${carId}"]`);
                if (button) {
                    CarCare.updateCarMileage(button);
                }
            }
        });
    },

    // События для запчастей
    bindPartEvents: function() {
        // Замена запчасти
        document.addEventListener('click', function(e) {
            if (e.target.closest('.replace-part-btn')) {
                const button = e.target.closest('.replace-part-btn');
                CarCare.replacePart(button);
            }
        });
    },

    // Форматирование ввода пробега
    formatMileageInput: function (input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 3) {
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }
        input.value = value;
    },

    // Обновление пробега автомобиля
    updateCarMileage: function(button) {
        const carId = button.dataset.carId;
        const input = document.querySelector(`#new-mileage-${carId}`);
        const newMileage = parseInt(input.value.replace(/\s/g, ''));

        if (!newMileage || isNaN(newMileage)) {
            CarCare.showAlert('warning', 'Введите корректный пробег');
            return;
        }

        // Показываем индикатор загрузки
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="loading"></span> Обновление...';
        button.disabled = true;

        $.ajax({
            url: `/cars/${carId}/update-mileage`,
            method: 'POST',
            data: JSON.stringify({ current_mileage: newMileage }),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    // Обновляем отображение пробега
                    $(`#current-mileage-${carId}`).text(
                        newMileage.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
                    );

                    // Сбрасываем поле ввода
                    input.value = '';

                    // Обновляем запчасти
                    CarCare.updatePartsDisplay(response.parts);

                    CarCare.showAlert('success', 'Пробег успешно обновлен!');
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Произошла ошибка';
                CarCare.showAlert('error', error);
            },
            complete: function() {
                // Восстанавливаем кнопку
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    },

    // Замена запчасти
    replacePart: function(button) {
        const partId = button.dataset.partId;

        if (!confirm('Отметить запчасть как замененную?')) {
            return;
        }

        // Показываем индикатор загрузки
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="loading"></span>';
        button.disabled = true;

        $.ajax({
            url: `/parts/${partId}/replaced`,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    // Перезагружаем страницу для обновления данных
                    location.reload();
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Произошла ошибка';
                CarCare.showAlert('error', error);
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    },

    // Обновление отображения запчастей
    updatePartsDisplay: function(parts) {
        parts.forEach(part => {
            const row = $(`#part-row-${part.id}`);
            const remainingSpan = $(`#remaining-${part.id}`);
            const statusBadge = row.find('.badge');

            if (row.length && remainingSpan.length) {
                // Обновляем оставшийся пробег
                remainingSpan.text(part.remaining.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' км');

                // Обновляем классы строки
                row.removeClass('part-good part-warning part-danger')
                    .addClass(`part-${part.status}`);

                // Обновляем бейдж статуса
                statusBadge.removeClass('bg-success bg-warning bg-danger')
                    .addClass(`bg-${part.status_class}`);

                // Обновляем текст бейджа
                statusBadge.html(CarCare.getStatusIcon(part.status) + CarCare.getStatusText(part.status));
            }
        });
    },

    // Получить иконку статуса
    getStatusIcon: function(status) {
        switch(status) {
            case 'good': return '<i class="fas fa-check me-1"></i>';
            case 'warning': return '<i class="fas fa-exclamation-triangle me-1"></i>';
            case 'danger': return '<i class="fas fa-exclamation-circle me-1"></i>';
            default: return '';
        }
    },

    // Получить текст статуса
    getStatusText: function(status) {
        switch(status) {
            case 'good': return 'Хорошо';
            case 'warning': return 'Внимание';
            case 'danger': return 'Требует замены';
            default: return '';
        }
    },

    // Показать уведомление
    showAlert: function(type, message) {
        // Удаляем старые уведомления
        $('.car-care-alert').remove();

        // Создаем новое уведомление
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show car-care-alert"
                 role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        $('body').append(alertHtml);

        // Автоматически скрываем через 5 секунд
        setTimeout(() => {
            $('.car-care-alert').alert('close');
        }, 5000);
    },

    // Подтверждение действия
    confirmAction: function(element) {
        const message = element.dataset.confirm || 'Вы уверены?';
        const url = element.href;
        const method = element.dataset.method || 'POST';

        if (confirm(message)) {
            if (method === 'DELETE') {
                // Для DELETE используем форму
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = `
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                `;
                document.body.appendChild(form);
                form.submit();
            } else {
                // Для других методов используем AJAX
                $.ajax({
                    url: url,
                    method: method,
                    success: function() {
                        location.reload();
                    }
                });
            }
        }
    }
};

// Инициализация приложения при загрузке страницы
$(document).ready(function() {
    CarCare.init();
});
