<?php 
    session_start();
    require_once 'common.php';

    $products_error = '';
    $total = 0;
    $empty_cart = 'The cart is empty!';
    $checkUrl = $_SERVER["REQUEST_URI"];
    $products_array =array();
    $sql = "";
    setData($conn, $products_array, $sql, '');
    
    if (strpos($checkUrl, 'empty')) {
        emptyCart();
        header("Location: cart.php");
        die;
    } 

    if (strpos($checkUrl, 'remove')) {
        $id = $_GET['id'];    
        unset($_SESSION['cart'][$id]);
        header("Location: cart.php");
        die;
    }

    if (strpos($checkUrl, 'quantity')) {    
        $id = $_GET['id'];
        $quantity = $_GET['quantity'];
        $_SESSION['cart'][$id] =  $quantity; 
        header("Location: cart.php"); 
        die;       
    }    
?>     
<html>
<head>
<style type="text/css">
    .image {width:200px; height:150px;}
    .error {color:red;}
    .error {color:red;}
    .info {color:black;}
    .border {border-style:dotted; width:500px;}
    .btnStyle {display:inline; margin:25px; width:200px; height:60px;}
    .title {margin:50px;}
    .price {color:green;}
</style>
</head>
<body>
<?= $products_error ?>
<h1 class="error">Cart Products</h2>
<a class="button" type="button"  href="cart.php?action=empty"><?= translate('empty') ?></a>
<a class="button" type="button"  href="index.php"><?= translate('view') ?></a>
<div>     
    <?php foreach ($products_array as $elem_product): ?>
    <?php if (in_array($elem_product["id"], array_keys($_SESSION['cart']))): ?>
    <div class="border">
    <h2 class="title">Product: <?= protect($elem_product["title"]) ?></h2>
    <h3 class="price">Quantity: <?= protect($_SESSION['cart'][$elem_product["id"]]) ?></h3><br>
    <form method="get" action="cart.php">
    <input style="display:inline;" type="number" min="1"  placeholder="<?= protect($_SESSION['cart'][$elem_product["id"]]) ?>" name="quantity" required="required" />
    <input style="display:inline;" type="hidden" name="id" value="<?= protect($elem_product["id"]) ?>"/>
    <input style="display:inline;" type="submit" value="<?= translate('change') ?>" />
        </form>
        <h3 class="price">Total Price: <?= protect($elem_product["price"] * $_SESSION['cart'][$elem_product["id"]]) ?>$</h3><br>
        <img class="image" src="<?= protect($elem_product["img"]) ?>"/><br>
        <p>Description: <br> <?= protect($elem_product["description"]) ?></p><br>
        <?php $rmElem = 'cart.php?action=remove&id='.protect($elem_product['id']) ?>
        <a href="<?= $rmElem ?>"><?= translate('remove') ?></a>
    </div>
    <?php $total = $total + $elem_product["price"] * $_SESSION['cart'][$elem_product["id"]]; ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <h1 class="error">Final: <?= protect($total) ?>$ </h2>
</div>
<table>
    <tr><form method="post" name="emailform" action="cart.php"></tr>
    <tr>
        <th>Name:</th>
        <td><input type="text" name="name"></td>
    </tr>
    <tr>
        <th>Email:</th>
        <td><input type="text" name="email"></td>
    </tr>
    <tr>
        <th>Comments:</th>
        <td><textarea name="message"></textarea></td>
    </tr>
</table>
<input type="submit" value="<?= translate('signOut') ?>">
</form>
</body>
</html>