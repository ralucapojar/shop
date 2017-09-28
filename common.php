<?php
require_once "config.php";

$conn = mysqli_connect(db_servername, db_username, db_password, db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function setData($conn, &$products_array, &$sql, $key){
	$sql = "SELECT id, title, description, price, img FROM products";
	if (isset($_SESSION['cart']) && sizeof($_SESSION['cart'])) {
		$sql .= ' WHERE id '.$key.' IN (' . implode(',', array_keys($_SESSION['cart'])) . ')';
	}

	if ($stmt = mysqli_prepare($conn, $sql)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $title, $description, $price, $img);
		
		while (mysqli_stmt_fetch($stmt)) {
			$row = array();
			$row = array('id' => $id, 'title' => $title, 'description' => $description, 'price' => $price, 'img' => $img);
			array_push($products_array, $row);
		}
	} else {
		$products_error = 'Products NOT found!';
		exit();
	}
}

function emptyCart(){
	unset($_SESSION['cart']);
	if (!isset($_SESSION['cart'])) {
	 	$cart = array(); 
	 	$_SESSION['cart'] = $cart;  
	} 
}

function addToCart(){
	$id = $_GET['id'];
	$numberProd = intval($_GET['quantity']);
	$_SESSION['cart'][$id] = $numberProd;	
}

$translations = array(
	'en' => array(
		'add' => 'Add Product',
		'addProducts' => 'View Own Products',
		'cart' => 'View Cart',
		'change' => 'Change Quantity',
		'delete' => 'Delete',
		'edit' => 'Edit',
		'empty' => 'Empty Cart',
		'remove' => 'Remove from Cart',
		'save' => 'Save',
		'signOut' => 'Check Out',
		'signIn' => 'Sign In',
		'view' => 'List Products'
	),
	'ro' => array(
		'Add Product!' => 'Adauga Produs!'
	)
);

function translate($key) {
	global $translations;
	return isset($translations['en']) && isset($translations['en'][$key]) ?  $translations['en'][$key] : $key;
}