<?php
# Script: add.class.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "add.class.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $DEBUG = 0;

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Classification</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php

if (isset($_POST['class'])) {
  $formVars['cls_name']        = clean($_POST['class'],       70);
  $formVars['cls_template']    = clean($_POST['template'],    10);
  $formVars['cls_project']     = clean($_POST['project'],     10);
  $formVars['cls_title']       = clean($_POST['title'],      100);
  $formVars['cls_help']        = clean($_POST['help'],       100);

  logaccess($db, $_SESSION['username'], "add.class.php", "Adding class: " . $formVars['cls_name']);

  $q_string = "insert into class set " . 
    "cls_id       = NULL, " . 
    "cls_name     = \"" . $formVars['cls_name']     . "\"," . 
    "cls_template =   " . $formVars['cls_template'] . "," . 
    "cls_project  =   " . $formVars['cls_project']  . "," . 
    "cls_title    = \"" . $formVars['cls_title']    . "\"," . 
    "cls_help     = \"" . $formVars['cls_help']     . "\" ";

  mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

}

?>

<form action="" method="POST">

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content button"><input type="submit" value="Add Classification"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=5>Classification Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Classification: <input type="text" name="class" size=30></td>
  <td class="ui-widget-content">Template: <input type="text" name="template" size=5></td>
  <td class="ui-widget-content">Project: <input type="text" name="project" size=5></td>
  <td class="ui-widget-content">Title: <input type="text" name="title" size=20></td>
  <td class="ui-widget-content">Help: <input type="text" name="help" size=30></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=6>Classification Listing</th>
</tr>
<tr>
  <th class="ui-state-default">ID</th>
  <th class="ui-state-default">Classification</th>
  <th class="ui-state-default">Template #</th>
  <th class="ui-state-default">Project</th>
  <th class="ui-state-default">Title</th>
  <th class="ui-state-default">Help</th>
</tr>
<?php

$q_string  = "select cls_id,cls_name,cls_template,cls_project,cls_title,cls_help ";
$q_string .= "from class ";
$q_string .= "order by cls_id";
$q_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
while ($a_class = mysqli_fetch_array($q_class)) {

  print "<tr>\n";
  print "  <td class=\"ui-widget-content\">" . $a_class['cls_id'] . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysqli_real_escape_string($db, $a_class['cls_name']) . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysqli_real_escape_string($db, $a_class['cls_template']) . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysqli_real_escape_string($db, $a_class['cls_project']) . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysqli_real_escape_string($db, $a_class['cls_title']) . "</td>\n";
  print "  <td class=\"ui-widget-content\">" . mysqli_real_escape_string($db, $a_class['cls_help']) . "</td>\n";
  print "</tr>\n";
  $count++;

}

if ($count == 0) {
  print "<tr>\n";
  print "  <td class=\"ui-widget-content\" colspan=6>No records found.</td>\n";
  print "</tr>\n";
}

mysqli_free_result($q_class);

?>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
