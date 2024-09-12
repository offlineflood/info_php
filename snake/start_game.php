<?php
// Formdan göndərilən məlumatları alırıq
$width = $_POST['width'];
$height = $_POST['height'];
$snakeAppearance = $_POST['snakeAppearance'];
$walls = $_POST['walls'];
$snakeColor = $_POST['snakeColor'];
$backgroundColor = $_POST['backgroundColor']; // Fon rəngi əlavə edin
?>
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Oyunu</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            width: 100%;
        }
        #gameCanvas {
            border: 1px solid #ddd;
            background-color: #000;
            display: block;
            margin: 0 auto;
        }
        .game-info {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .btn-secondary {
            background-color: #007bff;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #0056b3;
        }
        .btn-control {
            width: 100px;
            height: 100px;
            margin: 10px;
            font-size: 24px;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #up {
            background-color: #dc3545;
        }
        #down {
            background-color: #17a2b8;
        }
        #left {
            background-color: #ffc107;
        }
        #right {
            background-color: #28a745;
        }
        #up:hover {
            background-color: #c82333;
        }
        #down:hover {
            background-color: #138496;
        }
        #left:hover {
            background-color: #e0a800;
        }
        #right:hover {
            background-color: #218838;
        }
        .d-none {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Snake Oyunu</h1>
        <canvas id="gameCanvas" width="<?php echo htmlspecialchars($width); ?>" height="<?php echo htmlspecialchars($height); ?>"></canvas>
        
        <div class="game-info">
            <p id="score">Xal: 0</p>
            <p id="timer">Vaxt: 0 saniyə</p>
        </div>

        <div id="gameOverMessage" class="d-none text-center">
            <h2>Oyun Bitdi!</h2>
            <p>Xalınız: <span id="finalScore"></span></p>
            <button id="restartButton" class="btn btn-secondary">Oyuna davam et</button>
        </div>

        <div class="text-center mt-3">
            <button id="up" class="btn-control">↑</button>
            <br>
            <button id="left" class="btn-control">←</button>
            <button id="right" class="btn-control">→</button>
            <br>
            <button id="down" class="btn-control">↓</button>
        </div>
        
        <script>
            var canvas = document.getElementById('gameCanvas');
            var ctx = canvas.getContext('2d');
            var width = canvas.width;
            var height = canvas.height;
            var snakeColor = '<?php echo htmlspecialchars($snakeColor); ?>';
            var snakeAppearance = '<?php echo htmlspecialchars($snakeAppearance); ?>';
            var walls = '<?php echo htmlspecialchars($walls); ?>';
            var backgroundColor = '<?php echo htmlspecialchars($backgroundColor); ?>'; // Fon rəngini əldə edin
            var snakeSize = 20;
            var snake = [{x: width / 2, y: height / 2}];
            var direction = 'RIGHT';
            var food = generateFood();
            var score = 0;
            var gameInterval;
            var startTime = Date.now();
            var gameOver = false;

            // Fon rəngini təyin edin
            canvas.style.backgroundColor = backgroundColor;

            function startGame() {
                document.addEventListener('keydown', changeDirection);
                gameInterval = setInterval(updateGame, 100);
            }

            function generateFood() {
                return {x: Math.floor(Math.random() * (width / snakeSize)) * snakeSize, y: Math.floor(Math.random() * (height / snakeSize)) * snakeSize};
            }

            function changeDirection(event) {
                if (gameOver) return;
                switch (event.keyCode) {
                    case 37: if (direction !== 'RIGHT') direction = 'LEFT'; break;
                    case 38: if (direction !== 'DOWN') direction = 'UP'; break;
                    case 39: if (direction !== 'LEFT') direction = 'RIGHT'; break;
                    case 40: if (direction !== 'UP') direction = 'DOWN'; break;
                }
            }

            function updateGame() {
                if (gameOver) return;

                ctx.clearRect(0, 0, width, height);
                moveSnake();
                drawSnake();
                drawFood();
                checkCollision();
                checkFood();
                updateTimer();
            }

            function moveSnake() {
                var head = { ...snake[0] };

                switch (direction) {
                    case 'LEFT': head.x -= snakeSize; break;
                    case 'UP': head.y -= snakeSize; break;
                    case 'RIGHT': head.x += snakeSize; break;
                    case 'DOWN': head.y += snakeSize; break;
                }

                if (walls === 'no') {
                    if (head.x < 0) head.x = width - snakeSize;
                    if (head.x >= width) head.x = 0;
                    if (head.y < 0) head.y = height - snakeSize;
                    if (head.y >= height) head.y = 0;
                } else {
                    if (head.x < 0 || head.x >= width || head.y < 0 || head.y >= height) {
                        endGame();
                        return;
                    }
                }

                snake.unshift(head);

                if (head.x === food.x && head.y === food.y) {
                    food = generateFood();
                    score += 10;
                    document.getElementById('score').innerText = 'Xal: ' + score; // Xalı yeniləyin
                } else {
                    snake.pop();
                }
            }

            function drawSnake() {
                ctx.fillStyle = snakeColor;
                for (var i = 0; i < snake.length; i++) {
                    ctx.fillRect(snake[i].x, snake[i].y, snakeSize, snakeSize);
                }
            }

            function drawFood() {
                ctx.fillStyle = '#f00';
                ctx.fillRect(food.x, food.y, snakeSize, snakeSize);
            }

            function checkCollision() {
                var head = snake[0];
                for (var i = 1; i < snake.length; i++) {
                    if (head.x === snake[i].x && head.y === snake[i].y) {
                        endGame();
                    }
                }
            }

            function checkFood() {
                var head = snake[0];
                if (head.x === food.x && head.y === food.y) {
                    food = generateFood();
                    score += 10; // Xalı artırın
                    document.getElementById('score').innerText = 'Xal: ' + score; // Xalı yeniləyin
                }
            }

            function updateTimer() {
                var elapsed = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('timer').innerText = 'Vaxt: ' + elapsed + ' saniyə';
            }

            function endGame() {
                clearInterval(gameInterval);
                gameOver = true;
                document.getElementById('score').innerText = 'Xal: ' + score;
                document.getElementById('finalScore').innerText = score;
                document.getElementById('gameOverMessage').classList.remove('d-none');
            }

            document.getElementById('restartButton').addEventListener('click', function() {
                location.reload();
            });

            // Add event listeners to control buttons
            document.getElementById('up').addEventListener('click', function() {
                if (direction !== 'DOWN') direction = 'UP';
            });
            document.getElementById('down').addEventListener('click', function() {
                if (direction !== 'UP') direction = 'DOWN';
            });
            document.getElementById('left').addEventListener('click', function() {
                if (direction !== 'RIGHT') direction = 'LEFT';
            });
            document.getElementById('right').addEventListener('click', function() {
                if (direction !== 'LEFT') direction = 'RIGHT';
            });

            startGame();
        </script>
    </div>
</body>
</html>
