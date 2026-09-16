<?php session_start();
$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");

if ($db->connect_error) {
    die("DB klaida");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Nerastas produktas");
}

$stmt = $db->prepare(" SELECT labels.*, categories.name AS category_name FROM labels JOIN categories ON labels.category_id = categories.id WHERE labels.id = ? ");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();

if (!$item) {
    die("Produktas nerastas");
}

if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id'];

    if (!isset($_FILES['label_file']) || $_FILES['label_file']['error'] !== 0) {
        die("Failas nepridėtas");
    }

    $uploadDir = __DIR__ . '/uploads/tmp/';


    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES['label_file']['name']);
    move_uploaded_file($_FILES['label_file']['tmp_name'], $uploadDir . $fileName);
    $_SESSION['cart'][] = [
        'label_id' => $item['id'], 
        'name'     => $item['name'], 
        'price'    => $item['price'], 
        'photo'    => $item['photo'], 
        'file'     => $fileName,
        'is_temp'  => true
    ];
    header("Location: cart.php");
    exit;
} ?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETIKETES</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <div class="header">
        <a href="../index.php">
            <h1>ETIKETES DIZAINAS</h1>
        </a>

        <div>
            <a href="cart.php">
                <button class="cart-btn"><i class="fa-solid fa-cart-shopping"></i></button>
            </a>
        </div>
        <div>
            <a href="login.php">
                <button class="login-btn">PRISIJUNGTI</button>
            </a>
        </div>
    </div>

    <div class="item-page">
        <div class="item-image">
            <img src="/Kursinis/images/<?php echo $item['photo']; ?>" alt="product">
        </div>

        <div class="item-info">

            <h2><?php echo htmlspecialchars($item['name']); ?></h2>

            <div class="item-category">
                Kategorija: <?php echo $item['category_name']; ?>
            </div>

            <p><?php echo htmlspecialchars($item['desc']); ?></p>
            <p><strong><?php echo $item['price']; ?> €</strong></p>

            <form class="item-form" method="POST" enctype="multipart/form-data">
                <p>Pateikite informaciją, kurią norite matyti ant etiketės</p>
                <p>Tinka .txt, .doc, .docx (kairė/vidurys/dešinė)</p>

                <label for="label-text"> Įkelkite failą </label>
                <input
                    type="file"
                    id="label-file"
                    name="label_file"
                    accept=".doc,.docx, .txt"
                    required>

                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                <button type="submit" name="add_to_cart">PIRKTI</button>
            </form>
        </div>
    </div>

    <div class="footer">
        tlf: +3706132412341234 email: etiketes@gmail.com
    </div>
</body>

</html>