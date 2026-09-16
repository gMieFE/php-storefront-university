<?php
require_once __DIR__ . '/../auth.php';

$id = $_GET['id'] ?? null;
$errors = [];

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if ($db->connect_error) {
    die("DB klaida");
}

$resCat = $db->query("SELECT * FROM categories");
$allowed_images = ['jpg', 'jpeg', 'png'];

function uploadFile($file, $allowed_images)
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['none', null];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error', 'Klaida įkeliant failą'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_images)) {
        return ['error', 'Netinkamas failo formatas'];
    }

    $newName = time() . "_" . basename($file['name']);
    $path = "../../images/" . $newName;

    if (move_uploaded_file($file['tmp_name'], $path)) {
        return ['success', $newName];
    }

    return ['error', 'Nepavyko įkelti failo'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_id = (int)($_POST['category_id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['desc'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $old_photo = $_POST['old_photo'] ?? '';

    if ($category_id <= 0) $errors[] = "Kategorija privaloma";
    if ($name === '') $errors[] = "Pavadinimas privalomas";
    if ($desc === '') $errors[] = "Aprašas privalomas";
    if ($price <= 0) $errors[] = "Kaina privaloma";

    $photo = $old_photo;
    $upload = uploadFile($_FILES['photo'], $allowed_images);

    if ($upload[0] === 'error') {
        $errors[] = $upload[1];
    }

    if ($upload[0] === 'success') {
        if ($id && $old_photo) {
            $oldPath = "../../images/" . $old_photo;
            if (file_exists($oldPath)) unlink($oldPath);
        }
        $photo = $upload[1];
    }

    if (empty($errors)) {

        if ($id) {
            $stmt = $db->prepare("
                UPDATE labels 
                SET category_id = ?, name = ?, `desc` = ?, price = ?, photo = ?
                WHERE id = ?
            ");
            $stmt->bind_param(
                "issdsi", $category_id, $name, $desc, $price, $photo, $id
            );
        } else {
            $stmt = $db->prepare("
                INSERT INTO labels (category_id, name, `desc`, price, photo)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "issds", $category_id, $name, $desc, $price, $photo
            );
        }

        $stmt->execute();
        header("Location: http://localhost/Kursinis/admin/produktai/produktai.php");
        exit;
    }
}

if ($id) {
    $stmt = $db->prepare("SELECT * FROM labels WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
}

$category_id = $res['category_id'] ?? '';
$name  = $res['name'] ?? '';
$desc  = $res['desc'] ?? '';
$price = $res['price'] ?? '';
$photo = $res['photo'] ?? '';
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
            <h2>PRODUKTAI</h2>

            <div class="edit">
                <form method="post" enctype="multipart/form-data">
                    <div class="form">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" id="id" name="id" value="<?php echo $id ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="name">KAT ID</label>
                            <select id="category_id" name="category_id">
                                <option value="">--Pasirinkite--</option>
                                <?php while ($r = $resCat->fetch_assoc()):
                                    $isSelected = ($r['id'] == $category_id) ? 'selected' : '';

                                    echo "<option value='" . $r['id'] . "' $isSelected>" . $r['name'] . "</option>";

                                endwhile; ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="name">PAVADINIMAS</label>
                            <input type="text" id="name" name="name" value="<?php echo $name ?>">
                        </div>
                        <div class="form-group">
                            <label for="name">APRASAS</label>
                            <input type="text" id="desc" name="desc" value="<?php echo $desc ?>">
                        </div>
                        <div class="form-group">
                            <label for="name">KAINA</label>
                            <input type="text" id="price" name="price" value="<?php echo $price ?>">
                        </div>
                        <?php
                        echo '<div class="form-foto">
                                <label>NUOTRAUKA</label>';

                        if (!empty($photo)) {
                            echo '
                            <div style="margin-bottom:10px;">
                                <p><strong>Dabartinė nuotrauka:</strong></p>
                                <img src="../../images/' . htmlspecialchars($photo) . '" 
                                    style="max-width:150px; border-radius:8px;">
                                <input type="hidden" name="old_photo" value="' . htmlspecialchars($photo) . '">
                            </div>
                            <p style="font-size:13px; opacity:0.7;">
                                Jei nepasirinksite naujos – bus naudojama ši.
                            </p>';
                        }

                        echo '
                                <input type="file" name="photo" id="photo">
                            </div>';

                        ?>
                    </div>

                    <button class="save-btn" type="submit">Issaugoti</button>
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
                <a href="/Kursinis/admin/produktai/produktai.php">
                    <button class="back-button">ATGAL</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>