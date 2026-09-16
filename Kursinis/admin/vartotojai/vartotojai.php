<?php
require_once __DIR__ . '/../auth.php';

$db = new mysqli("localhost", "root", "", "etikeciu_parduotuve");
if (!$db->connect_error) {
    $res = $db->query("SELECT * FROM users");
}


?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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
            <a  href="/Kursinis/admin/produktai/produktai.php">
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
            <table>
                <tr class="top-tr">
                    <th>ID</th>
                    <th>PAVADINIMAS</th>
                    <th>EMAIL</th>
                    <th>SLAPTAŽODIS</th>
                    <th>ROLĖ</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <?php while ($r = $res->fetch_assoc()):
                        echo "<tr>";
                        echo    "<td>" . $r['id'] . "</td>";
                        echo    "<td>" . $r['name'] . "</td>";
                        echo    "<td>" . $r['email'] . "</td>";
                        echo    "<td>" . $r['pass'] . "</td>";
                        echo    "<td>" . $r['role'] . "</td>";
                        echo    "<td><a href='vartotojai_edit.php?id=" . $r['id'] . "'><button class='table-btn'>edit</button></a></td>";
                        echo    "<td><a href='vartotojai_delete.php?id=" . $r['id'] . ">'><button class='delete-btn'><i class='fa-solid fa-trash'></i></button></a></td>";
                        echo "</tr>";
                    endwhile; ?>
                </tr>
            </table>

            <a href="/Kursinis/admin/index.php">
                <button class="back-button">ATGAL</button>
            </a>

        </div>
    </div>
</body>

</html>