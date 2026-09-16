<?php
require_once __DIR__ . '/../auth.php';

$id = $_GET['id'];

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if (!$db->connect_error) {

    $stmt = $db->prepare(
        "DELETE FROM labels WHERE id = ?"
    );

    $stmt->bind_param( "i", $id);

    $stmt->execute();

} else {
    die("DB klaida");
}

header("Location: http://localhost/Kursinis/admin/produktai/produktai.php");
die();
