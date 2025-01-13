<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spelling Mini Game with Background Removal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: #ffffff;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-primary, .btn-success {
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success:hover {
            background-color: #3a7d44;
        }

        #gameArea {
            animation: fadeIn 1s ease-in-out;
        }

        #word {
            font-weight: bold;
            color: #333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            display: none;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .logos i {
            margin: 0 15px;
            font-size: 2.5rem; /* 调整图标大小 */
        }

        h2 {
            color: #0056b3; /* 替换为你需要的颜色 */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4">
            <form id="nicknameForm" method="POST" class="mb-4 text-center">
                @csrf
                <h2 class="mb-3">Spelling Mini Game</h2>
                <p class="text-muted">Enter your name to start playing!</p>
                <div class="mb-3">
                <label for="nickname" class="form-label" style="color: #ff9800;">Enter Your Name:</label>

                    <input type="text" id="nickname" name="nickname" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Start Game</button>
                <a href="{{ route('leaderboard') }}" class="btn btn-link text-decoration-none">View Leaderboard</a>
            </form>

            <!-- Bootstrap Icons as Logos -->
            <div class="logos text-center mb-4">
                <i class="bi bi-trophy fs-1 text-warning"></i>
                <i class="bi bi-star fs-1 text-primary"></i>
                <i class="bi bi-cup fs-1 text-success"></i>
            </div>

            <div id="gameArea" style="display: none;" class="p-4 text-center">
                <p id="word" class="fs-4 text-dark"></p>
                <div class="mb-3">
                    <input type="text" id="answer" class="form-control" placeholder="Your answer">
                </div>
                <button id="submitAnswer" class="btn btn-success w-100">Submit Answer</button>
                <div class="mt-4">
                    <p>Time left: <span id="timeLeft" class="fw-bold">60</span> seconds</p>
                    <p>Score: <span id="score" class="fw-bold">0</span></p>
                </div>
                <div id="loader"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const words = ["apple", "banana", "cherry", "grape", "orange", "lemon", "strawberry", "peach", "melon", "kiwi"];
        let userId, score = 0, timer = 60, currentWordIndex = 0, originalWord = '';

        function shuffleWords() {
            for (let i = words.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [words[i], words[j]] = [words[j], words[i]];
            }
        }

        function maskWord(word) {
            const letters = word.split('');
            let masked = letters.map(() => '_');

            const indicesToReveal = new Set();
            while (indicesToReveal.size < Math.min(3, letters.length)) {
                indicesToReveal.add(Math.floor(Math.random() * letters.length));
            }

            indicesToReveal.forEach((index) => {
                masked[index] = letters[index];
            });

            return masked.join('');
        }

        document.getElementById('nicknameForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const nickname = document.getElementById('nickname').value;

            const response = await fetch('/start-game', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ nickname })
            });

            const data = await response.json();
            userId = data.player.id;

            document.getElementById('nicknameForm').style.display = 'none';
            document.getElementById('gameArea').style.display = 'block';

            shuffleWords();
            startGame();
        });

        async function startGame() {
            const interval = setInterval(() => {
                timer--;
                document.getElementById('timeLeft').textContent = timer;

                if (timer <= 0) {
                    clearInterval(interval);
                    endGame();
                }
            }, 1000);

            showNextWord();
            document.getElementById('submitAnswer').addEventListener('click', checkAnswer);
        }

        function showNextWord() {
            if (currentWordIndex < words.length) {
                originalWord = words[currentWordIndex];
                document.getElementById('word').textContent = maskWord(originalWord);
            }
        }

        function checkAnswer() {
            const answer = document.getElementById('answer').value.toLowerCase().trim();
            if (answer === originalWord) {
                score++;
                document.getElementById('score').textContent = score;
            }
            currentWordIndex++;
            if (currentWordIndex < words.length) {
                showNextWord();
            }
            document.getElementById('answer').value = '';
        }

        function endGame() {
            document.getElementById('gameArea').style.display = 'none';
            document.getElementById('nicknameForm').style.display = 'block';
            alert(`Game over! Your score is ${score}.`);
        }
    </script>
</body>
</html>
