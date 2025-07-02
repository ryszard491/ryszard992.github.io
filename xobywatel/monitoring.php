<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();

if ($user['role'] !== 'admin') {
    echo "Nie masz uprawnień do tej strony.";
    exit();
}

$logFile = 'login_logs.txt';

if (!file_exists($logFile)) {
    die("Plik z logami nie istnieje.");
}

$logs = file_get_contents($logFile);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>xObywatel | Monitoring Logów</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #0d0d0d;
            color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #1a1a1a;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        header h1 {
            color: white;
            font-size: 1.8rem;
        }

        #log-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            max-height: 500px;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #f5f5f5;
            margin: 0;
            font-family: monospace;
        }

        .button {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: inline-block;
            font-size: 1rem;
        }

        .button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        footer {
            margin-top: auto;
            padding: 20px;
            background-color: #1a1a1a;
            text-align: center;
            color: white;
        }

        footer p {
            margin: 0;
        }

        ::-webkit-scrollbar {
            width: 12px;
            background: #1e1e1e;
        }

        ::-webkit-scrollbar-thumb {
            background: #3498db;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2980b9;
        }

        ::-webkit-scrollbar-thumb:active {
            background: #1a73e8;
        }

        scrollbar-width: thin;
        scrollbar-color: #3498db #1e1e1e;
    </style>

    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
</head>
<body>

<header>
    <h1>Monitoring Logów</h1>
    <a href="dashboard.php" class="button">Powrót do Dashboardu</a>
</header>

<div id="log-container">
    <h3>Zawartość pliku logów:</h3>
    <pre><?php echo htmlspecialchars($logs); ?></pre>
</div>

</body>
</html>
