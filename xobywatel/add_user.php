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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = htmlspecialchars(trim($_POST['username']));
    $new_password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));

    if (empty($new_username) || empty($new_password)) {
        $error = "Wszystkie pola są wymagane.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$new_username]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $error = "Użytkownik o podanym loginie już istnieje.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$new_username, $hashed_password, $role]);

            $success = "Użytkownik został dodany pomyślnie!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xObywatel | Dodaj Użytkownika</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        header p {
            font-size: 1rem;
            font-weight: 400;
            margin-top: 5px;
        }

        #logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        #logout-btn:hover {
            background-color: #c0392b;
        }

        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 40px;
            width: 100%;
            flex-direction: column;
            text-align: center;
        }

        .form-container {
            background: #1e1e1e;
            border-radius: 15px;
            padding: 50px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-10px);
        }

        h3 {
            color: #1a73e8;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #f5f5f5;
        }

        input, select {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: none;
            font-size: 1rem;
            width: 100%;
        }

        #add-user-btn {
            background: linear-gradient(135deg, #1a73e8, #005cbf);
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.2rem;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 92, 191, 0.5);
        }

        #add-user-btn:hover {
            background: linear-gradient(135deg, #005cbf, #1a73e8);
            box-shadow: 0 6px 15px rgba(0, 92, 191, 0.7);
        }

        .error, .success {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            background-color: #e74c3c;
            color: white;
        }

        .success {
            background-color: #2ecc71;
            color: white;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px;
            }

            h3 {
                font-size: 1.5rem;
            }

            #add-user-btn {
                font-size: 1rem;
                padding: 10px 25px;
            }
        }

        footer {
            background-color: #1a1a1a;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            position: relative;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        footer a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 500;
        }

        footer a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

<header>
    <div class="header-left">
        <h1>xObywatel | Dodaj Użytkownika</h1>
        <p>Zalogowany jako <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>
    <div class="header-right">
        <a href="logout.php" id="logout-btn">Wyloguj się <i class="fas fa-sign-out-alt"></i></a>
    </div>
</header>

<div class="content">
    <div class="form-container">
        <h3>Dodaj Nowego Użytkownika</h3>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Nazwa Użytkownika</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Hasło</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Rola</label>
            <select name="role" id="role" required>
                <option value="user">Użytkownik</option>
                <option value="admin">Administrator</option>
            </select>

            <button type="submit" id="add-user-btn">Dodaj Użytkownika</button>
        </form>
    </div>
</div>

<footer>
    <p>xObywatel © 2024</p>
</footer>

</body>
</html>
