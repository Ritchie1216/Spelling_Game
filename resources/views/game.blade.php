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
        :root {
            --primary-color: #7F7FD5;   /* 紫色调 */
            --secondary-color: #91EAE4;  /* 青色调 */
            --accent-color: #86A8E7;     /* 蓝色调 */
            --text-color: #2C3E50;
            --white: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.95);
            --shadow-color: rgba(127, 127, 213, 0.2);
        }

        body {
            /* 三色渐变背景 */
            background: linear-gradient(135deg, 
                var(--primary-color) 0%, 
                var(--accent-color) 50%, 
                var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--white);
            padding: 1rem;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: auto;
        }

        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-color);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: clamp(1rem, 5vw, 2rem);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        h2 {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: none;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.25);
        }

        .btn {
            border-radius: 12px;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border: none;
            box-shadow: 0 4px 15px rgba(127, 127, 213, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.6);
        }

        .btn-success {
            background: linear-gradient(45deg, #3CA55C, #B5AC49);
            border: none;
            box-shadow: 0 4px 15px rgba(60, 165, 92, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
        }

        #gameArea {
            animation: slideUp 0.5s ease-out;
        }

        #word {
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            font-weight: 700;
            color: var(--text-color);
            margin: 1.5rem 0;
            letter-spacing: 3px;
        }

        .progress {
            height: clamp(15px, 4vw, 20px);
            background-color: #e9ecef;
            border-radius: 10px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-bar {
            transition: all 1s linear;
            border-radius: 10px;
            position: relative;
            height: 100%;
        }

        /* 进度条颜色类 */
        .progress-bar-safe {
            background: linear-gradient(45deg, #34D399, #3B82F6);
        }

        .progress-bar-warning {
            background: linear-gradient(45deg, #FBBF24, #F59E0B);
        }

        .progress-bar-danger {
            background: linear-gradient(45deg, #EF4444, #DC2626);
        }

        /* 进度条动画效果 */
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.2) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.2) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
            animation: progressAnimation 1s linear infinite;
        }

        @keyframes progressAnimation {
            0% {
                background-position: 1rem 0;
            }
            100% {
                background-position: 0 0;
            }
        }

        .logos {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
        }

        .logos i {
            font-size: clamp(2rem, 6vw, 3rem);
            transition: transform 0.3s ease;
        }

        .logos i:hover {
            transform: scale(1.2);
        }

        @keyframes slideUp {
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
            .container {
                padding: 0 1rem;
            }
            
            .card {
                margin: 1rem;
            }
        }

        @media (max-width: 480px) {
            .logos {
                gap: 1rem;
            }

            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
        }

        /* 深色模式支持 */
        @media (prefers-color-scheme: dark) {
            :root {
                --text-color: #E0E0E0;
                --card-bg: rgba(44, 62, 80, 0.95);
            }

            .form-control {
                background-color: rgba(255, 255, 255, 0.05);
                border-color: rgba(255, 255, 255, 0.1);
                color: var(--white);
            }

            .form-control:focus {
                background-color: rgba(255, 255, 255, 0.1);
            }

            .text-muted {
                color: #a0a0a0 !important;
            }
        }

        /* 图标颜色调整 */
        .bi-trophy {
            color: #FFD700 !important; /* 金色 */
        }

        .bi-star {
            color: var(--primary-color) !important;
        }

        .bi-cup {
            color: var(--accent-color) !important;
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
                    <p class="mb-2">Time left: <span id="timeLeft" class="fw-bold">60</span>s</p>
                    <div class="progress">
                        <div id="timeProgress" class="progress-bar progress-bar-safe" 
                            role="progressbar" 
                            style="width: 100%;" 
                            aria-valuenow="100" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
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
            const totalTime = 60;
            timer = totalTime;
            const progressBar = document.getElementById('timeProgress');
            const timeLeftSpan = document.getElementById('timeLeft');

            const interval = setInterval(() => {
                timer--;
                const percentage = (timer / totalTime) * 100;
                
                // 更新进度条宽度
                progressBar.style.width = percentage + '%';
                timeLeftSpan.textContent = timer;

                // 根据剩余时间更新进度条颜色
                progressBar.classList.remove('progress-bar-safe', 'progress-bar-warning', 'progress-bar-danger');
                
                if (timer <= 10) {
                    progressBar.classList.add('progress-bar-danger');
                } else if (timer <= 30) {
                    progressBar.classList.add('progress-bar-warning');
                } else {
                    progressBar.classList.add('progress-bar-safe');
                }

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
            
            // 如果当前单词索引达到数组末尾，重新洗牌
            if (currentWordIndex >= words.length) {
                currentWordIndex = 0;
                shuffleWords();
            }
            
            showNextWord();
            document.getElementById('answer').value = '';
        }

        function endGame() {
            const submitButton = document.getElementById('submitAnswer');
            const answerInput = document.getElementById('answer');
            
            // 禁用输入和提交按钮
            submitButton.disabled = true;
            answerInput.disabled = true;

            // 保存分数到服务器
            fetch('/submit_score', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    player_id: userId,
                    score: score 
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(`游戏结束！你的得分是 ${score} 分。`);
                setTimeout(() => {
                    document.getElementById('gameArea').style.display = 'none';
                    document.getElementById('nicknameForm').style.display = 'block';
                    // 重置游戏状态
                    score = 0;
                    timer = 60;
                    currentWordIndex = 0;
                    document.getElementById('score').textContent = '0';
                    document.getElementById('timeLeft').textContent = '60';
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('保存分数时出错，请稍后再试');
            });
        }
    </script>
</body>
</html>
