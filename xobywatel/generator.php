<?php
session_start(); // Upewnij się, że sesja jest uruchomiona

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin'])) {
    // Sesja nie jest ustawiona, przekieruj do logowania
    header("Location: login.php");
    exit();
}

// Dodaj dodatkowy log, aby zobaczyć, co jest w sesji
if ($_SESSION['loggedin'] !== true) {
    // Sesja nie wskazuje, że użytkownik jest zalogowany, przekieruj do logowania
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xObywatel | Generator</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Ogólny styl strony */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Nagłówek */
        header {
            width: 100%;
            padding: 20px;
            background-color: #1a1a1a;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        header h1 {
            font-size: 2rem;
            color: #1a73e8;
            font-weight: 600;
        }

        header a {
            color: #f5f5f5;
            font-weight: 500;
            text-decoration: none;
            background-color: #1a73e8;
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        header a:hover {
            background-color: #005cbf;
        }

        /* Loader styl */
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

        /* Zawartość */
        .content {
            margin-top: 50px;
            background: #1e1e1e;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            width: 90%;
            max-width: 600px;
        }

        .content form {
            display: flex;
            flex-direction: column;
        }

        .content form label {
            margin: 10px 0 5px;
            font-size: 1rem;
            color: #f5f5f5;
        }

        .content form input, .content form select {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #2c2c2c;
            color: #f5f5f5;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .content form input::placeholder {
            color: #999;
        }

        .content form input[type="submit"] {
            background: linear-gradient(135deg, #1a73e8, #005cbf);
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            cursor: pointer;
            border: none;
            padding: 15px;
            border-radius: 30px;
            transition: background 0.3s ease;
        }

        .content form input[type="submit"]:hover {
            background: linear-gradient(135deg, #005cbf, #1a73e8);
        }

        /* Przycisk powrotu */
        .back-button {
            margin-top: 20px;
            font-size: 1.2rem;
            text-decoration: none;
            display: inline-block;
        }

        /* Responsywność */
        @media (max-width: 768px) {
            .content {
                width: 100%;
                padding: 20px;
            }

            header h1 {
                font-size: 1.5rem;
            }

            .content form input[type="submit"] {
                font-size: 1rem;
                padding: 10px;
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
    <header>
        <h1>xObywatel | Generator <i class="fas fa-user"></i></h1>
        <a href="dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Powrót</a>
    </header>

    <div class="loader"></div>

    <div class="content">
        <form action="dowodnowy.php" method="post">
            <label for="imie">Imię:</label>
            <input type="text" id="imie" name="imie" placeholder="Jan" required>
            
            <label for="nazwisko">Nazwisko:</label>
            <input type="text" id="nazwisko" name="nazwisko" placeholder="Kowalski" required>

            <label for="birthdate">Data urodzenia:</label>
            <input type="text" id="birthdate" name="birthdate" placeholder="01.01.2000" required>

            <label for="pesel">PESEL:</label>
            <input type="text" id="pesel" name="pesel" placeholder="05210169617" required maxlength="11">

            <label for="adres">Adres:</label>
            <input type="text" id="adres" name="adres" placeholder="Złota 44" required>

            <label for="kod_pocztowy_miasto">Kod pocztowy i miasto:</label>
            <input type="text" id="kod_pocztowy_miasto" name="kod_pocztowy_miasto" placeholder="00-120, Warszawa" required>

            <label for="data_zameldowania">Data zameldowania:</label>
            <input type="date" id="data_zameldowania" name="data_zameldowania" required>

            <label for="ostatnia_aktualizacja">Ostatnia aktualizacja:</label>
            <input type="date" id="ostatnia_aktualizacja" name="ostatnia_aktualizacja" required>

            <label for="seria_i_numer">Seria i numer:</label>
            <input type="text" id="seria_i_numer" name="seria_i_numer" placeholder="FIP146052" required>

            <label for="termin_waznosci">Termin ważności:</label>
            <input type="date" id="termin_waznosci" name="termin_waznosci" required>

            <label for="data_wydania">Data wydania:</label>
            <input type="date" id="data_wydania" name="data_wydania" required>

            <label for="link_zdjecia">Link do zdjęcia:</label>
            <input type="text" id="link_zdjecia" name="link_zdjecia" required>

            <label for="plec">Płeć:</label>
            <select id="plec" name="plec" required>
                <option value="">Wybierz...</option>
                <option value="Mężczyzna">Mężczyzna</option>
                <option value="Kobieta">Kobieta</option>
            </select>

            <label for="miejsce_urodzenia">Miejsce urodzenia:</label>
            <input type="text" id="miejsce_urodzenia" name="miejsce_urodzenia" placeholder="Warszawa" required>

            <label for="nazwisko_rodowe_ojca">Nazwisko rodowe ojca:</label>
            <input type="text" id="nazwisko_rodowe_ojca" name="nazwisko_rodowe_ojca" placeholder="Kiepski" required>

            <label for="nazwisko_rodowe_matki">Nazwisko rodowe matki:</label>
            <input type="text" id="nazwisko_rodowe_matki" name="nazwisko_rodowe_matki" placeholder="Kowalska" required>

            <label for="miasto">Organ Wydający:</label>
            <input type="text" id="miasto" name="miasto" placeholder="WARSZAWA" required>

            <label for="imie_ojca">Imię ojca:</label>
            <input type="text" id="imie_ojca" name="imie_ojca" placeholder="Ferdynand" required>

            <label for="imie_matki">Imię matki:</label>
            <input type="text" id="imie_matki" name="imie_matki" placeholder="Joanna" required>

            <input type="submit" value="Generuj">
        </form>
   </div>
   <div class="navbar">
        <a href="info.php" class="nav-link"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
    </div>
</body>
</html>
