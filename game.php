<?php
session_start();

// Initialize or reset the game
if (!isset($_SESSION['snake'])) {
    $_SESSION['snake'] = [
        ['x' => 200, 'y' => 200],  // Snake's starting position
    ];
    $_SESSION['food'] = [
        'x' => rand(0, 19) * 20,   // Random food position
        'y' => rand(0, 19) * 20,
    ];
    $_SESSION['dx'] = 20;  // Initial direction (moving right)
    $_SESSION['dy'] = 0;
}

// Function to move the snake
function moveSnake() {
    $head = [
        'x' => $_SESSION['snake'][0]['x'] + $_SESSION['dx'],
        'y' => $_SESSION['snake'][0]['y'] + $_SESSION['dy'],
    ];

    // Add the new head to the snake array
    array_unshift($_SESSION['snake'], $head);

    // Check if the snake has eaten the food
    if ($_SESSION['snake'][0]['x'] === $_SESSION['food']['x'] && $_SESSION['snake'][0]['y'] === $_SESSION['food']['y']) {
        // Generate new food position
        $_SESSION['food'] = [
            'x' => rand(0, 19) * 20,
            'y' => rand(0, 19) * 20,
        ];
    } else {
        // Remove the last part of the snake if no food was eaten
        array_pop($_SESSION['snake']);
    }

    // Check for collisions with walls
    if ($_SESSION['snake'][0]['x'] < 0 || $_SESSION['snake'][0]['x'] >= 400 || $_SESSION['snake'][0]['y'] < 0 || $_SESSION['snake'][0]['y'] >= 400) {
        session_destroy();
        die('Game Over');
    }

    // Check for collisions with itself
    foreach (array_slice($_SESSION['snake'], 1) as $part) {
        if ($_SESSION['snake'][0]['x'] === $part['x'] && $_SESSION['snake'][0]['y'] === $part['y']) {
            session_destroy();
            die('Game Over');
        }
    }
}

// Function to change the direction
function changeDirection($direction) {
    if ($direction === 'left' && $_SESSION['dx'] !== 20) {
        $_SESSION['dx'] = -20;
        $_SESSION['dy'] = 0;
    } elseif ($direction === 'up' && $_SESSION['dy'] !== 20) {
        $_SESSION['dx'] = 0;
        $_SESSION['dy'] = -20;
    } elseif ($direction === 'right' && $_SESSION['dx'] !== -20) {
        $_SESSION['dx'] = 20;
        $_SESSION['dy'] = 0;
    } elseif ($direction === 'down' && $_SESSION['dy'] !== -20) {
        $_SESSION['dx'] = 0;
        $_SESSION['dy'] = 20;
    }
}

// Handle direction change on button click
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direction = $_POST['direction'];
    changeDirection($direction);
    moveSnake();  // Immediately move the snake after changing direction
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Game</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .game-container {
            text-align: center;
        }
        .grid {
            position: relative;
            width: 400px;
            height: 400px;
            background-color: #000;
        }
        .snake, .food {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: green;
        }
        .food {
            background-color: red;
        }
        .controls {
            margin-top: 20px;
        }
        button {
            padding: 10px;
            font-size: 18px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="grid">
            <?php foreach ($_SESSION['snake'] as $part): ?>
                <div class="snake" style="left: <?= $part['x'] ?>px; top: <?= $part['y'] ?>px;"></div>
            <?php endforeach; ?>
            <div class="food" style="left: <?= $_SESSION['food']['x'] ?>px; top: <?= $_SESSION['food']['y'] ?>px;"></div>
        </div>
        <div class="controls">
            <form action="app.php" method="post" style="display:inline;">
                <input type="hidden" name="direction" value="left">
                <button type="submit">Left</button>
            </form>
            <form action="app.php" method="post" style="display:inline;">
                <input type="hidden" name="direction" value="up">
                <button type="submit">Up</button>
            </form>
            <form action="app.php" method="post" style="display:inline;">
                <input type="hidden" name="direction" value="down">
                <button type="submit">Down</button>
            </form>
            <form action="app.php" method="post" style="display:inline;">
                <input type="hidden" name="direction" value="right">
                <button type="submit">Right</button>
            </form>
        </div>
    </div>
</body>
</html>
