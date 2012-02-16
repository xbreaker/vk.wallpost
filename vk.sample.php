<?php 
/**
* vk.wallpost sample code
*
* @package vk.wallpost
* @author Ayrat Belyaev <xbreaker@gmail.com>
* @copyright (c) 2011 xbreaker
* @license http://creativecommons.org/licenses/by-sa/3.0/legalcode
*/
if ( !class_exists( 'vk_wallpost' ) )
		require('vk.wallpost.php' );
  
$vk = new vk_wallpost("user_login", "user_pass", "http://vk.com/{wall_url}", "{target_wall_id}");
//настройка прокси
$vk->proxyAddr="host:port";
$vk->proxyAuth="login:pass";
$vk->useProxy=false;
//отправка сообщения
$vk->postMessage("http://xbreaker.ru", "Test message");
?>