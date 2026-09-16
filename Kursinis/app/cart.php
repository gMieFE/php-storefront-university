<?php
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$error = '';

if (isset($_POST['checkout'])) {
    if (empty($cart)) {
        $error = 'Krepšelis tuščias';
    } else {
        header("Location: order_info.php");
        exit;
    }
}


?>


<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETIKETĖS</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="../index.php">
            <h1>ETIKETES DIZAINAS</h1>
        </a>
    </div>
    <hr>

    <div class="cart-body">
        <h2>PREKĖS</h2>

        <?php echo '<div class="cart-items">';

        if (empty($cart)) {

            echo '<p>Krepšelis tuščias</p>';
        } else {

            foreach ($cart as $id => $item) {

                $total += $item['price'];

                echo '
                        <div class="cart-item">
                            <img src="/Kursinis/images/' . $item['photo'] . '" alt="product_photo">
                            <div class="cart-item-info">
                                <p class="name">' . $item['name'] . '</p>
                                <p class="file">Failas:'. $item['file'] .' ?></p>
                                <p class="kaina">' . $item['price'] . ' €</p>
                            </div>
                            <a href="cart_delete.php?id=' . $id . '">
                                <button class="delete-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </a>
                        </div>';
            }
        }

        echo '</div>';
        ?>

        <p>SUMA: <?php echo $total; ?> €</p>

        <?php
        if ($error) {
            echo '<div class="klaidos">' . $error . '</div>';
        }
        ?>

        <form method="POST">
            <button class="button" type="submit" name="checkout">
                PIRKTI
            </button>
        </form>

    </div>



    <div class="footer">
        tlf: +3706132412341234 email: etiketes@gmail.com
    </div>
</body>

</html>