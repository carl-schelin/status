<?php
# Script: index.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  include('settings.php');
  include($Sitepath . "/guest.php");

  $package = "index.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

### This bit returns the index for the current week. Not everyone uses just the todo function.
  $today = date('w');
  $friday = 5 - $today;
  $thisweek = date('Y-m-d', mktime(0, 0, 0, date('m'), date("d") + $friday, date("Y")));

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks ";
  $q_string .= "where wk_date = \"" . $thisweek . "\" ";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_weeks = mysqli_fetch_array($q_st_weeks);

  $currentweek = $a_st_weeks['wk_id'];
###

######
# Retrieve the logged in user info
######

  $q_string  = "select usr_id,usr_first,usr_last,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_name = \"" . $_SESSION['username'] . "\" ";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $formVars['id'] = $a_st_users['usr_id'];
  $formVars['username'] = $a_st_users['usr_first'] . ' ' . $a_st_users['usr_last'];
  $formVars['group'] = $a_st_users['usr_group'];

#######
# Retrieve all the weeks into the weekval array
#######

  $week = 0;
  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks ";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_weeks = mysqli_fetch_array($q_st_weeks) ) {
    $weekval[$a_st_weeks['wk_id']] = $a_st_weeks['wk_date'];
  }
  $weektot = count($weekval) + 1;

#######
# Retrieve the last week data's been entered. This will be the "selected" value to make it easy to enter data.
#######

  $q_string  = "select strp_week ";
  $q_string .= "from st_status ";
  $q_string .= "where strp_name = " . $formVars['id'] . " ";
  $q_string .= "order by strp_week ";
  $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_status = mysqli_fetch_array($q_st_status) ) {
    $week = $a_st_status['strp_week'];
  }

  if ($week == 0) {
    $week = $currentweek;
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Status Management</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function setenddate() {

  document.SMAForm.endweek.value = document.SMAForm.startweek.value;

}

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<h1>Status Management</h1>

<div class="main ui-widget-content">

<p>The purpose of this tool is so the user will be able to easily enter weekly tasks. The data can then be reviewed, 
edited, and captured for the weekly e-mail report. In addition, since the data is saved, it can be used as part of 
your quarterly or yearly review.</p>

</div>

<form name="SMAForm" type="GET" action="<?php print $Statusroot; ?>/status.report.php">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default">Start Week</th>
  <th class="ui-state-default">End Week</th>
  <th class="ui-state-default" colspan="2">Task Description</th>
</tr>
<tr>
  <td class="ui-widget-content delete"><select name="startweek" onchange="setenddate();">
    <option value="0">None</option>
<?php
  for ($i = 1; $i < $weektot; $i++) {
    $selected=""; 
    if ($week == $i) {
      $selected=" selected";
    } 
    print "    <option value=\"" . $i . "\"$selected>" . $weekval[$i] . "</option>\n";
  } 
?>
</select></td>
  <td class="ui-widget-content delete"><select name="endweek">
    <option value="0" />None
<?php

  for ($i = 1; $i < $weektot; $i++) {
    $selected=""; 
    if ($week == $i) {
      $selected=" selected";
    } 
    print "    <option value=\"" . $i . "\"$selected \\>" . $weekval[$i] . "\n";
  } 
?>
  </select></td>
  <td class="ui-widget-content" colspan="2">Select the starting and if appropriate, the end date. Not all actions will use the end date.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Enter Weekly Data</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/status.report.php';return true;"></td>
  <td class="ui-widget-content">Weekly status report data entry. Enter the tasks you've done during the week.</td>
  <td class="delete ui-widget-content" title="Edit the weeks data in case you need to modify the date, classification, or the text of a task"><input type="submit" value="Edit" onclick="SMAForm.action='<?php print $Statusroot; ?>/edit.status.php';return true;"></td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Review Status Report</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/status.php';return true;"></td>
  <td class="ui-widget-content" colspan=2>Review your status report and mail the results to yourself.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Manage Todo List</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Todoroot; ?>/todo.php';return true;"></td>
  <td class="ui-widget-content">View and manage your Todo list.</td>
  <td class="delete ui-widget-content" title="Edit the todo data in case you need to modify the text of an item"><input type="submit" value="Edit" onclick="SMAForm.action='<?php print $Todoroot; ?>/edit.todo.php';return true;"></td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Review Todo List</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Todoroot; ?>/todo.review.php';return true;"></td>
  <td class="ui-widget-content" colspan="2">Review your todo list and mail the results to yourself.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Review Timecard</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/timecard.php';return true;"></td>
  <td class="ui-widget-content" colspan="2">Review your weekly timecard. Assuming you're tracking time, you can easily transfer your data to iConnect.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Review Project Hours</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $RASroot; ?>/ras.php';return true;"></td>
  <td class="ui-widget-content" colspan=2>View the actual hours worked over the selected date range.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Annual Review</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/quarterly.php';return true;"></td>
  <td class="ui-widget-content" colspan=2>Review your status report for a date range for preparation of your Annual Review.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Annual Report</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/project.php';return true;"></td>
  <td class="ui-widget-content" colspan=2>Final report on the work you've done over the date range selected.</td>
</tr>
<tr>
  <td class="button ui-widget-content" colspan="2"><b>Copy Week</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/copy.status.php';return true;">
<input type="hidden" name="user" value="<?php print $formVars['id']; ?>">
<input type="hidden" name="group" value="0">
<input type="hidden" name="week" value="0">
</td>
  <td class="ui-widget-content" colspan=2>This copies the <b>Start Week</b> to the <b>End Week</b>. <b><u>There is no confirmation</u></b>.</td>
</tr>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
