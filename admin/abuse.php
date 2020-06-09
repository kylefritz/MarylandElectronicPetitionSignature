<?PHP 
include_once('../slack.php');
include_once('security.php');
include_once('/var/www/secure.php'); //outside webserver
include_once('functions.php');
if ($_COOKIE['level'] == 'user'){
  slack_general('ADMIN: Redirect User Home ('.$_COOKIE['name'].') ('.$_COOKIE['level'].')','md-petition');
  header('Location: user_home.php');
}
if (isset($_GET['clear_php_session_id'])){
  $id = $_GET['clear_php_session_id'];
  $petition->query("update presign set presign_status = 'DONE' where php_session_id = '$id' ");
  header('Location: abuse.php');
}
if ($_COOKIE['level'] == 'manager'){
  slack_general('ADMIN: Redirect Manager Home ('.$_COOKIE['name'].') ('.$_COOKIE['level'].')','md-petition');
  header('Location: manager_home.php');
}
if (isset($_GET['flag_invalid_signature'])){
  $id = $_GET['flag_invalid_signature'];
  $petition->query("update signatures set signature_status = 'flag_invalid_signature' where id = '$id' ");
  header('Location: abuse.php');
}
if (isset($_GET['flag_duplicate'])){
  $id = $_GET['flag_duplicate'];
  $petition->query("update signatures set signature_status = 'flag_duplicate' where id = '$id' ");
  header('Location: abuse.php');
}
if (isset($_GET['flag_ip_address'])){
  $ip = $_GET['flag_ip_address'];
  $petition->query("update signatures set signature_status = 'flag_ip_address' where ip_address = '$ip' ");
  header('Location: abuse.php');
}
if (isset($_GET['resign_requested'])){
  $id = $_GET['resign_requested'];
  $petition->query("update signatures set signature_status = 'resign_requested' where id =  '$id' ");
  header('Location: abuse.php');
}
if (isset($_GET['bot'])){
  $id = $_GET['bot'];
  $petition->query("update signatures set signature_status = 'bot' where id =  '$id' ");
  header('Location: abuse.php');
}
if (isset($_GET['flag_VTRID'])){
  $VTRID = $_GET['flag_VTRID'];
  $petition->query("update signatures set signature_status = 'flag_VTRID' where VTRID = '$VTRID' ");
  header('Location: abuse.php');
}
if (isset($_GET['flag_phone'])){
  $flag_phone = $_GET['flag_phone'];
  $petition->query("update signatures set signature_status = 'flag_phone' where contact_phone = '$flag_phone' ");
  header('Location: abuse.php');
}
include_once('header.php');
if (isset($_GET['ip_address'])){ 
  $ip = $_GET['ip_address']; 
  echo "<h1>Review $ip</h1><table width='100%' border='1' cellpadding='5' cellspacing='5'>";    
  $q = "SELECT * FROM  signatures where ip_address = '$ip' order by signature_status desc ";
  $r = $petition->query($q);
  while($d = mysqli_fetch_array($r)){
    $color = 'white';
    $pos = strpos($d['date_time_signed'], date('Y-m-d'));
    if ($pos !== false) {
        $color= 'yellow';
    } 
    echo "<tr style='background-color:$color;'>
      <td><b>$d[date_time_signed]</b></td>
      <td><a href='?VTRID=$d[VTRID]'>$d[VTRID]</a></td>
      <td>".id2petition($d['petition_id'])."</td>
      <td>$d[signed_name_as]</td>
      <td>$d[signed_name_as_circulator]</td>
      <td>$d[contact_phone]</td>
      <td>$d[signature_status]</td>
      <td>$d[printed_status]</td>
      <td><a href='?flag_invalid_signature=$d[id]'>flag invalid signature</a></td>
      <td><a href='?flag_VTRID=$d[VTRID]'>flag VTRID</a></td>
      <td><a href='?flag_ip_address=$d[ip_address]'>flag ip address</a></td>
      <td><a href='?flag_duplicate=$d[id]'>flag duplicate</a></td>
      <td><a href='?flag_phone=$d[contact_phone]'>contact phone</a></td>
      <td><a href='?resign_requested=$d[id]'>resign requested</a></td>
      <td><a href='?bot=$d[id]'>bot</a></td>
    </tr>"; 
  }
  echo "</table>";
}elseif(isset($_GET['php_session_id'])){ 
  $php_session_id = $_GET['php_session_id']; 
  echo "<h1>Review $php_session_id</h1><table width='100%' border='1' cellpadding='5' cellspacing='5'>";    
  $q = "SELECT * FROM presign where php_session_id = '$php_session_id' order by id desc ";
  $r = $petition->query($q);
  while($d = mysqli_fetch_array($r)){
    $color = 'white';
    $test = date('Y-m-d',strtotime($d['action_on']));
    $pos = strpos($test, date('Y-m-d'));
    if ($pos !== false) {
        $color= 'yellow';
    } 
    echo "<tr style='background-color:$color;'>
      <td style='white-space:pre;'><b>$d[action_on]</b></td>
      <td style='white-space:pre;'>$d[php_page]</td>
      <td style='white-space:pre;'>".id2petition($d['petition'])."</td>
      <td style='white-space:pre;'>$d[invite]</td>
      <td style='white-space:pre;'>$d[name]</td>
      <td style='white-space:pre;'>$d[email_for_follow_up]</td>
      <td style='white-space:pre;'>$d[phone_for_validation]</td>
      <td style='white-space:pre;'>$d[presign_status]</td>
      <td style='white-space:pre;'>$d[ip_address]</td>
      <td style='white-space:pre;'>$d[browser_string]</td>
    </tr>"; 
  }
  echo "</table><a href='?clear_php_session_id=$d[php_session_id]'>CLEAR $d[php_session_id]</a>";
}elseif (isset($_GET['VTRID'])){ 
  $VTRID = $_GET['VTRID'];
  echo "<h1>Review $VTRID</h1><table width='100%' border='1' cellpadding='5' cellspacing='5'>";   
  $q = "SELECT * FROM  signatures where VTRID = '$VTRID' and signature_status <> 'deleted' order by petition_id, id DESC ";
  $r = $petition->query($q);
  while($d = mysqli_fetch_array($r)){
    $color = 'white';
    $pos = strpos($d['date_time_signed'], date('Y-m-d'));
    if ($pos !== false) {
        $color= 'yellow';
    } 
    echo "<tr style='background-color:$color;'>
          <td><b>$d[date_time_signed]</b></td>
          <td><a href='?ip_address=$d[ip_address]'>$d[ip_address]</a></td>
          <td>".id2petition($d['petition_id'])."</td>
          <td>$d[signed_name_as]</td>
          <td>$d[signed_name_as_circulator]</td>
          <td>$d[contact_phone]</td>
          <td>$d[signature_status]</td>
          <td>$d[printed_status]</td>
          <td><a href='?flag_invalid_signature=$d[id]'>flag invalid signature</a></td>
          <td><a href='?flag_VTRID=$d[VTRID]'>flag VTRID</a></td>
          <td><a href='?flag_ip_address=$d[ip_address]'>flag ip address</a></td>
          <td><a href='?flag_duplicate=$d[id]'>flag duplicate</a></td>
          <td><a href='?flag_phone=$d[contact_phone]'>contact phone</a></td>
          <td><a href='?resign_requested=$d[id]'>resign requested</a></td>
          <td><a href='?bot=$d[id]'>bot</a></td>
        </tr>"; 
  }
  echo "</table>";
}
?>

