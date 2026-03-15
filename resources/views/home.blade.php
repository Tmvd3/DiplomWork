@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm home-hero overflow-hidden">
                <div class="card-body p-4 p-lg-5">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-7">
                            <span class="badge bg-light text-primary border home-badge mb-3">
                                Полезно для каждого водителя
                            </span>
                            <h1 class="display-6 fw-bold mb-3">
                                УмныйГараж - лучший друг и помошник автомобилиста!
                            </h1>
                            <p class="lead text-muted mb-4">
                                Здесь собраны важные изменения для водителей и короткие рекомендации по уходу за автомобилем.
                                Ваш автомобиль - Ваша гордость!
                            </p>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                    <i class="fas fa-road me-2 text-primary"></i>Изменения которые затронут водителей 2025-2026
                                </span>
                                <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                    <i class="fas fa-tools me-2 text-success"></i>Краткий уход за машиной
                                </span>
                                <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                    <i class="fas fa-shield-alt me-2 text-warning"></i>Практичная памятка
                                </span>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Войти
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Регистрация
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card h-100 home-highlight">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-clipboard-check me-2 text-primary"></i>Почему это удобно
                                    </h5>

                                    <div class="home-highlight-item">
                                        <div class="home-highlight-icon bg-primary">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Важное перед глазами</h6>
                                            <p class="text-muted mb-0">Сразу видно, что поменялось для водителей и на что обратить внимание.</p>
                                        </div>
                                    </div>

                                    <div class="home-highlight-item">
                                        <div class="home-highlight-icon bg-success">
                                            <i class="fas fa-oil-can"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Меньше пропусков в обслуживании</h6>
                                            <p class="text-muted mb-0">Простые советы помогают не забывать о базовых проверках автомобиля.</p>
                                        </div>
                                    </div>

                                    <div class="home-highlight-item mb-0">
                                        <div class="home-highlight-icon bg-warning text-dark">
                                            <i class="fas fa-car-side"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Больше уверенности в дороге</h6>
                                            <p class="text-muted mb-0">Исправные шины, свет и жидкости снижают риск неприятных сюрпризов в пути.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-balance-scale me-2 text-primary"></i>Что изменилось для водителей
                    </h2>
                    <p class="text-muted mb-0">Краткий обзор заметных изменений на март 2026 года.</p>
                </div>
                <span class="badge bg-light text-dark border px-3 py-2">Обновлено: март 2026</span>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-danger">С 1 января 2025</span>
                        <i class="fas fa-receipt fa-2x text-danger opacity-75"></i>
                    </div>
                    <h5 class="card-title">Выше штрафы</h5>
                    <p class="card-text text-muted mb-0">
                        По многим нарушениям штрафы выросли. Скидка за быструю оплату теперь составляет 25%,
                        зато срок оплаты со скидкой увеличен до 30 дней.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary">С 1 марта 2025</span>
                        <i class="fas fa-file-signature fa-2x text-primary opacity-75"></i>
                    </div>
                    <h5 class="card-title">Учёт без ОСАГО</h5>
                    <p class="card-text text-muted mb-0">
                        Для постановки автомобиля на учёт полис ОСАГО больше не нужен,
                        но ездить без действующей страховки всё так же нельзя.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-warning text-dark">С 18 июля 2025</span>
                        <i class="fas fa-hand-paper fa-2x text-warning opacity-75"></i>
                    </div>
                    <h5 class="card-title">Строже за игнорирование остановки</h5>
                    <p class="card-text text-muted mb-0">
                        За невыполнение требования остановиться предусмотрен штраф 7 000-10 000 рублей,
                        а за повторное нарушение возможно лишение прав на 4-6 месяцев.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-success">С 9 января 2026</span>
                        <i class="fas fa-child fa-2x text-success opacity-75"></i>
                    </div>
                    <h5 class="card-title">Штраф выше за ребёнка без кресла</h5>
                    <p class="card-text text-muted mb-0">
                        Для водителя штраф за перевозку ребёнка без автокресла увеличен до 5 000 рублей.
                        Перед поездкой стоит проверить кресло и ремни.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="alert alert-light border mb-0">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                Это краткая памятка по важным изменениям для водителей. Перед поездкой, регистрацией автомобиля
                и оформлением документов лучше сверяться с актуальной редакцией правил и требований.
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0"><i class="fas fa-tools me-2 text-success"></i>Как кратко ухаживать за машиной</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="home-care-card">
                                <h5><i class="fas fa-oil-can me-2 text-primary"></i>Следите за жидкостями</h5>
                                <p class="text-muted mb-0">
                                    Раз в 1-2 недели проверяйте масло, антифриз, тормозную жидкость и запас омывателя.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="home-care-card">
                                <h5><i class="fas fa-circle-notch me-2 text-primary"></i>Контролируйте шины</h5>
                                <p class="text-muted mb-0">
                                    Проверяйте давление, износ протектора и соответствие сезона.
                                    Это влияет и на безопасность, и на расход топлива.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="home-care-card">
                                <h5><i class="fas fa-car-battery me-2 text-primary"></i>Проверяйте аккумулятор</h5>
                                <p class="text-muted mb-0">
                                    Перед холодным сезоном осмотрите клеммы, заряд и признаки окисления,
                                    особенно если автомобиль долго стоит.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="home-care-card">
                                <h5><i class="fas fa-wrench me-2 text-primary"></i>Соблюдайте регламент</h5>
                                <p class="text-muted mb-0">
                                    Меняйте масло, фильтры, свечи, колодки и другие расходники по пробегу и времени,
                                    а не только когда уже появилась проблема.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0"><i class="fas fa-list-check me-2 text-warning"></i>Памятка перед поездкой</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush home-checklist">
                        <li class="list-group-item">Проверьте предупреждения на приборной панели.</li>
                        <li class="list-group-item">Убедитесь, что работают фары, стоп-сигналы и поворотники.</li>
                        <li class="list-group-item">Перед дальней дорогой проверьте давление в шинах.</li>
                        <li class="list-group-item">Держите рядом документы, аптечку, огнетушитель и аварийный знак.</li>
                    </ul>
                </div>
            </div>

            <div class="card home-cta-card text-white">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3"><i class="fas fa-car-side me-2"></i>Хотите вести обслуживание удобнее?</h2>
                    <p class="mb-4">
                        Войдите в систему, чтобы хранить данные об автомобилях, следить за пробегом
                        и не забывать про замену расходников.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-light">
                            <i class="fas fa-sign-in-alt me-2"></i>Перейти ко входу
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light">
                            <i class="fas fa-user-plus me-2"></i>Создать аккаунт
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .home-hero {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.08), rgba(52, 152, 219, 0.18));
        }

        .home-badge {
            font-size: 0.95rem;
            border-color: rgba(52, 152, 219, 0.2) !important;
        }

        .home-highlight {
            border: 1px solid rgba(44, 62, 80, 0.08);
            background: rgba(255, 255, 255, 0.94);
        }

        .home-highlight-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }

        .home-highlight-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .home-care-card {
            height: 100%;
            padding: 1.25rem;
            border-radius: 12px;
            background: #f8f9fa;
            border: 1px solid rgba(44, 62, 80, 0.08);
        }

        .home-care-card h5 {
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .home-checklist .list-group-item {
            padding-left: 0;
            padding-right: 0;
            border-color: rgba(44, 62, 80, 0.08);
        }

        .home-cta-card {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            border: none;
        }
    </style>
@endpush
