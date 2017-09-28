	<html>
	<head>
	<style type="text/css">
	.btnStyle {display:inline; margin:25px; width:200px; height:60px;}
	</style>
	</head>
	<body>
	<table>
	<tr><form method="post" name="emailform" action="cart.php"></tr>
	<tr>
	<th>Title:</th>
	<td><input type="text" name="name"></td>
	</tr>
	<tr>
	<th>Price:</th>
	<td><input type="number" name="price"></td>
	</tr>
	<tr>
	<th>Description:</th>
	<td><textarea name="Description"></textarea></td>
	</tr>
	<tr>
	<th>Image:</th>
	<td><input type="file" name="image" accept="image/" value="Browse"></td>
	</tr>
	</table>
	<a class="btnStyle" href="index.php"><?= translate('view') ?></a>
	<a class="btnStyle" href="products.php"><?= translate('addProducts') ?></a>
	<input type="submit" value="<?= translate('save') ?>">
	</form>
	</body>
	</html>