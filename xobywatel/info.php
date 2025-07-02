<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xObywatel | TOS</title>
    <link rel="stylesheet" href="./xObywatel_info/all.min.css">
    <link rel="stylesheet" href="./xObywatel_info/generator.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            padding: 20px;
            margin: 0;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #1e1e1e;
            border-radius: 8px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .content h2 {
            color: #007BFF;
        }

        .content p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background: #333;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #121212;
            transition: opacity 0.75s, visibility 0.75s;
        }

        .loader--hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader::after {
            content: "";
            width: 75px;
            height: 75px;
            border: 15px solid #dddddd;
            border-top-color: #1e1e1e;
            border-radius: 50%;
            animation: loading 0.75s ease infinite;
        }

        @keyframes loading {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }
    </style>
    <script>
        window.addEventListener("load", () => {
            const loader = document.querySelector(".loader");
            loader.classList.add("loader--hidden");
            loader.addEventListener("transitionend", () => {
                document.body.removeChild(loader);
            });
        });
    </script>
</head>
<body>
    <div class="loader"></div>

    <header>
        <h1>xObywatel | Informacje i Regulamin <i class="fas fa-info-circle"></i></h1>
        <a href="dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Powrót</a>
    </header>

    <div class="content">
        <h2>O nas</h2>
        <p>xObywatel został stworzony, aby umożliwić użytkownikom tworzenie przykładowych stron imitujących aplikacje mObywatel 2.0.</p>

        <h2>Jak używać generatora?</h2>
        <p>Wypełnij wszystkie pola danymi, a następnie wygeneruj stronę i podążaj zgodnie z poniżej podanymi instrukcjami.</p>

        <b style="color:green">Android <i class="fab fa-android" style="color: #0ecb01;"></i></b>
        <ul>
            <li>Uruchom stronę w Chrome</li>
            <li>Przejdź do wcześniej wygenerowanej strony, do ekranu "Zaloguj się"</li>
            <li>Naciśnij trzy kropki w prawym górnym rogu</li>
            <li>Naciśnij "Dodaj do ekranu głównego"</li>
            <li>Wpisz nazwę</li>
            <li>Naciśnij "Dodaj"</li>
        </ul>

        <b style="color:white">iOS <i class="fab fa-apple" style="color: #ffffff;"></i></b>
        <ul>
            <li>Uruchom stronę w Safari</li>
            <li>Przejdź do wcześniej wygenerowanej strony, do ekranu "Zaloguj się"</li>
            <li>Naciśnij strzałkę w górę znajdującą się na dolnym pasku po środku</li>
            <li>Naciśnij "Dodaj do ekranu głównego"</li>
            <li>Wpisz nazwę</li>
            <li>Naciśnij "Dodaj"</li>
        </ul>

        <h2>Uwaga</h2>
        <p>xObywatel jest przeznaczony wyłącznie do celów demonstracyjnych i edukacyjnych.</p>
    </div>
</body>
</html>
