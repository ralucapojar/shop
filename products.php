	<?php 
	session_start();
	require_once 'db.php';

	$products_error = '';
	$total = 0;
	$empty_cart = 'The cart is empty!';
	$checkUrl = $_SERVER["REQUEST_URI"];
	$products_array = array();	
  	$sql = "";
	setData($conn, $products_array, $sql, 'NOT');
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
	<h1 style="color:red;">List Products</h2>
	<a class="btnStyle" type="button" href="logIn.php"><?= translate('signOut') ?></a>
	<a class="btnStyle" type="button" href="product.php"><?= translate('add') ?></a>
	<div>	 
	<?php foreach ($products_array as $elem_product): ?>
	<div class="border">
	<h2 class="title">Product: <?= $elem_product["title"] ?></h2>
	<h3 class="price">Price:  <?= $elem_product["price"] ?>$</h3><br>;
	<img class="image" src="<?= $elem_product["img"] ?>"/><br>;
	<p>Description: <br> <?=$elem_product["description"] ?></p><br>
	<form method="get"  action="index.php">
	<input type="number" min="1"  placeholder="quantity" name="quantity" required="required" />
	<input type="hidden" name="id" value="<?= $elem_product["id"] ?>"/>
	<input type="submit" value="Add to Cart!" />
	<?php $removeElem = 'cart.php?action=remove&id='.$elem_product['id'] ?>
	<a href="<?= $removeElem ?>"><?= translate('delete') ?></a>
	<?php $editElem = 'cart.php?action=edit&id='.$elem_product['id'] ?>
	<a href="<?= $editElem ?>"><?= translate('edit') ?></a>
	</form>
	</div>
	<?php endforeach; ?>
	</div>
  	</body>  	
	</html> 		