<?php
require_once __DIR__ . '/../auth.php';

$errors = [];
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $errors[] = 'Būtina įrašyti pavadinimą!';
    }

    if (empty($errors)) {
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
        if (!$db->connect_error) {
            if ($id) {
                $stmt = $db->prepare(
                    "UPDATE categories SET name = ? WHERE id = ?"
                );
                $stmt->bind_param("si", $name, $id);
            } else {
                $stmt = $db->prepare(
                    "INSERT INTO categories (name) VALUES (?)"
                );
                $stmt->bind_param("s", $name);
            }

            $stmt->execute();
        }

        header("Location: http://localhost/Kursinis/admin/kategorijos/kategorijos.php");
        die();
    } 
    
} else {
    $name = null;
    if ($id) {
        $db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
        if (!$db->connect_error) {
            $res = $db->query("SELECT * FROM categories WHERE id = $id");
            $res = $res->fetch_assoc();
            $name = $res["name"];
        }
    }
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
            <h2>KATEGORIJOS</h2>

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
                <a href="/Kursinis/admin/kategorijos/kategorijos.php">
                    <button class="back-button">ATGAL</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>