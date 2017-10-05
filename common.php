<?php
session_start();
require_once "config.php";
global $conn;

try {
    $conn = new PDO(db_servername, db_username, db_password);
} catch (PDOException $Exception) {   
    echo "Unable to connect to database.";
    exit;
}

function getProducts( $idExist) {
    global $conn;
    $sql = "SELECT id, title, description, price, img FROM products";
    $noQuestionMarks = '';
    $sessionSize = sizeof($_SESSION['cart']);

    $products_array = array();
     if ($sessionSize) {
        $noQuestionMarks = implode(',', array_fill(0, $sessionSize, '?'));
    } 
    
    if ($noQuestionMarks != '') {
        $sql .= ' WHERE id '.($idExist ? '' : 'NOT').' IN ('.$noQuestionMarks.')';
    }
   
    if ($stmt = $conn->prepare($sql)) {
        $idArray =  array_keys($_SESSION['cart']);
        $stmt->execute($idArray);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            array_push($products_array, $row);
        }
    } else {
        $products_array = 'Products NOT found!';
    }
    return $products_array;
}

function selectDataById( $id) {
    global $conn;
    $products_array = array();
    $sql = "SELECT id, title, description, price, img FROM products WHERE id= :id ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute(array(':id' => $id));

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            array_push($products_array, $row);
        }
     } else {
        $products_array = 'Products NOT found!';
    }

    return $products_array;
}

function insertData( $title, $price, $description, $image ) {   
    global $conn; 
    $sql = "INSERT INTO products (`title`, `description`, `price`, `img`) VALUES (:title, :description, :price, :img)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute(array(':title' => $title, ':description' => $description, ':price' => $price, ':img' => $image));
    }
}

function updateDataByID( $id, $title, $price, $description, $image ) {  
    global $conn;  
    $sql = "UPDATE products SET title = :title, description = :description, price = :price, img = :img WHERE id = :id";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute(array(':title' => $title, ':description' => $description, ':price' => $price, ':img' => $image, ':id' => $id));
    }
}

function deleteDataByID( $id ) {
    global $conn;
    $sql = "DELETE FROM products WHERE id = :id";
     if ($stmt = $conn->prepare($sql)) {
        $stmt->execute(array(':id' => $id));
    }
}

function validateInsertData( $title, $price, $description) {
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
        'checkOut' => 'Check Out',
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

function uploadImage(){
    $target = "img/";
    $messageError='';
    $target = $target . basename( $_FILES['Filename']['name']);
    $Filename=basename( $_FILES['Filename']['name']);

    $errorMsg = validateInsertData( $_POST['title'], $_POST['price'], $_POST['description']);
    
    if ($errorMsg != '') {
        $messageError .= $errorMsg;
    }
    
    if (!move_uploaded_file($_FILES['Filename']['tmp_name'], $target)) {
       $messageError .= "Sorry, there was a problem uploading your file.";
    } 
    
    return $messageError;
}