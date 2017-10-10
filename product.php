<?php 
    require_once 'common.php';
    if (!isset($_SESSION['logged'])) {
        die();  
    }

    $confirm = '';
    $listVal[0] = array('title'=>'', 'price'=>'', 'description'=>'', 'img'=>'');

    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $confirm = uploadImage();

        if ($confirm == '') {
            $path = './img/' . basename( $_FILES['Filename']['name']);
            insertData($title, $price, $description, $path);
            $confirm = 'Data succesfull inserted!';
        }
             
    }
  
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $listVal = selectDataById($id);
    }

    if (isset($_GET['action']) && !isset($_GET['id'])) {
        
        $listVal = array_replace($listVal, $_POST);
        $id = intval($_POST['id']);
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $confirm = validateInsertData( $title, $price, $description);
        
        $confirm .= uploadImage($id);

        if ($confirm == '') {
            $path = './img/' .$id. basename( $_FILES['Filename']['name']);
            updateDataByID($id, $title, $price, $description, $path);
            $confirm = 'Data succesfull updated!';
        }
    }
?>    
<html>
    <head>
    <style type="text/css">
        .error {color:red;}
        .btnStyle {display:inline; margin:25px; width:200px; height:60px;}
    </style>
    </head>
    <body>
        <h3 class="error"><?= isset($confirm) ? $confirm : '' ?></h3>
        <form enctype="multipart/form-data" method="post" action="product.php?action=<?php echo utf8_decode(urldecode(isset($_GET['action']) ? $_GET['action'] : 'add')) ?>">
            <table>
            <input type="hidden" name="id" value="<?= (isset($_GET['id']) ? $_GET['id'] : '') ?>">
                <tr>
                    <th><?php echo translate('title');?></th>
                    <td><input type="text" name="title" value="<?php echo protect(isset($_POST['title']) ? $_POST['title'] : $listVal[0]['title'])?>"></td>
                </tr>
                <tr>
                    <th><?php echo translate('price');?></th>
                    <td><input type="number" name="price" value="<?php echo protect(isset($_POST['price']) ? $_POST['price'] : $listVal[0]['price'])?>"></td>
                </tr>
                <tr>
                    <th><?php echo translate('description');?></th>
                    <td><textarea name="description" value="<?php echo protect(isset($_POST['description']) ? $_POST['description'] : $listVal[0]['description'])?>" ><?php echo protect(isset($_POST['description']) ? $_POST['description'] : $listVal[0]['description'])?></textarea></td>
                </tr>
                <tr>
                    <th><?php echo translate('image');?></th>
                    <td><input type="file" name="Filename" accept="image/*" data-buttonText="<?php echo protect(isset($_POST['img']) ? $_POST['img'] : $listVal[0]['img'])?>"></td>
                </tr>
            </table>
            <a class="btnStyle" href="index.php"><?= translate('view') ?></a>
            <a class="btnStyle" href="products.php"><?= translate('addProducts') ?></a>
            <input type="submit" value="<?= translate('save') ?>">
        </form>
    </body>
</html>