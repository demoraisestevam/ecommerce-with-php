<?php 
session_start();
require_once("vendor/autoload.php");

// Config
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

$app = new Slim();

$app->config('debug', true);

// Content Important
// Strore
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

// Admin
$app->get('/admin', function() {
    
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

// Login Admin (GET)
$app->get('/admin/login', function() {
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});
// Login Admin (POST)
$app->post('/admin/login', function() {

	User::Login($_POST["login"], $_POST["password"]);

	header("location: /admin");
	exit;

});
// Logout
$app->get('/admin/logout', function() {

	User::logout();

	header("location: /admin/login");
	exit;

});

// List Users
$app->get('/admin/users', function() {

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));

});

// Delete Save in DB
$app->get('/admin/users/:iduser/delete', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

// Create Users
$app->get('/admin/users/create', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

// Update User
$app->get('/admin/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

// Create Save in DB
$app->post('/admin/users/create', function() {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

// Update Save in DB
$app->post('/admin/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;


});

$app->get("/admin/forgot", function() {
 	
 	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

 	$page->setTpl("forgot");

});

$app->post("/admin/forgot", function(){

 	$user = User::getForgot($_POST["email"]);

 	header("Location: /admin/forgot/sent");

	exit;

});

$app->get("/admin/forgot/sent", function(){

 	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

 	$page->setTpl("forgot-sent");	
 	
});

$app->get("/admin/forgot/reset", function() {

 	$user = User::validForgotDecrypt($_GET["code"]);

 	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

 	$page->setTpl("forgot-reset", array(
 		"name"=>$user["desperson"],
 		"code"=>$_GET["code"]
 	));

});

$app->post("/admin/forgot/reset", function() {

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

 	$page->setTpl("forgot-reset-success");

});

$app->get("/admin/categories", function() {

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

 	$page->setTpl("categories", array(
 		"categories"=>$categories
 	));

});

$app->get("/admin/categories/create", function() {

	User::verifyLogin();

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");

});


$app->post("/admin/categories/create", function() {

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});

$app->get("/admin/categories/:idcategory/delete", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");
	exit;

});

$app->get("/admin/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", array(
		'category'=>$category->getValues()
	));

});

$app->post("/admin/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});

$app->get("/category/:idcategory", function($idcategory) {

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		'category'=>$category->getValues(),
		'products'=>[]
	));

});

// Run Application
$app->run();

 ?>