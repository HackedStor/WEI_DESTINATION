<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../auth/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT pd.*, u.username 
    FROM potential_destinations pd
    JOIN users u ON pd.user_id = u.id
");
$stmt->execute();
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Potential Destinations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        
        
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .navbar .right {
            float: right;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="../user/dashboard.php">Profil</a>
        <a href="dashboard.php">Villes potentielles</a>
        <a href="../auth/logout.php" class="right">Logout</a>
    </div>

    <div class="container">
        <h1>Your Potential Destinations</h1>
        <table>
            <thead>
                <tr>
                    <th>City</th>
                    <th>Date</th>
                    <th>Max Temp (°C)</th>
                    <th>Min Temp (°C)</th>
                    <th>Tagged On</th>
                    <th>Tagged By</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($destinations as $destination): ?>
                <tr>
                    <td><?= htmlspecialchars($destination['city']) ?></td>
                    <td><?= htmlspecialchars($destination['forecast_date']) ?></td>
                    <td><?= htmlspecialchars($destination['max_temp']) ?></td>
                    <td><?= htmlspecialchars($destination['min_temp']) ?></td>
                    <td><?= htmlspecialchars($destination['created_at']) ?></td>
                    <td><?= htmlspecialchars($destination['username']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>