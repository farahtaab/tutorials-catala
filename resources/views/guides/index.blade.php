<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorials de Reparació</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 🎨 Estilos generales */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* 📌 Diseño de las tarjetas */
        .card {
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            background: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 160px;
            object-fit: cover;
            border-bottom: 3px solid #007bff;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 15px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        .card-text {
            font-size: 0.9rem;
            color: #555;
        }

        .info-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .btn-primary {
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.3s;
            font-size: 0.85rem;
            padding: 8px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* 📌 Paginación */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-item .page-link {
            border-radius: 8px;
            color: #007bff;
            border: 1px solid #ddd;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1>🔧 Tutorials de Reparació 📖</h1>

        <!-- 📌 Grid de Tarjetas -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($guides as $guide)
                <div class="col">
                    <div class="card shadow-sm">
                        @php 
                            $image = is_array($guide->image) ? $guide->image : json_decode($guide->image, true);
                            $timeMin = $guide->time_required_min ? round($guide->time_required_min / 60) : 0;
                            $timeMax = $guide->time_required_max ? round($guide->time_required_max / 60) : 0;

                            $timeDisplay = ($timeMin == 0 && $timeMax == 0) ? "" : "⏳ $timeMin - $timeMax min";
                        @endphp
                        <img src="{{ $image['standard'] ?? 'https://via.placeholder.com/400x200' }}" class="card-img-top" alt="Imatge de la guia">

                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($guide->title, 50) }}</h6>
                            <p class="card-text">
                                <strong>📌 Categoria:</strong> {{ $guide->category }} <br>
                                <strong>⚡ Dificultat:</strong> {{ ucfirst(strtolower($guide->difficulty)) }}
                            </p>
                            <p class="info-text">
                                ✍️ <strong>{{ $guide->author_username }}</strong> <br>
                                {{ $timeDisplay }}
                            </p>

                            <a href="{{ url('/guide/'.$guide->guide_id) }}" class="btn btn-primary btn-sm mt-2">📖 Veure Guia</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 📌 Paginación Bootstrap -->
        <div class="d-flex justify-content-center">
            {{ $guides->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
