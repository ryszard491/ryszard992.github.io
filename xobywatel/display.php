<?php
if (isset($_GET['folder'])) {
    $folderName = urldecode($_GET['folder']);
    $filePath = "generated_docs/" . $folderName . "/dane.json";

    if (file_exists($filePath)) {
        $data = json_decode(file_get_contents($filePath), true);
    } else {
        die("Data not found.");
    }
} else {
    die("No folder specified.");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xObywatel | Dane</title>
    <link rel="stylesheet" href="../dowod_files/main.css">
</head>
<body>
    <header>
        <h1>Dane użytkownika</h1>
    </header>
    <main>
        <pre><?php echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
    </main>
</body>
</html>
