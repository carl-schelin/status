<?php
# Script: add.project.php
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

  $package = "add.project.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['group'] = clean($_GET['group'], 10);

  if ($formVars['group'] == 0) {
    $formVars['group'] = $_SESSION['group'];
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Project</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function clear_input(formfield) {

  if ( document.project.desc.value == "Brief Description" ) {
    formfield.value = "";
  }

  if ( document.project.code.value == "7884" ) {
    formfield.value = "";
  }

  if ( document.project.snow.value == "" ) {
    formfield.value = "";
  }

  if ( document.project.project.value == "As seen in iConnect" ) {
    formfield.value = "";
  }

  if ( document.project.task.value == "As seen in iConnect" ) {
    formfield.value = "";
  }
}

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php

if (isset($_POST['project'])) {

  $formVars['prj_name']  = clean($_POST['project'], 255);
  $formVars['prj_code']  = clean($_POST['code'], 10);
  $formVars['prj_snow']  = clean($_POST['snow'], 30);
  $formVars['prj_task']  = clean($_POST['task'], 30);
  $formVars['prj_desc']  = clean($_POST['desc'], 100);
  $formVars['prj_group'] = clean($_POST['group'], 10);

  logaccess($db, $_SESSION['username'], "add.project.php", "Adding project: " . $formVars['prj_name']);

  $q_string = "insert into project " . 
    "set prj_id = NULL, " . 
    "prj_name  = \"" . $formVars['prj_name']  . "\", " . 
    "prj_code  = "   . $formVars['prj_code']  . "," . 
    "prj_snow  = \"" . $formVars['prj_snow']  . "\", " .
    "prj_task  = \"" . $formVars['prj_task']  . "\", " .
    "prj_desc  = \"" . $formVars['prj_desc']  . "\", " .
    "prj_group = "   . $formVars['prj_group'];

  mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

}
?>

<form name="project" action="" method="POST">

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content button"><input type="submit" value="Add Project"><input type="hidden" name="group" value="<?php print $formVars['group']; ?>"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=6>Description For Status Reports</th>
</tr>
<tr>
  <td class="ui-widget-content" title="The description as shown in the drop down menus in the Status Management app" colspan=2>Project Description</td>
  <td class="ui-widget-content" title="The description as shown in the drop down menus in the Status Management app" colspan=4><input type="text" value="Brief Description" name="desc" size=30 length=30 onfocus="clear_input(this);"></td>
</tr>
<tr>
  <th class="ui-state-default" colspan=6>Description From Timecard</th>
</tr>
<tr>
  <td class="ui-widget-content" title="The project code as seen in iConnect">Project Code</td>
  <td class="ui-widget-content" title="The project code as seen in iConnect"><input type="text" value="7884" name="code" size=10 length=10 onfocus="clear_input(this);"></td>
  <td class="ui-widget-content" title="The project code as seen in iConnect">Service Now</td>
  <td class="ui-widget-content" title="The project code as seen in iConnect"><input type="text" value="" name="snow" size=20 length=20 onfocus="clear_input(this);"></td>
  <td class="ui-widget-content" title="The project name as seen in iConnect">Project Name</td>
  <td class="ui-widget-content" title="The project name as seen in iConnect"><input type="text" name="project" value="As seen in iConnect" size=30 length=30 onfocus="clear_input(this);"></td>
  <td class="ui-widget-content" title="The project task as seen in iConnect">Task</td>
  <td class="ui-widget-content" title="The project task as seen in iConnect"><input type="text" value="As seen in iConnect" name="task" size=30 length=30 onfocus="clear_input(this);"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default">ID</th>
  <th class="ui-state-default">Description</th>
  <th class="ui-state-default">Code</th>
  <th class="ui-state-default">Service Now</th>
  <th class="ui-state-default">Name</th>
  <th class="ui-state-default">Task</th>
</tr>
<?php

$q_string  = "select prj_id,prj_name,prj_code,prj_snow,prj_task,prj_desc ";
$q_string .= "from project ";
$q_string .= "order by prj_name";
$q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
while ($a_project = mysqli_fetch_array($q_project)) {

  print "<tr>\n";
  print "  <td class=\"ui-widget-content\">" . $a_project['prj_id'] . "</td>\n";
  print "  <td class=\"ui-widget-content\" title=\"The description as shown in the drop down menus in the Status Management app\">" . $a_project['prj_desc'] . "</td>\n";
  print "  <td class=\"ui-widget-content\" title=\"The project code as seen in iConnect\">" . $a_project['prj_code'] . "</td>\n";
  print "  <td class=\"ui-widget-content\" title=\"The project code as seen in iConnect\">" . $a_project['prj_snow'] . "</td>\n";
  print "  <td class=\"ui-widget-content\" title=\"The project name as seen in iConnect\">" . $a_project['prj_name'] . "</td>\n";
  print "  <td class=\"ui-widget-content\" title=\"The project task as seen in iConnect\">" . $a_project['prj_task'] . "</td>\n";
  print "</tr>\n";
  $count++;

}

if ($count == 0) {
  print "<tr>\n";
  print "  <td class=\"ui-widget-content\">No records found.</td>\n";
  print "</tr>\n";
}

mysqli_free_result($q_project);

?>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
