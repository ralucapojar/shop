<?php 
    session_start();
    require_once 'common.php';


    $errorMsg = $confirm = '';
    $listVal = array('title'=>'', 'price'=>'', 'description'=>'', 'image'=>'');

    if( isset($_GET['action']) == 'update' && !isset($_GET['id'])) {
       $listVal = array_replace($listVal, $_POST);

        if(validateInsertData($errorMsg, $_POST['title'], $_POST['price'], $_POST['description'], $_POST['image'])){
            $confirm = 'The data was successfully added';      
        } else {
            $confirm = $errorMsg;
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
        <table>
            <tr><form method="post" action="product.php?action=<?php echo utf8_decode(urldecode(isset($_GET['action']) ? $_GET['action'] : '')) ?>"></tr>
            <tr>
                <th>Title:</th>
                <td><input type="text" name="title" value="<?php echo protect(isset($_POST['title']) ? $_POST['title'] : $listVal['title'])?>"></td>
            </tr>
            <tr>
                <th>Price:</th>
                <td><input type="number" name="price" value="<?php echo protect(isset($_POST['price']) ? $_POST['price'] : $listVal['price'])?>"></td>
            </tr>
            <tr>
                <th>Description:</th>
                <td><textarea name="description" value="<?php echo protect(isset($_POST['description']) ? $_POST['description'] : $listVal['description'])?>" ><?php echo protect(isset($_POST['description']) ? $_POST['description'] : $listVal['description'])?></textarea></td>
            </tr>
            <tr>
                <th>Image:</th>
                <td><input type="file" name="image" accept="image/" value="<?php echo protect(isset($_POST['image']) ? $_POST['image'] : $listVal['image'])?>"></td>
            </tr>
        </table>
        <a class="btnStyle" href="index.php"><?= translate('view') ?></a>
        <a class="btnStyle" href="products.php"><?= translate('addProducts') ?></a>
        <input type="submit" value="<?= translate('save') ?>">
        </form>
    </body>
</html>