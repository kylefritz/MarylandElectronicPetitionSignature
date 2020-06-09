<?PHP
session_start();
// this will overlay our current system
function presign(){
  global $petition;
  $id     = session_id();
  $page   = $petition->real_escape_string($_SERVER['REQUEST_URI']);
  $email  = $petition->real_escape_string($_COOKIE['email']);
  $name   = $petition->real_escape_string($_COOKIE['pNAME']);
  $phone  = $petition->real_escape_string($_COOKIE['pPHONE']);
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    	$ip = $_SERVER['REMOTE_ADDR'];
	}
  $petition_name   = $petition->real_escape_string($_COOKIE['invite']).'-'.$petition->real_escape_string($_COOKIE['pID']);
  $ip = $petition->real_escape_string($ip);
  $browser_string = $petition->real_escape_string($_SERVER['HTTP_USER_AGENT']);
  $now = $petition->real_escape_string(date('r'));
  $petition->query("insert into presign (petition,php_session_id,php_page,name,email_for_follow_up,phone_for_validation,ip_address,browser_string,action_on) 
  values ('$petition_name','$id','$page','$name','$email','$phone','$ip','$browser_string','$now') "); 
}
?>
