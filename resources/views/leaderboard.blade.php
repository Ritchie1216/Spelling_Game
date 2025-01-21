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
        :root {
            --primary-color: #7F7FD5;
            --secondary-color: #91EAE4;
            --accent-color: #86A8E7;
            --gold: #FFD700;
            --silver: #C0C0C0;
            --bronze: #CD7F32;
            --text-primary: #2C3E50;
            --text-secondary: #5D6D7E;
            --card-bg: rgba(255, 255, 255, 0.95);
            --shadow: rgba(0, 0, 0, 0.1);
        }

        body {
            background: linear-gradient(135deg,
                var(--primary-color) 0%,
                var(--accent-color) 50%,
                var(--secondary-color) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text-primary);
            padding: clamp(1rem, 3vw, 2rem);
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        /* 标题样式 */
        .title-section {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.8s ease-out;
        }

        .title-section h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
        }

        /* Top 3 玩家样式 */
        .top-three {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: clamp(1rem, 3vw, 2rem);
            margin-bottom: 4rem;
            padding: 0 clamp(1rem, 3vw, 2rem);
        }

        .player {
            background: var(--card-bg);
            border-radius: 20px;
            padding: clamp(1rem, 3vw, 2rem);
            text-align: center;
            box-shadow: 0 10px 30px var(--shadow);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .player:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* 前三名特殊样式 */
        .player:nth-child(1) {
            background: linear-gradient(135deg, var(--gold) 0%, #FFF5D4 100%);
            transform: scale(1.05);
        }

        .player:nth-child(2) {
            background: linear-gradient(135deg, var(--silver) 0%, #F5F5F5 100%);
        }

        .player:nth-child(3) {
            background: linear-gradient(135deg, var(--bronze) 0%, #FFE5D4 100%);
        }

        .medal {
            font-size: clamp(2.5rem, 8vw, 4rem);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .rank {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .nickname {
            font-size: clamp(1rem, 3vw, 1.5rem);
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .score {
            font-size: clamp(0.9rem, 2.5vw, 1.2rem);
            color: var(--text-secondary);
        }

        /* 表格样式 */
        .table-responsive {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            backdrop-filter: blur(10px);
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem;
            border: none;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(127, 127, 213, 0.1);
            transform: scale(1.01);
        }

        /* 返回按钮样式 */
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(127, 127, 213, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(127, 127, 213, 0.6);
        }

        /* 动画 */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .top-three {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .player:nth-child(1) {
                grid-column: span 2;
            }
        }

        @media (max-width: 480px) {
            .top-three {
                grid-template-columns: 1fr;
            }

            .player:nth-child(1) {
                grid-column: auto;
            }

            .table thead th {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .table td {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
        }

        /* 深色模式支持 */
        @media (prefers-color-scheme: dark) {
            :root {
                --text-primary: #E0E0E0;
                --text-secondary: #A0A0A0;
                --card-bg: rgba(44, 62, 80, 0.95);
            }

            .table thead th {
                background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            }

            .table tbody tr:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }
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
