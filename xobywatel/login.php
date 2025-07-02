<?php
session_start();
require 'db_connection.php';

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_regenerate_id(true);

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $ip_address = getUserIP();

    if ($_SESSION['login_attempts'] >= 5) {
        $error = "Zbyt wiele nieudanych prób logowania. Spróbuj ponownie za 10 minut.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['is_banned']) {
                $error = "Zostałeś zablokowany z powodu: " . htmlspecialchars($user['ban_reason']);
            } elseif (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_attempts'] = 0;

                file_put_contents('login_logs.txt', date('Y-m-d H:i:s') . " - Użytkownik $username z IP: $ip_address zalogował się pomyślnie.\n", FILE_APPEND);
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Nieprawidłowy login lub hasło.";
                $_SESSION['login_attempts']++;
                file_put_contents('login_logs.txt', date('Y-m-d H:i:s') . " - Nieudana próba logowania dla użytkownika $username z IP: $ip_address.\n", FILE_APPEND);
            }
        } else {
            $error = "Nieprawidłowy login lub hasło.";
            $_SESSION['login_attempts']++;
            file_put_contents('login_logs.txt', date('Y-m-d H:i:s') . " - Nieudana próba logowania dla użytkownika $username z IP: $ip_address.\n", FILE_APPEND);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xObywatel | Logowanie</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

.card {
  --main-col: #ffeba7;
  --bg-col: #2a2b38;
  --bg-field: #1f2029;

  width: 190px;
  padding: 1.9rem 1.2rem;
  text-align: center;
  background: var(--bg-col);
  border-radius: 10px;
  border: 1px solid var(--main-col);
  user-select: none;
}

.field {
  margin-top: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  padding-left: 0.5rem;
  gap: 0.5rem;
  background-color: var(--bg-field);
  border-radius: 4px;
}

.input-icon {
  width: 1em;
  color: var(--main-col);
  fill: var(--main-col);
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.input-field {
  background: transparent;
  border: none;
  outline: none;
  width: 100%;
  color: var(--main-col);
  padding: 0.5em 1em 0.5em 0;
  caret-color: var(--main-col);
}

.filed:has(.input-field:valid) {
  border: 1px solid var(--main-col);
}

.title {
  margin-bottom: 1rem;
  font-size: 1.5em;
  font-weight: 500;
  color: var(--main-col);
  text-shadow: 1px 1px 20px var(--main-col);
  text-transform: uppercase;
}

.btn {
  margin: 1rem;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  font-size: 0.8em;
  text-transform: uppercase;
  padding: 0.6em 1.2em;
  background-color: var(--main-col);
  color: var(--bg-col);
  box-shadow: 0 8px 24px 0 rgb(255 235 167 / 20%);
  transition: all 0.3s ease-in-out;
  cursor: pointer;
}

.btn-link {
  color: #f5f5f5;
  display: block;
  font-size: 0.75em;
  transition: color 0.3s ease-out;
}

.field input:focus::placeholder {
  opacity: 0;
  transition: opacity 0.3s;
}

.btn:hover {
  background-color: var(--bg-field);
  color: var(--main-col);
  box-shadow: 0 8px 24px 0 rgb(16 39 112 / 20%);
}

.btn-link:hover {
  color: var(--main-col);
  text-decoration: underline;
}

    </style>
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
</head>
<body>

<div class="card">
  <h4 class="title">Zaloguj się!</h4>

  <?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

  <form method="POST" action="">
    <label class="field" for="logemail">
      <span class="input-icon">@</span>
      <input  class="input-field" type="text" name="username" placeholder="Login" required>
    </label>
    <label class="field" for="logpass">
      <svg
        class="input-icon"
        viewBox="0 0 500 500"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M80 192V144C80 64.47 144.5 0 224 0C303.5 0 368 64.47 368 144V192H384C419.3 192 448 220.7 448 256V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V256C0 220.7 28.65 192 64 192H80zM144 192H304V144C304 99.82 268.2 64 224 64C179.8 64 144 99.82 144 144V192z"
        ></path>
      </svg>

      <input class="input-field" type="password" name="password" placeholder="Hasło" required>
    </label>
        <input class="btn" type="submit" value="Zaloguj się">
  </form>
</div>

</body>
</html>
