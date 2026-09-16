<?php
session_start();

$errors = [];

if (isset($_SESSION['order_completed'])) {
    header("Location: cart.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if ($db->connect_error) {
    die("DB klaida");
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId   = $_SESSION['user_id'];
    $name     = trim($_POST['name']);
    $email    = $_SESSION['user_email'];
    $address  = trim($_POST['address']);

    if ($name === '' || $address === '') {
        $errors[] = 'Užpildykite visus laukus';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, name, email, address, sum, date)
            VALUES (?, ?, ?, ?, ?, NOW())");

        $stmt->bind_param(
            "isssd",
            $userId,
            $name,
            $email,
            $address,
            $total
        );

        $stmt->execute();
        $orderId = $db->insert_id;
        $stmtItem = $db->prepare("
            INSERT INTO order_items (order_id, label_id, price, text)
            VALUES (?, ?, ?, ?)");

        foreach ($cart as $item) {

            if (!empty($item['is_temp'])) {
                rename(
                    __DIR__ . '/uploads/tmp/' . $item['file'],
                    __DIR__ . '/uploads/' . $item['file']
                );
            }

            $labelId = $item['label_id'];
            $price   = $item['price'];
            $file    = $item['file'];

            $stmtItem->bind_param(
                "iids",
                $orderId,
                $labelId,
                $price,
                $file
            );

            $stmtItem->execute();
        }


        unset($_SESSION['cart']);
        $_SESSION['order_completed'] = true;

        header("Location: successfull_purchase.php");
        exit;
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
        <a href="index.php">
            <h1>ETIKETES DIZAINAS</h1>
        </a>
    </div>
    <hr>

    <div class="order-info-body">
        <h2>Užsakymo informacija</h2>

        <form method="POST" class="order-form">
            <div class="form-group">
                <label for="name">VARDAS</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">EMAIL (siuntimo vieta)</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>">
            </div>

            <div class="form-group">
                <label for="address">ADRESAS</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($address ?? '') ?>">
            </div>

            <button type="submit" class="button primary">PIRKTI</button>
        </form>

        <?php
        if (!empty($errors)) {
            echo '<div class="klaidos">';
            foreach ($errors as $k) {
                echo '<p>' . htmlspecialchars($k) . '</p>';
            }
            echo '</div>';
        }
        ?>

        <a href="cart.php" class="back-link">
            <button class="button">ATGAL</button>
        </a>

    </div>

    <div class="footer">
        tlf: +3706132412341234 email: etiketes@gmail.com
    </div>
</body>

</html>