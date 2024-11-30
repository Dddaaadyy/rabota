<?php
$host = '192.168.199.13';
$user = 'learn';
$password = 'learn';
$database = 'learn_is364-fahritdinov';

$conn = mysqli_connect($host, $user, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];

    $query = "INSERT INTO products (name, category, price) VALUES ('$name', '$category', $price)";
    mysqli_query($conn, $query);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог Товаров</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        button {
            background-color: green;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Каталог Товаров</h1>

<form method="GET" action="">
    <label for="category">Категория:</label>
    <select id="category" name="category">
        <option value="">Все</option>
        <option value="Электроника">Электроника</option>
        <option value="Одежда">Одежда</option>
        <option value="Мебель">Мебель</option>
    </select>

    <label for="min_price">Минимальная цена:</label>
    <input type="number" id="min_price" name="min_price" step="0.01" min="0">

    <label for="max_price">Максимальная цена:</label>
    <input type="number" id="max_price" name="max_price" step="0.01" min="0">

    <label for="search">Поиск по имени:</label>
    <input type="text" id="search" name="search">

    <button type="submit">Применить фильтры</button>
</form>

<ul>
    <?php
    $query = "SELECT * FROM products WHERE 1=1";

    if (isset($_GET['category']) && $_GET['category'] != '') {
        $category = mysqli_real_escape_string($conn, $_GET['category']);
        $query .= " AND category = '$category'";
    }

    if (isset($_GET['min_price']) && $_GET['min_price'] != '') {
        $min_price = (float)$_GET['min_price'];
        $query .= " AND price >= $min_price";
    }

    if (isset($_GET['max_price']) && $_GET['max_price'] != '') {
        $max_price = (float)$_GET['max_price'];
        $query .= " AND price <= $max_price";
    }

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $query .= " AND name LIKE '%$search%'";
    }

    $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "<div class='error'>Ошибка в запросе: " . mysqli_error($conn) . "</div>";
        } else {
            while ($product = mysqli_fetch_assoc($result)): ?>
                <li>
                    <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                    Категория: <?php echo htmlspecialchars($product['category']); ?><br>
                    Цена: <?php echo htmlspecialchars($product['price']); ?> руб.
                </li>
            <?php endwhile;
        }
        ?>
    </ul>
    
    <h2>Добавить новый товар</h2>
    <form method="POST" action="">
        <label for="name">Название товара:</label>
        <input type="text" id="name" name="name" required>
    
        <label for="category">Категория:</label>
        <select id="category" name="category" required>
            <option value="Электроника">Электроника</option>
            <option value="Одежда">Одежда</option>
            <option value="Мебель">Мебель</option>
        </select>
    
        <label for="price">Цена:</label>
        <input type="number" id="price" name="price" step="0.01" min="0" required>
    
        <button type="submit" name="add_product">Добавить товар</button>
    </form>
    
    </body>
    </html>
    
    <?php
    mysqli_close($conn);
    ?>
    