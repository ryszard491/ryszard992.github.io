<?php
session_start();
require 'db_connection.php';

if (!isset($_GET['unique_id'])) {
    die("Brak unikalnego identyfikatora.");
}

$unique_id = $_GET['unique_id'];

$stmt = $pdo->prepare("SELECT * FROM dowody WHERE unique_id = ?");
$stmt->execute([$unique_id]);
$dowod = $stmt->fetch();

if (!$dowod) {
    die("Nie znaleziono dowodu.");
}

$html_template = file_get_contents('dowodnowy.html');

$html_template = str_replace('{IMIE}', htmlspecialchars($dowod['imie']), $html_template);
$html_template = str_replace('{NAZWISKO}', htmlspecialchars($dowod['nazwisko']), $html_template);
$html_template = str_replace('{PESEL}', htmlspecialchars($dowod['pesel']), $html_template);
$html_template = str_replace('{BRITHDATE}', htmlspecialchars($dowod['birthdate']), $html_template);
$html_template = str_replace('{PLEC}', htmlspecialchars($dowod['plec']), $html_template);
$html_template = str_replace('{nazwisko_rodowe_ojca}', htmlspecialchars($dowod['nazwisko_rodowe_ojca']), $html_template);
$html_template = str_replace('{nazwisko_rodowe_matki}', htmlspecialchars($dowod['nazwisko_rodowe_matki']), $html_template);
$html_template = str_replace('{miejsce_urodzenia}', htmlspecialchars($dowod['miejsce_urodzenia']), $html_template);
$html_template = str_replace('{data_zameldowania}', htmlspecialchars($dowod['data_zameldowania']), $html_template);
$html_template = str_replace('{ostatnia_aktualizacja}', htmlspecialchars($dowod['ostatnia_aktualizacja']), $html_template);
$html_template = str_replace('{seria_i_numer}', htmlspecialchars($dowod['seria_i_numer']), $html_template);
$html_template = str_replace('{termin_waznosci}', htmlspecialchars($dowod['termin_waznosci']), $html_template);
$html_template = str_replace('{data_wydania}', htmlspecialchars($dowod['data_wydania']), $html_template);
$html_template = str_replace('{imie_ojca}', htmlspecialchars($dowod['imie_ojca']), $html_template);
$html_template = str_replace('{imie_matki}', htmlspecialchars($dowod['imie_matki']), $html_template);
$html_template = str_replace('{link_zdjecia}', htmlspecialchars($dowod['link_zdjecia']), $html_template);
$html_template = str_replace('{miasto}', htmlspecialchars($dowod['miasto']), $html_template);

echo $html_template;
?>