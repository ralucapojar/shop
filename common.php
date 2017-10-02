<?php
session_start();
require_once "config.php";
global $conn;

$conn = mysqli_connect(db_servername, db_username, db_password, db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getProducts( $key) {
    global $conn;
    $sql = "SELECT id, title, description, price, img FROM products";
    $noQuestionMarks = '';

    $products_array = array();
    $refs = array_keys($_SESSION['cart']);
    $sessionSize = sizeof($_SESSION['cart']);

    if ($sessionSize) {
        $noQuestionMarks = implode(',', array_fill(0, $sessionSize, '?'));
    } 
    
    if ($noQuestionMarks != '') {
        $sql .= ' WHERE id '.($key ? '' : 'NOT').' IN ('.$noQuestionMarks.')';
    }

    if ($stmt = mysqli_prepare($conn, $sql)) {
        
        if ($sessionSize) {
            $types = '';
            
            for($i = 0; $i < $sessionSize; $i++) {
                $types =  $types."d";
            }

            // $refs = array();
            // foreach($_SESSION['cart'] as $key => $value) {
            //     $refs[$key] = &$_SESSION['cart'][$key];
            // }

            array_unshift($refs, $types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $title, $description, $price, $img);
        
        while (mysqli_stmt_fetch($stmt)) {
            $row = array();
            $row = array('id' => $id, 'title' => $title, 'description' => $description, 'price' => $price, 'img' => $img); 
            array_push($products_array, $row);
        }

    } else {
        $products_array = 'Products NOT found!';
    }

    return $products_array;
}

function selectDataById( $key) {
    global $conn;
    $sql = "SELECT id, title, description, price, img FROM products WHERE id= ? ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        $stmt->bind_param('d', $key);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $title, $description, $price, $img);
        
        while (mysqli_stmt_fetch($stmt)) {
            $products_array = array('id' => $id, 'title' => $title, 'description' => $description, 'price' => $price, 'img' => $img);
        }
     } else {
        $products_array = 'Products NOT found!';
    }

    return $products_array;
}

function insertDataByID( $title, $price, $description, $image ) {    
    $sql = "INSERT INTO products VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
         $stmt->bind_param('ssdsd', $title, $description, $price, $image);
        mysqli_stmt_execute($stmt);
    }
}

function updateDataByID( $key, $title, $price, $description, $image ) {    
    $sql = "UPDATE products SET title=?, description=?, price=?, img=? WHERE id=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $stmt->bind_param('ssdsd', $title, $description, $price, $image, $key);
        mysqli_stmt_execute($stmt);
    }
}

function deleteDataByID( $key, $title, $price, $description, $image ) {
    $sql = "DELETE FROM products WHERE id=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $stmt->bind_param('d', $key);
        mysqli_stmt_execute($stmt);
    }
}

function validateInsertData( $title, $price, $description, $image) {
    $errorMsg = '';

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
        return $errorMsg;
    } else {
        return $errorMsg;
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