<h1>Abuses</h1>

<table><tr>

  <td valign="top">
<h2>IP Address</h2>
<div>Watch for duplicates.</div><ol>
<?PHP
$q="SELECT ip_address, petition_id,VTRID, COUNT(*) as count FROM signatures where signature_status = 'verified' group by ip_address, petition_id, VTRID";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){
  if ($d['count'] > 1){
    echo "<li><a href='?ip_address=$d[ip_address]'>$d[ip_address]</a> <a target='_Blank' href='https://ipinfo.io/$d[ip_address]'>IP INFO</a> <a href='?VTRID=$d[VTRID]'>$d[VTRID]</a> $d[petition_id] <b>$d[count]</b> $d[signed_name_as]</li>"; 
  }
}
?></ol>
  </td><td valign="top">
<h2>VTRID</h2>
<div>Watch for duplicates.</div><ol>
<?PHP
$q="SELECT VTRID, petition_id, COUNT(*) as count FROM signatures where signature_status = 'verified' group by VTRID, petition_id";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
  if ($d['count'] > 1){
    echo "<li><a href='?VTRID=$d[VTRID]'>$d[VTRID]</a> $d[petition_id] <b>$d[count]</b> $d[signed_name_as]</li>"; 
  }
}
  ?></ol>
  </td></tr><tr><td valign="top">
