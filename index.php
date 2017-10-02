<?php 
    require_once 'common.php';
    
    if (isset($_GET['quantity']) && isset($_GET['id'])) {    
        addToCart();     
    } 

    if (isset($_GET['action']) && $_GET['action'] == 'empty') {
        emptyCart();
    } 

    $products_array = getProducts(false);     
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
    <h1 style="color:red;">List Products</h2>
    <a class="btnStyle" type="button" href="cart.php"><?= translate('cart') ?></a>
    <a class="btnStyle" type="button" href="index.php?action=empty"><?= translate('empty') ?></a>
    <div>  
        <?php  if (!is_string($products_array)): ?>    
            <?php foreach ($products_array as $elem_product): ?>
            <div class="border">
                <h2 class="title">Product: <?= protect($elem_product["title"]) ?></h2>
                <h3 class="price">Price:  <?= protect($elem_product["price"]) ?>$</h3><br>;
                <img class="image" src="<?= protect($elem_product["img"]) ?>"/><br>;
                <p>Description: <br> <?= protect($elem_product["description"]) ?></p><br>
                <form method="get"  action="index.php">
                    <input type="number" min="1"  placeholder="quantity" name="quantity" required="required" />
                    <input type="hidden" name="id" value="<?= protect($elem_product["id"]) ?>"/>
                    <input type="submit" value="<?= translate('add') ?>" />
                </form>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>    
    </div>
</body>      
</html>         