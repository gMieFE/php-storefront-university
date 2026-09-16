<?php
session_start();
$email = '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['pass'] ?? '');

    if ($email === '' || $pass === '') {
        $errors[] = 'Užpildykite visus laukus!';
    }

    if (empty($errors)) {
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");

        if ($db->connect_error) {
            die("DB klaida");
        }

        $stmt = $db->prepare("SELECT id, email, pass, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc()) {
            if (password_verify($pass, $user['pass'])) {

                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'];

                header("Location: http://localhost/Kursinis/index.php");
                exit;

            } else {
                $errors[] = 'Neteisingas slaptažodis!';
            }
        } else {
            $errors[] = 'Tokio vartotojo nėra!';
        }
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
        <h2>Prisijungti</h2>
        <div class="login">
            <form method="POST">
                <label for="email">EMAIL</label>
                <input type="text" id="email" name="email" value="<?php echo $email ?>">
                <label for="pass">SLAPTAZODIS</label>
                <input type="password" id="pass" name="pass" value="">
                <button class='button' type="submit">Prisijungti</button>
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
        <a href="register.php">
            <p>Neturi paskiros?  </p>
        </a>
        <a href="login_newpass.php">
            <p>Pamiršote slaptazodį? </p>
        </a>
    </div>
</body>

</html>