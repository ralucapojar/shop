<?php 
    require_once 'common.php';
    if (!isset($_SESSION['logged'])) {
        die();  
    }    

    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        deleteDataByID($id);        
    }

    $products_array = getProducts($conn, false);

    if (!is_string($products_array)) {
        $products_error = '';
    } else {
        $products_error = translate('notFoundProducts');
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
    <h1 style="color:red;"><?= translate('listProduct');?></h2>
    <a class="btnStyle" type="button" href="logIn.php"><?= translate('signOut') ?></a>
    <a class="btnStyle" type="button" href="product.php"><?= translate('add') ?></a>
    <div>     
        <?php foreach ($products_array as $elem_product): ?>
        <div class="border">
            <h2 class="title"><?= translate('product'); protect($elem_product["title"]) ?></h2>
            <h3 class="price"><?= translate('price'); protect($elem_product["price"]) ?>$</h3><br>;
            <img class="image" src="<?= protect($elem_product["img"]) ?>"/><br>;
            <p><?= translate('description');?> <br> <?= protect($elem_product["description"]) ?></p><br>
            <form method="get"  action="index.php">
                <input type="number" min="1"  placeholder="<?= translate('quantity');?>" name="quantity" required="required" />
                <input type="hidden" name="id" value="<?= protect($elem_product["id"]) ?>"/>
                <input type="submit" value="Add to Cart!" />
                <a href="products.php?action=delete&id=<?=protect($elem_product['id'])?>"</a>
                <a href="product.php?action=update&id=<?= protect($elem_product['id'])?>"><?= translate('edit') ?></a>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</body>      
</html>     