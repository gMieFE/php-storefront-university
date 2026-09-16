<?php
    require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="header">
        <h1>ETIKETES</h1>
        <a href="http://localhost/Kursinis/index.php">
            <button class="home-btn">HOME PAGE</button>
        </a>
    </div>
    
    <div class="layout">
        <div class="main-buttons">
            <a href="produktai/produktai.php">
                <button class="side-button">Produktai</button>
            </a>
            <a href="kategorijos/kategorijos.php">
                <button class="side-button">Kategorios</button>
            </a>

            <a href="uzsakymai/uzsakymai.php">
                <button class="side-button">Užsakymai</button>
            </a>

            <a href="vartotojai/vartotojai.php">
                <button class="side-button">Vartotojai</button>
            </a>
        </div>
    </div>
</body>

</html>