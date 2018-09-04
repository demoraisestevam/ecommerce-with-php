<?php 

use \Hcode\Page;

// Strore
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

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