<?php
require_once __DIR__ . '/../auth.php';

$id = $_GET['id'] ?? null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $pass        = trim($_POST['pass']);
    $role        = trim($_POST['role'] ?? '');

    if ($name === '') {
        $errors[] = "Vardas privalomas";
    }
    if ($email === '') {
        $errors[] = "Email privalomas";
    }
    if ($pass === '') {
        $errors[] = "Slaptažodis privalomas";
    }

    if (empty($errors)) {
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");

        $pass = password_hash($pass, PASSWORD_DEFAULT);

        if (!$db->connect_error) {
            if ($id) {
                $stmt = $db->prepare(
                    "UPDATE users 
                     SET name = ?, email = ?, pass = ?, role = ?
                     WHERE id = ?"
                );
                $stmt->bind_param("ssssi", $name, $email, $pass, $role, $id);
            } else {
                $stmt = $db->prepare(
                    "INSERT INTO users (name, email, pass, role)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->bind_param("ssss", $name, $email, $pass, $role);
            }

            $stmt->execute();
        }
        header("Location: http://localhost/Kursinis/admin/vartotojai/vartotojai.php");
        die();
    }
} else {
    if ($id) {
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
        if (!$db->connect_error) {
            $res = $db->query("SELECT * FROM users WHERE id = $id");
            $res = $res->fetch_assoc();
        }
    }
    $name       = trim($res['name'] ?? '');
    $email      = trim($res['email'] ?? '');
    $pass       = trim($res['pass'] ?? '');
    $role       = trim($res['role'] ?? '');
}


?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../styles.css">
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
            <a href="/Kursinis/admin/produktai/produktai.php">
                <button class="side-button">Produktai</button>
            </a>
            <a href="/Kursinis/admin/kategorijos/kategorijos.php">
                <button class="side-button">Kategorios</button>
            </a>

            <a href="/Kursinis/admin/uzsakymai/uzsakymai.php">
                <button class="side-button">Užsakymai</button>
            </a>

            <a href="/Kursinis/admin/vartotojai/vartotojai.php">
                <button class="side-button">Vartotojai</button>
            </a>
        </div>

        <div class="content">
            <h2>VARTOTOJAI</h2>

            <div class="edit">
                <form method="post">
                    <div class="form">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" id="id" name="id" value="<?php echo $id ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="name">PAVADINIMAS</label>
                            <input type="text" id="name" name="name" value="<?php echo $name ?>">
                        </div>
                        <div class="form-group">
                            <label for="name">EMAIL</label>
                            <input type="text" id="email" name="email" value="<?php echo $email ?>">
                        </div>
                        <div class="form-group">
                            <label for="name">SLAPTAŽODIS</label>
                            <input type="password" id="pass" name="pass" value="<?php echo $pass ?>">
                        </div>
                        <div class="form-group">
                            <label for="name">ADMIN</label>
                            <input type="radio" id="role" name="role" value="admin" <?php if ($role === 'admin') echo "checked" ?>>
                        </div>
                        <div class="form-group">
                            <label for="name">USER</label>
                            <input type="radio" id="role" name="role" value="user" <?php if ($role === 'user') echo "checked" ?>>
                        </div>
                    </div>

                    <button class="save-btn" type="submit">Išsaugoti</button>
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
            <div>
                <a href="/Kursinis/admin/vartotojai/vartotojai.php">
                    <button class="back-button">ATGAL</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>