<?php 

use \Hcode\Page;
use \Hcode\Model\Product;

// Strore
$app->get('/', function() {
    
	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", array(
		"products"=>Product::checkList($products)
	));

});

// Categories Store
$app->get("/category/:idcategory", function($idcategory) {

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		'category'=>$category->getValues(),
		'products'=>[]
	));

});

 ?>