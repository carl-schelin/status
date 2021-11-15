<?php
# Script: add.progress.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($AL_User);

  $package = "add.progress.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Progress</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php

if (isset($_POST['progress'])) {
  $formVars['pro_name'] = clean($_POST['progress'], 70);
  $formVars['pro_desc'] = clean($_POST['desc'], 70);

  logaccess($_SESSION['username'], "add.progress.php", "Adding progress: " . $formVars['pro_name']);

  $q_string = "insert into progress set " . 
    "pro_id   =   " . " NULL"               . ", " . 
    "pro_name = \"" . $formVars['pro_name'] . "\", " . 
    "pro_desc = \"" . $formVars['pro_desc'] . "\"";

  mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

}

?>

<form action="" method="POST">

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content button"><input type="submit" value="Add Progress"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default">Progress Form</th>
</tr>
<tr>
  <td class="ui-widget-content">New Progress ID: <input type="text" name="progress" size=70 length=70></td>
</tr>
<tr>
  <td class="ui-widget-content">Description: <input type="text" name="desc" size=70 length=70></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=3>Progress Listing</th>
</tr>
<tr>
  <th class="ui-state-default">ID</th>
  <th class="ui-state-default">Description</th>
  <th class="ui-state-default">Help</th>
</tr>
<?php

$q_string  = "select pro_id,pro_name,pro_desc ";
$q_string .= "from progress ";
$q_string .= "order by pro_id";
$q_progress = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
while ($a_progress = mysql_fetch_array($q_progress)) {

  print "<tr>\n";
  print "  <td class=\"ui-widget-content\">" . $a_progress['pro_id'] . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysql_real_escape_string($a_progress['pro_name']) . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysql_real_escape_string($a_progress['pro_desc']) . "</td>\n";
  print "</tr>\n";
  $count++;

}

if ($count == 0) {
  print "<tr>\n";
  print "  <td class=\"ui-widget-content\">No records found.</td>\n";
  print "</tr>\n";
}

mysql_free_result($q_progress);

?>
</table>

</form>
</center>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
