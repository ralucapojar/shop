<?php 
    require_once 'common.php';

    $total = 0;
    $emailConfirm = '';

    if (isset($_GET['action']) && $_GET['action'] == 'empty') {
        emptyCart();
    } 

    if (isset($_GET['action']) && $_GET['action'] == 'remove') {
        $id = $_GET['id'];    
        unset($_SESSION['cart'][$id]);
    }

    if (isset($_GET['quantity']) && isset($_GET['id'])) {    
        addToCart();     
    } 

    $products_array = getProducts(true);

    if (isset($_SESSION['cart']) && sizeof($_SESSION['cart'])) {
        foreach ($products_array as $elem_product) {
            $total = $total + $elem_product["price"] * $_SESSION['cart'][$elem_product['id']]; 
        }
    }    

    if (isset($_GET['action']) && $_GET['action'] == 'emailform'){
            $email = "From: " . protect($_REQUEST['email']) . "\r\n";
            $name = protect($_REQUEST['name']);
            $message = protect($_REQUEST['message']);
            $message .= "\n ";

            foreach ($products_array as $key => $product) {
                $message .= "\n ";
                $message .= 1 + $key;
                $message .= " Product => ". $product['title'];
                $message .= "\n Price:".$product['price']." \n";
                $message .= " Quantity:".$_SESSION['cart'][$product['id']];
                $message .= "\n Description: ".$product['description'];
                $message .= "\n ";
            }
            $message .= "\n Total: ".$total."$ \n"; 
            $emailConfirm = 'Email send Succes!';
            mail($email, $name, $message, $email); 
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
<?= (is_string($products_array) ? $products_array : '') ?>
<?= (sizeof($emailConfirm) ? $emailConfirm : 'ERROR') ?>
<h1 class="error">Cart Products</h2>
<a class="button" type="button"  href="cart.php?action=empty"><?= translate('empty') ?></a>
<a class="button" type="button"  href="index.php"><?= translate('view') ?></a>
<div>     
    <?php  if (!is_string($products_array)): ?>   
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
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <h1 class="error">Final: <?= protect($total) ?>$ </h2>
</div>
<form method="post" name="emailform" action="cart.php?action=emailform">
    <table>
        <tr>
            <th>Name:</th>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><input type="email" name="email"></td>
        </tr>
        <tr>
            <th>Comments:</th>
            <td><textarea name="message"></textarea></td>
        </tr>
    </table>
    <input type="submit" value="<?= translate('checkOut') ?>">
</form>
</body>
</html>