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
  
$vk = new vk_wallpost("login", "pass", "http://vkontakte.ru/club9999", "-9999");
/////////////////////////////////////////^ адрес стены (юзера или группы) ^ id стены группы с минусом, пользователя- без него
//настройка прокси
$vk->proxyAddr="server:port";
$vk->proxyAuth="user:pass";
$vk->useProxy=false;
//отправка сообщения
$vk->postMessage("habr.ru", "Test message");
//прикрепление изображения
$vk->postPicture("habr.ru", "http://some.cool.image.jpg");
?>