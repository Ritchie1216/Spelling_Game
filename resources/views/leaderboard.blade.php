<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leaderboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e8eaf6, #c5cae9); /* Gradient background */
            font-family: 'Arial', sans-serif;
        }

        /* Highlight top 3 players */
        .top-three {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .top-three .player {
            text-align: center;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        /* Hover effects for top 3 players */
        .top-three .player:hover {
            transform: scale(1.1); /* Make the card bigger on hover */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        }

        .top-three .player:first-child {
            background-color: #ffd700; /* Gold background for 1st place */
        }

        .top-three .player:nth-child(2) {
            background-color: #c0c0c0; /* Silver background for 2nd place */
        }

        .top-three .player:nth-child(3) {
            background-color: #cd7f32; /* Bronze background for 3rd place */
        }

        /* General styles for ranking */
        .player .rank {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .player .nickname {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .player .score {
            font-size: 1rem;
            color: #555;
        }

        .player .medal {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        /* Table styles */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table-striped tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05); /* Hover effect */
        }
    </style>
</head>
<body>
    <!-- Container -->
    <div class="container my-5 text-center">
        <!-- Title -->
        <i class="bi bi-trophy-fill text-warning display-4 mb-3"></i>
        <h1 class="mb-4 fw-bold text-primary">Leaderboard</h1>

        <!-- Top 3 Players -->
        <div class="top-three">
            @if (count($scores) > 0)
                <div class="player">
                    <div class="medal text-warning">
                        <i class="bi bi-trophy-fill"></i> <!-- Gold Medal Icon -->
                    </div>
                    <div class="rank">1st</div>
                    <div class="nickname">{{ $scores[0]->player->nickname }}</div>
                    <div class="score">Score: {{ $scores[0]->score }}</div>
                </div>
            @endif
            @if (count($scores) > 1)
                <div class="player">
                    <div class="medal text-secondary">
                        <i class="bi bi-trophy-fill"></i> <!-- Silver Medal Icon -->
                    </div>
                    <div class="rank">2nd</div>
                    <div class="nickname">{{ $scores[1]->player->nickname }}</div>
                    <div class="score">Score: {{ $scores[1]->score }}</div>
                </div>
            @endif
            @if (count($scores) > 2)
                <div class="player">
                    <div class="medal text-muted">
                        <i class="bi bi-trophy-fill"></i> <!-- Bronze Medal Icon -->
                    </div>
                    <div class="rank">3rd</div>
                    <div class="nickname">{{ $scores[2]->player->nickname }}</div>
                    <div class="score">Score: {{ $scores[2]->score }}</div>
                </div>
            @endif
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Nickname</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scores as $index => $score)
                        @if ($index > 2) <!-- Skip top 3 players -->
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $score->player->nickname }}</td>
                            <td>{{ $score->score }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Back to Game Button -->
        <div class="text-center mt-4">
            <a href="{{ route('game') }}" class="btn btn-primary px-4 py-2">
                <i class="bi bi-controller"></i> Back to Game
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer text-center mt-4">
        <p>Created with ❤️ by Your Team</p>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
