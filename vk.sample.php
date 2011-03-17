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
  
function vkPost($url='http://mysite.ru/', $message='message', $title='title', $descr='descr', $type='share')  
{     
  $vkfu = new vk_wallpost();
  $d = $vkfu->_login();
  $o = 'desff65fdscx.txt'; //cookies
  if(($d['l'] != '')&&($d['p'] != '')) {
	$h = $vkfu->_hash($o, 'http://vkontakte.ru/mypage', true);
		
	if($h['my_id'] == 0) 
	{
	  $vkfu->_auth($o, $d, true);
	  $h = $vkfu->_hash($o, 'http://vkontakte.ru/mypage', true);
	} 

	if($h['my_id'] != 0) 
	{
	  $r = $vkfu->_status(false, $o, $h['post_hash'], $url, $message, $title, $descr, false, $h['user_id'], $type, true);
	  $c = preg_match_all('/page_wall_count_all/smi',$r,$f);
	  if( $c == 0 ) 
	  {
	    return false;
	  } 
	  else 
	  {
	    return true;
	  }
	}
  } 
  else 
  {
    return false;
  }
}
?>