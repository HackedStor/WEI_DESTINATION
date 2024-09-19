<?php
session_start();
require_once '../auth/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['city'], $data['date'], $data['maxTemp'], $data['minTemp'])) {
    http_response_code(400);
    exit('Invalid data');
}

$user_id = $_SESSION['user_id'];
$city = $data['city'];
$date = $data['date'];
$max_temp = $data['maxTemp'];
$min_temp = $data['minTemp'];

$stmt = $pdo->prepare("INSERT INTO potential_destinations (user_id, city, forecast_date, max_temp, min_temp) VALUES (?, ?, ?, ?, ?)");

if ($stmt->execute([$user_id, $city, $date, $max_temp, $min_temp])) {
    http_response_code(200);
    echo json_encode(['message' => 'Ville étiquetée avec succès']);
} else {
    http_response_code(500);
    echo json_encode(['message' => "N'a pas étiqueté la ville"]);
}