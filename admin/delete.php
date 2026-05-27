<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require __DIR__ . '/../config/app.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = intval($_GET['id']);

error_log("Method: " . $method); // 👈 logs to PHP error log
error_log("ID: " . $id);

if ($method === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);

        $affected = $stmt->rowCount(); // 👈 how many rows deleted
        error_log("Rows affected: " . $affected);

        echo json_encode([
            'success' => true,
            'affected' => $affected,
            'id' => $id
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Wrong method: ' . $method]);
