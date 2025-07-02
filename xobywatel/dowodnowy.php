<?php
session_start();
// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php'; // Plik z połączeniem do bazy danych

error_reporting(E_ALL); // Raportuj wszystkie błędy
ini_set('display_errors', 1); // Wyświetl błędy na stronie

// Sprawdzenie, czy formularz został wysłany metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $pesel = $_POST['pesel'];
    $birthdate = $_POST['birthdate']; // Data jako string
    $plec = $_POST['plec'];
    $nazwisko_rodowe_ojca = $_POST['nazwisko_rodowe_ojca'];
    $nazwisko_rodowe_matki = $_POST['nazwisko_rodowe_matki'];
    $miejsce_urodzenia = $_POST['miejsce_urodzenia'];
    $data_zameldowania = $_POST['data_zameldowania'];
    $seria_i_numer = $_POST['seria_i_numer'];
    $termin_waznosci = $_POST['termin_waznosci'];
    $data_wydania = $_POST['data_wydania'];
    $imie_ojca = $_POST['imie_ojca'];
    $imie_matki = $_POST['imie_matki'];
    $link_zdjecia = $_POST['link_zdjecia'];
    $miasto = $_POST['miasto'];

    // Generowanie unikalnego identyfikatora
    $unique_id = uniqid();

    // Wstawienie danych do bazy danych
    $stmt = $pdo->prepare("INSERT INTO dowody (username, imie, nazwisko, pesel, birthdate, plec, nazwisko_rodowe_ojca, nazwisko_rodowe_matki, miejsce_urodzenia, data_zameldowania, seria_i_numer, termin_waznosci, data_wydania, imie_ojca, imie_matki, link_zdjecia, miasto, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Wykonanie zapytania
    try {
        $stmt->execute([
            $_SESSION['username'], // Zakładam, że masz zmienną sesyjną z nazwą użytkownika
            $imie, $nazwisko, $pesel, $birthdate, $plec,
            $nazwisko_rodowe_ojca, $nazwisko_rodowe_matki, $miejsce_urodzenia,
            $data_zameldowania, $seria_i_numer, $termin_waznosci,
            $data_wydania, $imie_ojca, $imie_matki, $link_zdjecia,
            $miasto, $unique_id
        ]);
        
        // Generowanie linku do dowodu
        $link_do_dowodu = "http://xobywatel.cba.pl/public/host/xobywatel/admin.php?unique_id=$unique_id";

        // Wyświetlenie komunikatu w estetycznym UI
        echo "
        <!DOCTYPE html>
        <html lang='pl'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>xObywatel | Dowód Zapisany</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap' rel='stylesheet'>
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    background: #0d0d0d;
                    color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .container {
                    background: #1a1a1a;
                    border-radius: 8px;
                    padding: 20px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                    text-align: center;
                }
                h2 {
                    margin-bottom: 20px;
                    color: #f5f5f5;
                }
                p {
                    margin-bottom: 10px;
                    font-size: 1.2rem;
                }
                a {
                    display: inline-block;
                    margin-top: 15px;
                    background: linear-gradient(135deg, #1a73e8, #005cbf);
                    color: white;
                    padding: 15px 30px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: background 0.3s ease;
                }
                a:hover {
                    background: linear-gradient(135deg, #005cbf, #1a73e8);
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Twój dowód został zapisany!</h2>
                <p>Możesz go udostępnić pod tym linkiem:</p>
                <a href='$link_do_dowodu' target='_blank'>Pobierz Dowód</a>
            </div>
        </body>
        </html>";
        
    } catch (PDOException $e) {
        die("Wystąpił błąd przy zapisie danych: " . $e->getMessage());
    }
    exit;
}
?>