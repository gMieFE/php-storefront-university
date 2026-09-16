<?php
require_once __DIR__ . '/../auth.php';

$id = $_GET['id'] ?? null;

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if ($db->connect_error) {
    die("DB klaida");
}

$stmt = $db->prepare(
    "DELETE FROM users WHERE id = ?"
);
$stmt->bind_param( "i", $id);
$stmt->execute();

header("Location: http://localhost/Kursinis/admin/kategorijos/kategorijos.php");
die();
