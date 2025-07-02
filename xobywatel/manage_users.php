<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $userId = $_POST['user_id'];
        if ($userId == 2 || $userId == 4) {
            echo "<script>alert('Nie możesz usunąć tego użytkownika.');</script>";
        } else {
            $deleteStmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $deleteStmt->execute([$userId]);
            header("Location: manage_users.php");
            exit();
        }
    }

    if (isset($_POST['ban'])) {
        $userId = $_POST['user_id'];
        $banReason = $_POST['ban_reason'];
        $userIp = $_SERVER['REMOTE_ADDR'];

        if ($userId == 2 || $userId == 4) {
            echo "<script>alert('Nie możesz zbanować tego użytkownika.');</script>";
        } else {
            $banStmt = $pdo->prepare("UPDATE users SET is_banned = 1, ban_reason = ?, ban_ip = ?, ban_date = NOW() WHERE id = ?");
            $banStmt->execute([$banReason, $userIp, $userId]);
            header("Location: manage_users.php");
            exit();
        }
    }

    if (isset($_POST['unban'])) {
        $userId = $_POST['user_id'];
        $unbanStmt = $pdo->prepare("UPDATE users SET is_banned = 0, ban_reason = NULL, ban_ip = NULL, ban_date = NULL WHERE id = ?");
        $unbanStmt->execute([$userId]);
        header("Location: manage_users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>Zarządzanie Użytkownikami</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
body {
    font-family: 'Poppins', sans-serif;
    background: #0d0d0d;
    color: #f5f5f5;
    margin: 0;
    padding: 0;
}

header {
    background-color: #1a1a1a;
    padding: 20px;
    text-align: center;
}

header h1 {
    color: white;
}

#user-table {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #1e1e1e;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #555;
}

th {
    background-color: #333;
}

.delete-btn, .ban-btn, .unban-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 5px;
}

.delete-btn:hover {
    background-color: #c0392b;
}

.ban-btn {
    background-color: #ab362a;
}

.ban-btn:hover {
    background-color: #8f2c21;
}

.unban-btn {
    background-color: #28a745;
}

.unban-btn:hover {
    background-color: #218838;
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

.button2 {
    background-color: #e74c3c; /* Czerwony kolor */
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

.button2:hover {
    background-color: #c0392b; /* Ciemniejszy czerwony przy najeździe */
    transform: translateY(-2px);
}

.field {
    margin: 10px 0;
}

.field textarea {
    width: 100%;
    height: 15px;
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
    resize: none;
    background-color: #2a2b38;
    color: #fff;
    margin-top: 10px;
}

.field textarea:focus {
    border-color: #ffeba7;
    outline: none;
}

#banDetailsModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
}

#banDetailsModal .modal-content {
    position: relative;
    width: 400px;
    margin: 100px auto;
    background: #2a2b38;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    color: #fff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
}

#banDetailsModal h3 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    color: #ffeba7;
}

#banDetailsModal p {
    margin: 10px 0;
}

#banDetailsModal button {
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#banDetailsModal button:hover {
    background-color: #2980b9;
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
    #user-table {
        padding: 10px;
        margin: 20px;
    }

    th, td {
        padding: 8px;
    }

    .button, .button2 {
        width: 100%;
        padding: 10px;
        font-size: 0.9rem;
    }
}

    </style>
    <script>
        function showBanDetails(userId, banReason, banIp, banDate) {
            document.getElementById('modalUserId').textContent = userId;
            document.getElementById('modalBanReason').textContent = banReason;
            document.getElementById('modalBanDate').textContent = banDate;
            document.getElementById('banDetailsModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('banDetailsModal').style.display = 'none';
        }
    </script>
</head>
<body>

<header>
    <h1>Zarządzanie Użytkownikami</h1>
    <a href="dashboard.php" class="button">Powrót do Dashboardu</a>
</header>

<div id="user-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa Użytkownika</th>
                <th>Rola</th>
                <th>Status</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo $user['is_banned'] ? 'Zbanowany' : 'Aktywny'; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <?php if ($user['is_banned']): ?>
                                <button type="button" onclick="showBanDetails('<?php echo $user['id']; ?>', '<?php echo htmlspecialchars($user['ban_reason']); ?>', '<?php echo htmlspecialchars($user['ban_ip']); ?>', '<?php echo htmlspecialchars($user['ban_date']); ?>')" class="ban-btn">Szczegóły Bana</button>
                                <button type="submit" name="unban" class="unban-btn">Odbanuj</button>
                                <button type="submit" name="delete" class="delete-btn">Usuń</button>
                            <?php else: ?>
                                <div class="field">
                                    <textarea name="ban_reason" placeholder="Powód bana"></textarea>
                                </div>
                                <button type="submit" name="ban" class="ban-btn">Banuj</button>
                                <button type="submit" name="delete" class="delete-btn">Usuń</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="banDetailsModal">
    <div class="modal-content">
        <h3>Szczegóły Bana</h3>
        <p><strong>ID Użytkownika:</strong> <span id="modalUserId"></span></p>
        <p><strong>Powód Bana:</strong> <span id="modalBanReason"></span></p>
        <p><strong>Data Bana:</strong> <span id="modalBanDate"></span></p>
        <button onclick="closeModal()">Zamknij</button>
    </div>
</div>

<script>
    function showDetailsModal(userId, banReason, ipAddress, banDate) {
        document.getElementById('modalUserId').textContent = userId;
        document.getElementById('modalBanReason').textContent = banReason;
        document.getElementById('modalBanDate').textContent = banDate;
        document.getElementById('banDetailsModal').style.display = 'block';
    }
    function closeModal() {
        document.getElementById('banDetailsModal').style.display = 'none';
    }
</script>

</body>
</html>