<h2>VTRID</h2>
<div>Watch for 0</div><ol>
<?PHP
$q="SELECT * FROM signatures where VTRID = '0' and signature_status <> 'bot' and signature_status <> 'flag_invalid_signature' and signature_status <> 'resign_requested'";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
    echo "<li>$d[date_time_signed] <a href='?ip_address=$d[ip_address]'>$d[ip_address]</a> <a target='_Blank' href='https://ipinfo.io/$d[ip_address]'>IP INFO</a> $d[petition_id] $d[signed_name_as]</li>"; 
}
?></ol>
 </td><td valign="top">
<h2>petition_id</h2>
<div>Watch for 0</div><ol>
<?PHP
$q="SELECT * FROM signatures where (petition_id = '0' or petition_id = '') and signature_status <> 'bot' and signature_status <> 'flag_invalid_signature' and signature_status <> 'resign_requested'";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
    echo "<li>$d[date_time_signed] <a href='?ip_address=$d[ip_address]'>$d[ip_address]</a> <a target='_Blank' href='https://ipinfo.io/$d[ip_address]'>IP INFO</a>  $d[petition_id] $d[signed_name_as]</li>"; 
}
?></ol>
  </td></tr><tr><td valign="top">
<h2>resign_requested</h2>
<div>These are most likely from early bugs</div><ol>
<?PHP
$q="SELECT * FROM signatures where signature_status = 'resign_requested' order by ip_address";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
    echo "<li>$d[date_time_signed] <a href='?ip_address=$d[ip_address]'>$d[ip_address]</a> <a target='_Blank' href='https://ipinfo.io/$d[ip_address]'>IP INFO</a> <a href='?VTRID=$d[VTRID]'>$d[VTRID]</a> $d[petition_id] $d[signed_name_as]</li>"; 
}
?></ol>
  </td>
  
  <td valign="top">
<h2>bots</h2>
<div>These are bots on the site.</div><ol>
<?PHP
$q="SELECT * FROM signatures where signature_status = 'bot' order by ip_address";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
    echo "<li>$d[date_time_signed] <a href='?ip_address=$d[ip_address]'>$d[ip_address]</a> <a target='_Blank' href='https://ipinfo.io/$d[ip_address]'>IP INFO</a> <a href='?VTRID=$d[VTRID]'>$d[VTRID]</a> $d[petition_id] $d[signed_name_as]</li>"; 
}
?></ol>
  </td>


</tr>
<tr>
<td valign="top">
<h2>Pre-Sign</h2>
<div>Follow up requested - never signed.</div><ol>
<?PHP
$q="SELECT distinct email_for_follow_up, php_session_id, name, petition, invite FROM presign where presign_status = 'NEW' and email_for_follow_up <> '' order by id desc";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
    echo "<li><a href='?php_session_id=$d[php_session_id]'>$d[name] $d[email_for_follow_up] ($d[petition])</a></li>"; 
}
?></ol>
  </td>
<td valign="top">
<h2>Signature</h2>
<div>Last 10</div><ol>
<?PHP
$q="SELECT * FROM signatures where signature_status = 'verified' order by id desc limit 0, 10";
$r = $petition->query($q);
while($d = mysqli_fetch_array($r)){ 
    echo "<li>$d[date_time_signed] ".id2petition($d['petition_id'])." $d[signed_name_as]</li>"; 
}
?></ol>
  </td>

  </tr>
</table>


<?PHP
include_once('footer.php');
?>
