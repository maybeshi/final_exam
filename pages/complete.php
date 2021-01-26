<?php
require_once "database.php";
require_once "Product.php";
?>
<?php

$action = $_POST["action"];
$title = "";

session_start();
$product = $_SESSION["product"];

if($action === "entry"){
	try{
		$pdo = connectDB();
		$sql = "insert into product(name, price, category, detail) values (:name, :price, :category, :detail)";
		$params = [];
		$params[":name"] = $product->getName();
		$params[":price"] = $product->getPrice();
		$params[":category"] = $product->getCategory();
		$params[":detail"] = $product->getDetail();
		$pstmt = $pdo->prepare($sql);
		$pstmt->execute($params);
	} catch (PDOException $e){
		echo $e->getMessage();
		die;
	} finally {
		unset($pstmt);
		unset($pdo);
	}
	$title = "新規商品の追加";
} else if ($action === "update") {
	try{
		$pdo = connectDB();
		$sql = "update product set name = :name, price = :price, category = :category, detail = :detail where id = :id";
		$params = [];
		$params[":id"] = $product->getId();
		$params[":name"] = $product->getName();
		$params[":price"] = $product->getPrice();
		$params[":category"] = $product->getCategory();
		$params[":detail"] = $product->getDetail();
		$pstmt = $pdo->prepare($sql);
		$pstmt->execute($params);
	} catch (PDOException $e){
		echo $e->getMessage();
		die;
	} finally {
		unset($pstmt);
		unset($pdo);
	}
	$title = "ID{$product->getId()}の商品の更新";
}else if($action === "delete"){
	try{
		$pdo = connectDB();
		$sql = "delete from product where id = :id";
		$params = [];
		$params[":id"] = $product->getId();
		$pstmt = $pdo->prepare($sql);
		$pstmt->execute($params);
	} catch (PDOException $e){
		echo $e->getMessage();
		die;
	} finally {
		unset($pstmt);
		unset($pdo);
	}
	$title = "ID{$product->getId()}の商品の削除";
}
unset($_SESSION["product"]);
unset($_SESSION);
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
<main id="complete">
	<h2>商品の完了</h2>
	<p><?= $title ?>処理を完了しました。</p>
	<p><a href="top.php">トップページに戻る</a></p>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>