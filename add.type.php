<?php
# Script: add.type.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($AL_User);

  $package = "add.type.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Type</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php

if (isset($_POST['type'])) {

  $formVars['typ_name'] = clean($_POST['type'], 70);
  $formVars['typ_desc'] = clean($_POST['desc'], 70);

  logaccess($_SESSION['username'], "add.type.php", "Adding type: " . $formVars['typ_name']);

  $q_string  = "insert into type set ";
  $q_string .= "typ_id = NULL, typ_name = \"" . $formVars['typ_name'] . "\", typ_desc = \"" . $formVars['typ_desc'] . "\"";
  mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

}

?>

<form action="" method="POST">

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content button"><input type="submit" value="Add Type"></td>
</tr>
</table>

<table>
<tr>
  <th class="ui-state-default">Task Type Form</th>
</tr>
<tr>
  <td class="ui-widget-content">New Type: <input type="text" name="type" size=70 length=70></td>
</tr>
<tr>
  <td class="ui-widget-content">Description: <input type="text" name="desc" size=70 length=70></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=3>Task Type Listing</th>
</tr>
<tr>
  <th class="ui-state-default">ID</th>
  <th class="ui-state-default">Name</th>
  <th class="ui-state-default">Description</th>
</tr>
<?php

$q_string  = "select typ_id,typ_name,typ_desc ";
$q_string .= "from type ";
$q_string .= "order by typ_id";
$q_type = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
while ($a_type = mysql_fetch_array($q_type)) {

  print "<tr>\n";
  print "  <td class=\"ui-widget-content\">" . $a_type['typ_id'] . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysql_real_escape_string($a_type['typ_name']) . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysql_real_escape_string($a_type['typ_desc']) . "</td>\n";
  print "</tr>\n";
  $count++;

}

if ($count == 0) {
  print "<tr>\n";
  print "  <td class=\"ui-widget-content\" colspan=3>No records found.</td>\n";
  print "</tr>\n";
}

mysql_free_result($q_type);

?>
</table>

</form>
</center>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
