<?php
session_start();
unset($_SESSION['order_completed']);
?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Pirkimas sėkmingas</title>
    <link rel="stylesheet" href="styles.css">

    <script>
        setTimeout(function() {
            window.location.href = "../index.php";
        }, 3000);
    </script>
</head>

<body>
    <div class="purchased">
        <h1>Užsakymas sėkmingas!</h1>
        <p>Būsite nukreipti į pagrindinį puslapį po 3 sekundžių...</p>
    </div>
</body>

</html>