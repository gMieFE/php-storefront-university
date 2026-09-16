<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /Kursinis/app/login.php");
    exit;
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /Kursinis/index.php");
    exit;
}
