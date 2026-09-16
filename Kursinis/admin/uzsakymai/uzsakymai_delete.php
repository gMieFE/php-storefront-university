<?php
require_once __DIR__ . '/../auth.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: http://localhost/Kursinis/admin/uzsakymai/uzsakymai.php");
    exit;
}

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if ($db->connect_error) {
    die("DB klaida");
}

$stmt = $db->prepare("
    SELECT text FROM order_items WHERE order_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

$uploadDir = __DIR__ . '/../../app/uploads/';

while ($row = $res->fetch_assoc()) {
    if (!empty($row['text'])) {
        $filePath = $uploadDir . $row['text'];
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}

$stmt = $db->prepare("DELETE FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: http://localhost/Kursinis/admin/uzsakymai/uzsakymai.php");
exit;
