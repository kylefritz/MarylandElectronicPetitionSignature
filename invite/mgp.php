<?PHP 
setcookie("invite", "mgp"); // we use this later
include_once('header.php');
slack_general('MGP Home Page Loaded ('.$_COOKIE['invite'].')','md-petition');
$q = "select * from petitions where petition_id = '1'";
$r = $petition->query($q);
$d = mysqli_fetch_array($r);
?>
<script>document.title = "<?PHP echo $d['tab_name'];?>";</script>
<div class='row'>
  <div class='col-sm-10' style='text-align:center;'><h1><?PHP echo $d['text_title'];?></h1><h2><?PHP echo $d['text_block'];?></h2></div>
 </div> 
<div class='row'>
  <div class='col-sm-10' style='text-align:center;'><button type="button" class="btn btn-success btn-lg btn-block" onclick="window.location.href='enter_information.php'">SIGN <?PHP echo $d2['petition_name'];?></button></div>
 </div> 
<div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
  
 <center>
 <table border="1" cellpadding="2" cellspacing="0">
 <?PHP
 $q2 = "SELECT * FROM petitions where web_short_name = 'mgp'";
 $r2 = $petition->query($q2);
 while($d2 = mysqli_fetch_array($r2)){
  echo "<tr>
  <td align='center'><small>$d2[petition_name]<small></td>
  <td><div class=\"fb-share-button\" 
     data-href=\"http://md-petition.com/invite/mgp.php\" 
     data-layout=\"box_count\" data-size=\"large\">
   </div></td>
   <td><input type='text' size='50' value='http://md-petition.com/invite/mgp.php' id='$d2[web_short_name]'><button onclick='myFunction(\"$d2[web_short_name]\")'>Copy Link</button></td>
   </tr>";
 }
  ?>
 </table>
</center>
 
 
 
<?PHP
$copy = '&copy; 2020 Patrick McGuire';
if ($_COOKIE['invite'] != ''){
 $copy = '&copy; 2020 Patrick McGuire - '.strtoupper($_COOKIE['invite']); 
}  
?>
<div class='row'>
 <div class='col-sm-10' style='text-align:center;'><?PHP echo $copy;?></div>
</div>