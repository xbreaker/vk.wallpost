<?php 

class vk_wallpost 
{
 
  function _login() 
  {
    return $a = array( 'l' => 'логин',
                       'p' => 'пароль');
  }
  
  function _proxy() 
  {
    return $p = array( 'a' => 'прокси:порт',
                       'p' => 'логин:пароль',
                       's' => 'https');
  } 
  
  function _curl_init($use_proxy = false, $headers = false, $cookies)
  {
    $c = curl_init($use_proxy); 
    $proxy = $this->_proxy();
    curl_setopt($c, CURLOPT_PROXY, $proxy['a']);
    curl_setopt($c, CURLOPT_PROXYUSERPWD, $proxy['p']);
    if( $proxy['s'] == 'socks4' ) 
    {
      curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
    } 
    elseif( $proxy['s'] == 'socks5' ) 
    {
      curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    } 
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1); 
    curl_setopt($c, CURLOPT_POST, 1);  
    curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13)');
    if($headers)
      curl_setopt($c, CURLOPT_HEADER, 1);  
    if($cookies)
      curl_setopt($c, CURLOPT_COOKIEJAR, $cookies);
      
    return $c;
  }
  
  function _curl_do($ch, $fu)
  {
	$result = curl_exec($ch);
	$eo = curl_errno($ch);
	$er = curl_error($ch);
	if($eo > 0) 
	{
	  echo('curl vk_wallpost::'.$fu.': '.$eo.': '.$er);
	}    
	curl_close($ch);
	
	return $result;
  }
  
  function _auth( $cookies, $data, $use_proxy = false ) 
  {
    $e = urlencode($data['l']);
    $p = urlencode($data['p']);
    
    $s = 'act=login&q=1&al_frame=1&expire=&captcha_sid=&captcha_key=&from_host=vkontakte.ru&email='.$e.'&pass='.$p;
    $this->_curl_init($use_proxy, false, $cookies);
    curl_setopt($c, CURLOPT_URL,'http://login.vk.com/?act=login'); 
    curl_setopt($c, CURLOPT_POSTFIELDS, $s);
    $this->_curl_do($c, '_auth');
  }
  
  function _hash($cookies, $url, $use_proxy = false) 
  {
    $this->_curl_init($use_proxy, false, $cookies);
    curl_setopt($c, CURLOPT_REFERER, 'http://vkontakte.ru/settings.php');
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookies); 
    curl_setopt($c, CURLOPT_URL, $url);   
    $r = $this->_curl_do($c, '_hash');
    preg_match_all('/"post_hash":"(\w+)"/i', $r, $f1);
    preg_match_all('/"user_id":(\d+),/i', $r, $f2);
    preg_match_all('/handlePageParams\(\{"id":(\d+),/i', $r, $f3);
    return $f = array(
           'post_hash' => $f1[1][0],
           'user_id'   => $f2[1][0],
           'my_id'     => $f3[1][0]);
  }
  
  function _photo($cookies, $url, $img, $use_proxy = false)
  {
    $u = urlencode($url);
    $i = urlencode($img);
    $this->_curl_init($use_proxy, false, $cookies);
    $q = 'act=a_photo&url='.$u.'&image='.$i.'extra=';
    curl_setopt($c, CURLOPT_POST, 1);  
    curl_setopt($c, CURLOPT_REFERER, 'http://vkontakte.ru/share.php');
    curl_setopt($c, CURLOPT_POSTFIELDS, $q);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookies);
    curl_setopt($c, CURLOPT_URL, 'http://vkontakte.ru/share.php');   
    $r = $this->_curl_do($c, '_photo');
    if(preg_match('/onUploadDone/i', $r, $o))
    {
      preg_match_all('/{"user_id":(\d+),"photo_id":(\d+)}/i', $r, $out);
      return $f = array(
                  'user_id'  => $out[1][0],
                  'photo_id' => $out[2][0]);
    }
    else
    {
      return false;
    }
  }
  
  function _status($page = false, $cookies, $hash, $url, $message, $title, $descr, $id, $type, $use_proxy = false) 
  {
    $u = urlencode($url);
    $m = urlencode($message);
    $t = urlencode($title);
    $d = urlencode($descr);
    if($page)
      $_prefix = 'public';
    else
      $_prefix = 'id';
      
    if( $type == 'share') 
    {
      $q = 'act=post&al=1&hash='.$hash.'&message='.$m.'&note_title=&official=&status_export=&to_id='.$id.'&type=all&media_type=share&url='.$u.'&title='.$t.'&description='.$d;
    } 
    elseif( $type == '') 
    {
      $q = 'act=post&al=1&hash='.$hash.'&message='.$m.'&note_title=&official=&status_export=&to_id='.$id.'&type=all';
    }  
    $this->_curl_init($use_proxy, false, $cookies);
    curl_setopt($c, CURLOPT_HTTPHEADER, array('X-Requested-With: XMLHttpRequest')); 
    curl_setopt($c, CURLOPT_POST, 1);  
    curl_setopt($c, CURLOPT_REFERER, 'http://vkontakte.ru/'.$_prefix.$id);
    curl_setopt($c, CURLOPT_POSTFIELDS, $q);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookies);
    curl_setopt($c, CURLOPT_TIMEOUT, 15); 
    curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($c, CURLOPT_URL, 'http://vkontakte.ru/al_wall.php');   
    $r = $this->_curl_do($c, '_status');

    return $r;
  }
}
?>