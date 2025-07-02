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

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$isAdmin = ($user['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xObywatel | Dashboard</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
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
    overflow-x: hidden;
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

.generator-container {
    background: #1e1e1e;
    border-radius: 15px;
    padding: 50px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease;
}

.generator-container:hover {
    transform: translateY(-10px);
}

h3 {
    color: #1a73e8;
    font-size: 2rem;
    margin-bottom: 20px;
}

#generator-btn {
    background: linear-gradient(135deg, #1a73e8, #005cbf);
    color: white;
    padding: 15px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.2rem;
    transition: background 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 92, 191, 0.5);
    display: block;
    margin: 10px 0;
    text-align: center;
}

#generator-btn:hover {
    background: linear-gradient(135deg, #005cbf, #1a73e8);
    box-shadow: 0 6px 15px rgba(0, 92, 191, 0.7);
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

#monitoring-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s ease;
    margin-right: 15px;
}

.user-info {
    display: flex;
    align-items: center;
}

.avatar {
    font-size: 24px;
    margin-right: 5px;
    color: white;
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

@media (max-width: 768px) {
    header h1 {
        font-size: 1.5rem;
    }

    header p {
        font-size: 0.9rem;
    }

    #logout-btn, #monitoring-btn {
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 20px;
    }

    .content {
        padding: 20px;
    }

    .generator-container {
        padding: 30px;
        max-width: 90%;
    }

    h3 {
        font-size: 1.5rem;
    }

    #generator-btn {
        font-size: 1rem;
        padding: 10px 25px;
    }
}

@media (max-width: 480px) {
    header {
        padding: 15px;
        flex-direction: column;
        text-align: center;
    }

    .header-left, .header-right {
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    #logout-btn, #monitoring-btn {
        margin: 5px 0;
        width: 90%;
        padding: 12px 0;
        font-size: 1rem;
        border-radius: 25px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    header h1 {
        font-size: 1.3rem;
    }

    header p {
        font-size: 0.8rem;
    }

    .generator-container {
        padding: 20px;
        width: 90%;
        margin: 10px 0;
    }

    h3 {
        font-size: 1.2rem;
    }

    #generator-btn {
        font-size: 1rem;
        padding: 12px 0;
        width: 90%;
        margin-top: 10px;
    }
}

@media (max-width: 1024px) {
    header h1 {
        font-size: 1.6rem;
    }

    .generator-container {
        max-width: 80%;
        padding: 40px;
    }

    h3 {
        font-size: 1.8rem;
    }

    #generator-btn {
        padding: 14px 28px;
    }
}

    </style>
</head>
<script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
</script>
<body>

<header>
    <div class="header-left">
        <h1>Gale MMA | Dashboard</h1>
        <i class="fas fa-user avatar"></i>
        <span>Zalogowany jako <?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </div>
    <?php if ($isAdmin): ?>
    <div class="header-right">
        <a href="monitoring.php" id="monitoring-btn"> Monitoring <i class="fas fa-file-alt"></i></a>
    <?php endif; ?>
        <a href="logout.php" id="logout-btn"> Wyloguj się <i class="fas fa-sign-out-alt"></i></a>
    </div>
</header>

<div class="content">
    <div class="generator-container">
        <h3>Witaj <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
        <a href="generator.php" id="generator-btn">Przejdź do Generatora <i class="fas fa-cogs"></i></a>
    </div>

<?php if ($isAdmin): ?>
    <div class="generator-container" style="margin-top: 20px;">
        <h3>Zarządzaj Kontami</h3>
        <a href="add_user.php" id="generator-btn"> Dodaj Nowego Użytkownika <i class="fas fa-user-plus"></i></a>
        <a href="manage_users.php" id="generator-btn"> Zarządzanie Użytkownikami i Kluczami <i class="fas fa-cogs"></i></a>
    </div>
<?php endif; ?>
</div>

<footer>
    <p>xObywatel © 2024</p>
</footer>

</body>
</html>