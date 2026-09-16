<?php
session_start();
$errors = [];
$email = '';
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['pass'] ?? '');
    $pass2 = $_POST['pass_repeat'] ?? '';


    if ($email === '' || $pass === '' || $name === '') {
        $errors[] = 'Užpildykite visus laukus!';
    }
    if ($pass !== $pass2) {
        $errors[] = 'Slaptažodžiai nesutampa!';
    }

    if (empty($errors)) {
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
        $role = 'user';

        if ($db->connect_error) {
            die("DB klaida");
        }

        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'Toks el. paštas jau registruotas!';
        }
    }

    if (empty($errors)) {
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");

        $role = 'user';


        if (!$db->connect_error) {
            $stmt = $db->prepare(
                "INSERT INTO users (name, email, pass, role)
                        VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $name, $email, $pass, $role);
            $stmt->execute();
        } else {
            die("DB klaida");
        }
        header("Location: http://localhost/Kursinis/index.php");
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETIKETES</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="header">
        <a href="../index.php">
            <h1>ETIKETES DIZAINAS</h1>
        </a>
    </div>

    <div class="body-login">
        <h2>Registruotis</h2>
        <div class="login">
            <form method="POST" autocomplete="off">
                <label for="name">VARDAS</label>
                <input type="text" id="name" name="name"  value="<?php echo $name ?>" autocomplete="off">
                <label for="email">EMAIL</label>
                <input type="text" id="email" name="email" value="<?php echo $email  ?>" autocomplete="new-email">
                <label for="pass">SLAPTAZODIS</label>
                <input type="password" id="pass" name="pass" autocomplete="new-password">
                <label for="pass">PAKARTOTI SLAPTAZODI</label>
                <input type="password" id="pass_repeat" name="pass_repeat" autocomplete="new-password">

                <button class='button' type="submit">Registruotis</button>
                <hr>
                <a href="login.php">
                    <p>Turi paskira? </p>
                </a>
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
    </div>
</body>

</html>