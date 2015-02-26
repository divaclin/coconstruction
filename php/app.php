<?php
session_start();
class App{
	const APP_NAME='';
	const TITLE='Co-construction'; 
	const DSN='mysql:host=localhost;port=3306;dbname=city;charset=utf8';
	const DB_USERNAME='root';
	const DB_PASSWORD='ConstructCity';
	//port=8889 3306
	//pwd=root  Adms@0930766151
	static $dbn;

	static function HOST(){
		$url='http://';
		$url.='140.119.134.100/co-construction';//$_SERVER['SERVER_NAME'].'8888';
		$url.='';
		return $url;
		}	
	static function db_connect(){
		try{ self::$dbn=new PDO(self::DSN,self::DB_USERNAME,self::DB_PASSWORD); }
		catch(PDOException $e){ echo 'Connection failed: ' . $e->getMessage(),exit(); }
		}
	static function header_location($url){
		header('location: '.$url);
		exit();
		}
	static function error_index(){
		if(!isset($_GET['errorCode'])){	exit(); }
		$_html_p='<p class="warning">';
		if(403==$_GET['errorCode']){ $_html_p.='帳號或密碼錯誤，請重新輸入'; }
		$_html_p.='</p>';
		echo $_html_p;
		}
	static function html_xl_nav(){
		}
	}
	function islogin(){
		if(!isset($_SESSION['islogin']) || true!=$_SESSION['islogin']){
			$url=App::HOST().'index.php';
			App::header_location($url);
			}	
		}	
?>