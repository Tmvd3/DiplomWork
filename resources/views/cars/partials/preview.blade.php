@php
    $previewColor = $color ?? '#3498db';
    $previewTitle = $title ?? 'Визуализация автомобиля';
    $previewSubtitle = $subtitle ?? null;
    $previewNote = $note ?? null;
    $previewId = 'car-preview-' . str_replace('.', '', uniqid('', true));
@endphp

<div class="car-preview-shell {{ $containerClass ?? '' }}" data-car-preview style="--car-paint: {{ $previewColor }};">
    <div class="car-preview-meta">
        <div>
            @if($previewSubtitle)
                <div class="car-preview-kicker">{{ $previewSubtitle }}</div>
            @endif

            <h5 class="mb-1">{{ $previewTitle }}</h5>

            @if($previewNote)
                <p class="text-muted mb-0">{{ $previewNote }}</p>
            @endif
        </div>

        <div class="car-preview-color-pill">
            <span class="car-preview-color-dot"></span>
            <span data-car-preview-value>{{ strtoupper($previewColor) }}</span>
        </div>
    </div>

    <div class="car-preview-stage">
        <div class="car-preview-aura"></div>
        <div class="car-preview-grid"></div>
        <div class="car-preview-road"></div>

        <svg class="car-preview-svg" viewBox="0 0 600 240" role="img" aria-label="Визуализация автомобиля">
            <defs>
                <linearGradient id="{{ $previewId }}-glass" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#ffffff" stop-opacity="0.95" />
                    <stop offset="100%" stop-color="#dee2e6" stop-opacity="0.9" />
                </linearGradient>
                <linearGradient id="{{ $previewId }}-body-shade" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#868e96" stop-opacity="0.85" />
                    <stop offset="100%" stop-color="#495057" stop-opacity="0.95" />
                </linearGradient>
            </defs>

            <ellipse cx="300" cy="214" rx="235" ry="16" fill="#000" opacity="0.08" />

            <path id="{{ $previewId }}-car-body"
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
                  fill="url(#{{ $previewId }}-body-shade)" />

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
                  data-car-preview-body="primary"
                  style="fill: {{ $previewColor }};"
                  opacity="0.22" />

            <path d="M182 110
                     L210 90
                     Q235 72 262 72
                     L338 72
                     Q365 72 390 90
                     L418 110"
                  fill="none"
                  stroke="#343a40"
                  stroke-opacity="0.35"
                  stroke-width="4"
                  stroke-linecap="round" />

            <path d="M214 108
                     L236 92
                     Q248 84 262 84
                     L292 84
                     L292 120
                     L214 120
                     Z"
                  fill="url(#{{ $previewId }}-glass)" />
            <path d="M308 84
                     L338 84
                     Q352 84 364 92
                     L386 108
                     L386 120
                     L308 120
                     Z"
                  fill="url(#{{ $previewId }}-glass)" />

            <path d="M300 124 L300 188"
                  stroke="#212529"
                  stroke-opacity="0.25"
                  stroke-width="3"
                  stroke-linecap="round" />

            <path d="M520 158 Q534 164 534 174 Q534 184 520 188"
                  fill="#ffd43b"
                  opacity="0.55" />
            <path d="M80 158 Q66 164 66 174 Q66 184 80 188"
                  fill="#f8f9fa"
                  opacity="0.35" />

            <g>
                <circle cx="150" cy="196" r="34" fill="#212529" />
                <circle cx="150" cy="196" r="24" fill="#495057" />
                <circle cx="150" cy="196" r="8" fill="#adb5bd" />
                <path d="M150 172 L150 220 M126 196 L174 196 M134 180 L166 212 M166 180 L134 212"
                      stroke="#ced4da"
                      stroke-opacity="0.6"
                      stroke-width="2"
                      stroke-linecap="round" />
            </g>
            <g>
                <circle cx="450" cy="196" r="34" fill="#212529" />
                <circle cx="450" cy="196" r="24" fill="#495057" />
                <circle cx="450" cy="196" r="8" fill="#adb5bd" />
                <path d="M450 172 L450 220 M426 196 L474 196 M434 180 L466 212 M466 180 L434 212"
                      stroke="#ced4da"
                      stroke-opacity="0.6"
                      stroke-width="2"
                      stroke-linecap="round" />
            </g>

            <use href="#{{ $previewId }}-car-body"
                 fill="none"
                 stroke="#212529"
                 stroke-opacity="0.18"
                 stroke-width="3" />
        </svg>
    </div>
</div>
