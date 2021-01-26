<?php
require_once "database.php";
require_once "Product.php";
?>
<?php
$action = $_REQUEST["action"];
$product = null;
$title = "";

if($action === "entry" or $action === "update"){
	isset($_POST["id"])? $id = $_POST["id"]: $id = 0;
	isset($_POST["category"])? $category = $_POST["category"]: $category = "";
	isset($_POST["name"])? $name = $_POST["name"]: $name = "";
	isset($_POST["price"])? $price = $_POST["price"]: $price = 0;
	isset($_POST["detail"])? $detail = $_POST["detail"]: $detail = "";
	$product = new Product($id, $name, $price, $category, $detail);
}else if($action === "delete"){
	isset($_GET["id"])? $id = $_GET["id"]: $id = 0;
	try{
	$pdo = connectDB();
	$sql = "select * from product where id = ?";
	$pstmt = $pdo->prepare($sql);
	$pstmt->bindValue(1, $id);
	$pstmt->execute();
	$records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
	$product = null;
	foreach($records as $record){
		$id = $record["id"];
		$name = $record["name"];
		$price = $record["price"];
		$category = $record["category"];
		$detail = $record["detail"];
		$product = new Product($id, $name, $price, $category, $detail);
	}
}catch (PDOException $e){
	echo $e->getMessage();
	die;
} finally {
	unset($pstmt);
	unset($pdo);
}
}
session_start();
$_SESSION["product"] = $product;
if($action === "entry"){$title = "登録";}
if($action === "update"){$title = "更新";}
if($action === "delete"){$title = "削除";}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>商品データベース</title>
	<link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<header>
	<h1>商品データベース</h1>
</header>
<main id="confirm">
	<h2>商品の確認</h2>
	<p>以下の情報で<?= $title ?>します。</p>
	<?php if(!is_null($product)): ?>
	<table class="form">
		<?php if($action === "update" or $action === "delete"){?>
		<tr>
			<th>商品ID</th>
			<td><?= $product->getId() ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th>カテゴリ</th>
			<td><?= $product->getCategory() ?></td>
		</tr>
		<tr>
			<th>商品名</th>
			<td><?= $product->getName() ?></td>
		</tr>
		<tr>
			<th>価格</th>
			<td><?= $product->getPrice() ?></td>
		</tr>
		<tr>
			<th>商品説明</th>
			<td><?= $product->getDetail() ?></td>
		</tr>
		<tr class="buttons">
			<td colspan="2">
				<form name="inputs">
					<button formaction="complete.php" formmethod="post" name="action" value="<?= $action ?>"><?= $title ?>する</button>
				</form>
			</td>
		</tr>
	</table>
	<?php endif; ?>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>