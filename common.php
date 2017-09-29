<?php
require_once "config.php";

$conn = mysqli_connect(db_servername, db_username, db_password, db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function setData($conn, &$products_array, &$sql, $key) {
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

function selectDataById($conn, &$products_array, &$sql, $key) {
    $sql = "SELECT id, title, description, price, img FROM products WHERE id=".$key;

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $title, $description, $price, $img);
        
        while (mysqli_stmt_fetch($stmt)) {
            $row = array();
            $row = array('id' => $id, 'title' => $title, 'description' => $description, 'price' => $price, 'img' => $img);
            array_push($products_array, $row);
        }
    } else {
        $products_error = 'Product NOT found!';
        exit();
    }
}

function insertDataByID($conn, &$sql, $key, $title, $price, $description, $image ) {    
    $sql = "INSERT INTO products VALUES (?, ?, ?, ?) WHERE id=".$key;
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_execute($stmt);
    }
}

function updateDataByID($conn, &$sql, $key, $title, $price, $description, $image ) {    
    $sql = "UPDATE products SET title=".$title.", description=".$description.",price=".$price.",img=".$image." WHERE id=".$key;
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_execute($stmt);
    }
}

function deleteDataByID($conn, &$sql, $key, $title, $price, $description, $image ) {
    $sql = "DELETE FROM products WHERE  id=".$key;
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_execute($stmt);
    }
}

function validateInsertData(&$errorMsg, $title, $price, $description, $image) {

        if (strlen($title) < 3) {
            $errorMsg .= 'The Name must contains at least 3 characters <br/>';
        }

        if ( $price == 0) {
            $errorMsg .= 'Invalid price<br/>';
        }

        if (strlen($description) < 3) {
            $errorMsg .= 'Description too short! <br/>';
        }

        if (!is_valid_type($image)){
            $errorMsg .= 'Not a valid File!';
        }
          
        if ($errorMsg === '') {
            return 1;
        } else {
            return 0;
        }
}

function emptyCart() {
    unset($_SESSION['cart']);
    if (!isset($_SESSION['cart'])) {
        $cart = array(); 
        $_SESSION['cart'] = $cart;  
    } 
}

function addToCart() {
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

function protect($str) {
    return htmlentities(strip_tags($str));
}

function is_valid_type($file) {
    if($file){
        $size = getimagesize($file);
        
        if (!$size) {
            return 0;
        }

        $valid_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);

        if (in_array($size[2],  $valid_types)) {
            return 1;
        } else {
            return 0;
        }
    }
    return 0;
}