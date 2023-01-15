<?php
# Script: edit.project.php
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

  $package = "edit.project.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['group'] = clean($_GET['group'],10);

#######
# Retrieve all the groups into the groupval array
#######

  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from groups";
  $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_groups = mysqli_fetch_array($q_groups) ) {
    $groupval[$a_groups['grp_id']] = $a_groups['grp_name'];
  }

  $q_string  = "select usr_projects ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id = " . $_SESSION['uid'];
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Project Codes</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function attach_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<p><b>Note:</b> When a project code changes, don't modify it but add a new one. If you need to create a new entry, 
<a href="add.project.php">use this script</a>. You can also copy project codes from other groups into your group here.</p>

<?php

#######
# Display the project codes
#######


$q_string  = "select grp_id,grp_name ";
$q_string .= "from groups ";
$q_string .= "where grp_id = " . $formVars['group'];
$q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
$a_groups = mysqli_fetch_array($q_groups);

print "<form name=\"update\">\n";
print "<table class=\"ui-widget-content\">\n";
print "<tr>\n";
print "  <th class=\"ui-state-default\" colspan=7>" . $a_groups['grp_name'] . "</th>\n";
print "</tr>\n";
print "<tr>\n";
print "  <th class=\"ui-state-default\">Project Name</th>\n";
print "  <th class=\"ui-state-default\">Code</th>\n";
print "  <th class=\"ui-state-default\">Service Now</th>\n";
print "  <th class=\"ui-state-default\">Task</th>\n";
print "  <th class=\"ui-state-default\">Project Description</th>\n";
print "  <th class=\"ui-state-default\" title=\"Checkbox used to create a Personal Project Menu showing only your projects\">Mine</th>\n";
print "  <th class=\"ui-state-default\">Close</th>\n";
print "  <th class=\"ui-state-default\">Changes</th>\n";
print "</tr>\n";

$matches[0] = '';
$q_string  = "select prj_id,prj_name,prj_code,prj_snow,prj_task,prj_desc,prj_close ";
$q_string .= "from st_project ";
$q_string .= "where prj_group = " . $a_groups['grp_id'] . " ";
$q_string .= "order by prj_name,prj_desc,prj_task,prj_code ";
$q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
while ($a_st_project = mysqli_fetch_array($q_st_project)) {

  $prj_id = $a_st_project['prj_id'];
  print "<tr>\n";

  print "  <td class=\"ui-widget-content\" id=\"name_" . $prj_id . "\"><input type=\"text\" name=\"name_"   .$prj_id."\" size=\"40\" value=\"".$a_st_project['prj_name']."\"></td>\n";
  print "  <td class=\"ui-widget-content\" id=\"proj_" . $prj_id . "\"><input type=\"text\" name=\"project_".$prj_id."\" size=\"5\"  value=\"".$a_st_project['prj_code']."\"></td>\n";
  print "  <td class=\"ui-widget-content\" id=\"snow_" . $prj_id . "\"><input type=\"text\" name=\"snow_"   .$prj_id."\" size=\"5\"  value=\"".$a_st_project['prj_snow']."\"></td>\n";
  print "  <td class=\"ui-widget-content\" id=\"task_" . $prj_id . "\"><input type=\"text\" name=\"task_"   .$prj_id."\" size=\"30\" value=\"".$a_st_project['prj_task']."\"></td>\n";
  print "  <td class=\"ui-widget-content\" id=\"desc_" . $prj_id . "\"><input type=\"text\" name=\"desc_"   .$prj_id."\" size=\"25\" value=\"".$a_st_project['prj_desc']."\"></td>\n";
  if (preg_match("/:" . $prj_id . ":/i", $a_st_users['usr_projects'])) {
    $checked = "checked ";
  } else {
    $checked = "";
  }
  print "  <td class=\"ui-widget-content delete\" title=\"Select this project for your Personal Project Menu\" id=\"pers_" . $prj_id . "\" align=center><input type=\"checkbox\" name=\"pers_"  . $prj_id . "\" $checked></td>\n";
  if ($a_st_project['prj_close']) {
    $checked = "checked ";
  } else {
    $checked = "";
  }
  print "  <td class=\"ui-widget-content delete\" title=\"Close this project to additional hours\" id=\"clos_" . $prj_id . "\" align=center><input type=\"checkbox\" name=\"close_"  . $prj_id . "\" $checked></td>\n";
  print "  <td class=\"ui-widget-content delete\" title=\"Save changes to this project code.\" id=\"save_" . $prj_id . "\"><input type=\"button\" value=\"Save\" onClick=\"javascript:attach_file('edit.project.mysql.php?id=" . $prj_id . "&name=' + encodeURIComponent(name_$prj_id.value) + '&close=' + close_$prj_id.checked + '&code=' + project_$prj_id.value + '&task=' + encodeURIComponent(task_$prj_id.value) + '&desc=' + encodeURIComponent(desc_$prj_id.value) + '&personal=' + pers_$prj_id.checked);\"></td>\n";

  print "</tr>\n";

}

print "</table>\n";

print "</form>\n";

print "</div>\n";


// Print all the other groups
$q_string  = "select grp_id,grp_name ";
$q_string .= "from groups ";
$q_string .= "where grp_id not like " . $formVars['group'];
$q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
while ($a_groups = mysqli_fetch_array($q_groups)) {

  print "<div id=\"main\">\n";
  print "<table class=\"ui-widget-content\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\" colspan=7>" . $a_groups['grp_name'] . "</th>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">Project Name</th>\n";
  print "  <th class=\"ui-state-default\">Code</th>\n";
  print "  <th class=\"ui-state-default\">Service Now</th>\n";
  print "  <th class=\"ui-state-default\">Task</th>\n";
  print "  <th class=\"ui-state-default\">Project Description</th>\n";
  print "  <th class=\"ui-state-default\">To My Group</th>\n";
  print "</tr>\n";

  $q_string  = "select * ";
  $q_string .= "from st_project ";
  $q_string .= "where prj_group = " . $a_groups['grp_id'] . " ";
  $q_string .= "order by prj_name,prj_desc,prj_task,prj_code ";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {

    $prj_id = $a_st_project['prj_id'];
    print "<tr>\n";

    print "  <td class=\"ui-widget-content\" id=\"name_" . $prj_id . "\">" . $a_st_project['prj_name'] . "</td>\n";
    print "  <td class=\"ui-widget-content\" id=\"proj_" . $prj_id . "\">" . $a_st_project['prj_code'] . "</td>\n";
    print "  <td class=\"ui-widget-content\" id=\"snow_" . $prj_id . "\">" . $a_st_project['prj_snow'] . "</td>\n";
    print "  <td class=\"ui-widget-content\" id=\"task_" . $prj_id . "\">" . $a_st_project['prj_task'] . "</td>\n";
    print "  <td class=\"ui-widget-content\" id=\"desc_" . $prj_id . "\">" . $a_st_project['prj_desc'] . "</td>\n";
    print "  <td class=\"ui-widget-content delete\" title=\"Copy this project into your group list.\" id=\"copy_" . $prj_id . "\"><input type=\"button\" value=\"Copy\" onClick=\"javascript:attach_file('copy.project.mysql.php?id=" . $prj_id . "&group=" . $formVars['group'] . "')\"></td>\n";

    print "</tr>\n";

  }

  print "</table>\n";
  print "</div>\n";
}

?>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
