<?php
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass1 = $_POST['pass1'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';

    if ($email === '' || $pass1 === '' || $pass2 === '') {
        $errors[] = 'Užpildykite visus laukus';
    }

    if ($pass1 !== $pass2) {
        $errors[] = 'Slaptažodžiai nesutampa';
    }

    if (empty($errors)) {

        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
        if ($db->connect_error) {
            die("DB klaida");
        }

        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $errors[] = 'Tokio vartotojo nėra';
        } else {
            $hashed = password_hash($pass1, PASSWORD_DEFAULT);

            $stmt = $db->prepare("UPDATE users SET pass = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed, $email);
            $stmt->execute();

            $success = 'Slaptažodis sėkmingai pakeistas';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>ETIKETES</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="header">
        <a href="index.php">
            <h1>ETIKETES DIZAINAS</h1>
        </a>
    </div>

    <div class="body-login">
        <h2>Pakeisti slaptažodį</h2>

        <div class="login">
            <form method="POST">

                <label for="email">EMAIL</label>
                <input type="email" name="email" autocomplete="new-email" >

                <label for="pass1">NAUJAS SLAPTAŽODIS</label>
                <input type="password" name="pass1" autocomplete="new-password">

                <label for="pass2">PAKARTOKITE SLAPTAŽODĮ</label>
                <input type="password" name="pass2" >

                <button class="button" type="submit">Pakeisti</button>
            </form>
        </div>

        <?php
        if (!empty($errors)) {
            echo '<div class="klaidos">';
            foreach ($errors as $k) {
                echo '<p>' . htmlspecialchars($k) . '</p>';
            }
            echo '</div>';
        }
        ?>

        <hr>

        <a href="login.php">
            <button class="button">Atgal</button>
        </a>
    </div>

</body>

</html>