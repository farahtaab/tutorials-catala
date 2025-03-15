<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $guide->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* 🎨 Estilos Generales */
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 1100px;
    }

    h1,
    h3 {
        color: #343a40;
    }

    hr {
        border-top: 2px solid #007bff;
    }

    /* 🛠️ Sección de herramientas */
    .tool-card {
        text-align: center;
        border-radius: 10px;
        transition: transform 0.2s ease-in-out;
    }

    .tool-card:hover {
        transform: scale(1.05);
    }

    .tool-image {
        width: 100px;
        height: auto;
        margin-bottom: 10px;
    }

    /* 📝 Diseño de pasos */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
    }

    .step-card {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        background-color: white;
        padding: 15px;
        transition: transform 0.2s ease-in-out;
    }

    .step-card:hover {
        transform: scale(1.03);
    }

    .step-number {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 6px 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }

    .step-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    /* 💬 Diseño de comentarios */
    .comment-box {
        background-color: #f1f1f1;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 10px;
    }

    .comment-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-3 text-center">{{ $guide->title }}</h1>

        <div class="text-center">
            <strong>Categoría:</strong> {{ $guide->category }} |
            <strong>Subjecte:</strong> {{ $guide->subject }} |
            <strong>Dificultat:</strong> {{ ucfirst(strtolower($guide->difficulty)) }}
        </div>

        @if ($guide->time_required_min > 0 && $guide->time_required_max > 0)
        <p class="text-center mt-2">
            <strong>⏳ Temps estimat:</strong>
            {{ round($guide->time_required_min / 60) }} - {{ round($guide->time_required_max / 60) }} min
        </p>
        @endif


        <hr>

        <h3>🔎 Introducció</h3>
        <p>{{ $guide->introduction_raw }}</p>

        <hr>

        <h3>🔧 Eines necessàries</h3>
        <div class="row">
            @if (!empty($guide->tools))
            @php
            $tools = is_string($guide->tools) ? json_decode($guide->tools, true) : $guide->tools;
            @endphp
            @foreach ($tools as $tool)
            <div class="col-md-4 mb-3">
                <div class="card tool-card p-3">
                    <img src="{{ $tool['thumbnail'] ?? 'https://via.placeholder.com/100' }}" class="tool-image"
                        alt="Eina">
                    <div class="card-body">
                        <h6 class="card-title">{{ $tool['text'] }}</h6>
                        <a href="{{ $tool['url'] }}" class="btn btn-sm btn-outline-primary" target="_blank">🔗 Veure</a>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <p class="text-muted">No hi ha eines específiques per aquesta guia.</p>
            @endif
        </div>

        <hr>

        <h3>📌 Passos detallats</h3>
        @php
        $steps = is_string($guide->steps) ? json_decode($guide->steps, true) : $guide->steps;
        @endphp

        @if (!empty($steps))
        <div class="grid-container">
            @foreach ($steps as $index => $step)
            <div class="step-card">
                <h5><span class="step-number">{{ $index + 1 }}</span> {{ $step['title'] ?? 'Pas sense títol' }}</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($step['lines'] as $line)
                    <li class="list-group-item">{{ $line['text_raw'] }}</li>
                    @endforeach
                </ul>

                @if (isset($step['media']['data']) && is_array($step['media']['data']))
                <div class="mt-3">
                    @foreach ($step['media']['data'] as $image)
                    <img src="{{ $image['standard'] ?? '' }}" class="step-image mb-2" alt="Imatge del pas">
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">No hi ha passos disponibles.</p>
        @endif

        <hr>

        <h3>💬 Comentaris</h3>
        @php
        $comments = is_string($guide->comments) ? json_decode($guide->comments, true) : $guide->comments;
        @endphp

        @if (!empty($comments))
        @foreach ($comments as $comment)
        <div class="d-flex align-items-start comment-box">
            <img src="{{ $comment['author']['image']['thumbnail'] ?? 'https://via.placeholder.com/50' }}"
                class="comment-avatar">
            <div>
                <strong>{{ $comment['author']['username'] }}</strong>
                <p class="mb-1">{{ $comment['text_raw'] }}</p>
                <small class="text-muted">📅 {{ date('d-m-Y', $comment['date']) }}</small>
            </div>
        </div>
        @endforeach
        @else
        <p class="text-muted">No hi ha comentaris en aquesta guia.</p>
        @endif

        <a href="/" class="btn btn-primary mt-4 d-block mx-auto">🔙 Tornar a les guies</a>
    </div>
</body>

</html>