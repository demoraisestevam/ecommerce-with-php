<?php 
session_start();
require_once("vendor/autoload.php");

// Config
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

// Run Application
$app->run();

 ?>