<?PHP 
include_once('security.php');
include_once('header.php');
?>
<meta http-equiv="refresh" content="60">
  <?PHP
  $expected = 4313592; // old data file...
  if ($result = $petition->query("SELECT VTRID FROM VoterList")) {
    $row_cnt = $result->num_rows;
    $c = $row_cnt / $expected;
    $p = number_format($c,2)*100;
    echo '<h1>'.date('r').'</h1><h1>'.number_format($row_cnt).' out of '.number_format($expected).' '.$p.'% done</h1><title>'.$p.'% loaded</title>';
} 
include_once('footer.php');  ?>
