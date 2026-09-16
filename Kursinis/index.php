<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin   = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if ($db->connect_error) {
    die("DB klaida");
}

$category_res = $db->query("SELECT * FROM categories");

$filter_category_id = $_GET['filter-category'] ?? null;
$filter_name        = $_GET['filter-name'] ?? null;

$sql = "SELECT * FROM labels WHERE 1=1";
$params = [];
$types  = "";

if (!empty($filter_category_id)) {
    $sql .= " AND category_id = ?";
    $params[] = $filter_category_id;
    $types .= "i";
}

if (!empty($filter_name)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%" . $filter_name . "%";
    $types .= "s";
}

$stmt = $db->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$labels_res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETIKETES DIZAINAS</title>
    <link rel="stylesheet" href="app/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php
echo '<div class="header">
        <a href="index.php">
            <h1>ETIKETES DIZAINAS</h1>
        </a>

        <div>
            <a href="app/cart.php">
                <button class="cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
            </a>
        </div>';

if (!$isLoggedIn) {

    echo '<div>
            <a href="app/login.php">
                <button class="login-btn">PRISIJUNGTI</button>
            </a>
          </div>';

} else {

    echo '<div style="display:flex; align-items:center; gap:10px;">
            <span>' . htmlspecialchars($_SESSION['user_email']) . '</span>
            <a href="app/logout.php">
                <button class="login-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> ATSIJUNGTI
                </button>
            </a>
          </div>';

    if ($isAdmin) {
        echo '<div>
                <a href="/Kursinis/admin">
                    <button class="login-btn">ADMIN-PANEL</button>
                </a>
              </div>';
    }
}

echo '</div>';
?>

<div class="hero">
    <h2>Unikalios etiketės tavo produktams</h2>
    <p>Pasirink dizainą, užsisakyk per kelias minutes</p>
</div>

<div class="filter-prod">
    <form method="GET">
        <input type="text" name="filter-name" value="<?php echo htmlspecialchars($filter_name ?? ''); ?>">

        <label for="filter-category" class="filter-label">Filtras:</label>
        <select name="filter-category" class="filter-select">
            <option value="">--Pasirinkite--</option>
            <?php
            while ($r = $category_res->fetch_assoc()) {
                $selected = ($r['id'] == $filter_category_id) ? 'selected' : '';
                echo "<option value='{$r['id']}' {$selected}>{$r['name']}</option>";
            }
            ?>
        </select>

        <button type="submit" class="button">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
</div>

<div class="items">
<?php
while ($r = $labels_res->fetch_assoc()) {
    echo '<a href="app/item.php?id=' . $r['id'] . '">
            <div class="product-card">
                <img class="product-image"
                     src="/Kursinis/images/' . htmlspecialchars($r['photo']) . '"
                     alt="product_photo">
                <p>' . htmlspecialchars($r['name']) . '</p>
                <p>' . htmlspecialchars($r['price']) . ' €</p>
            </div>
          </a>';
}
?>
</div>

<div class="footer">
        tlf: +3706132412341234 email: etiketes@gmail.com
</div>

</body>
</html>
