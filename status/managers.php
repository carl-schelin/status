<?php
# Script: managers.php
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

  check_login($db, $AL_Supervisor);

  $package = "managers.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $DEBUG = 0;

### This bit returns the index for the current week. Not everyone uses just the todo function.
  $today = date('w');
  $friday = 5 - $today;
  $thisweek = date('Y-m-d', mktime(0, 0, 0, date('m'), date("d") + $friday, date("Y")));

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks ";
  $q_string .= "where wk_date = \"" . $thisweek . "\" ";
  $q_st_weeks = mysqli_query($db, $q_string);
  $a_st_weeks = mysqli_fetch_array($q_st_weeks);

  $currentweek = $a_st_weeks['wk_id'];
###

######
# Retrieve the logged in user info
######

  $q_string  = "select usr_id,usr_first,usr_last,usr_group,usr_supervisor,usr_manager,usr_director ";
  $q_string .= "from users ";
  $q_string .= "where usr_name = \"" . $_SESSION['username'] . "\"";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_users = mysqli_fetch_array($q_users);

  $formVars['id'] = $a_users['usr_id'];
  $formVars['username'] = $a_users['usr_first'] . ' ' . $a_users['usr_last'];
  $formVars['group'] = $a_users['usr_group'];

  logaccess($db, $_SESSION['username'], "managers.php", "Viewing the manager app: user=" . $formVars['username'] . " group=" . $formVars['group']);
# select users who's supervisor == your uid, manager == your uid, and/or director == your uid.

######
# Retrieve all the user info into the userval array
######

  $q_string = "select usr_id,usr_first,usr_last,usr_group from users where usr_id = " . $formVars['id'] . " or ";
  if (check_userlevel($db, $AL_VicePresident)) {
    $q_string .= "usr_vicepresident = " . $formVars['id'] . " and ";
  } else {
    if (check_userlevel($db, $AL_Director)) {
      $q_string .= "usr_director = " . $formVars['id'] . " and ";
    } else {
      if (check_userlevel($db, $AL_Manager)) {
        $q_string .= "usr_manager = " . $formVars['id'] . " and ";
      } else {
        if (check_userlevel($db, $AL_Supervisor)) {
          $q_string .= "usr_supervisor = " . $formVars['id'] . " and ";
        }
      }
    }
  }
  $q_string .= "usr_id != 1 and usr_disabled = 0 order by usr_last";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $count = 0;
  while ( $a_users = mysqli_fetch_array($q_users) ) {
    $userid[$count] = $a_users['usr_id'];
    $usergrp[$count] = $a_users['usr_group'];
    $userval[$count++] = $a_users['usr_last'] . ", " . $a_users['usr_first'];
  }
  $usertot = $count;

#######
# Retrieve all the groups into the groupval array
#######

  $q_string = "select grp_id,grp_name from groups where grp_id = " . $formVars['group'];

  for ($i = 0; $i < $usertot; $i++) {
    $q_string .= " or grp_id = " . $usergrp[$i];
  }
  $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $count = 0;
  while ( $a_groups = mysqli_fetch_array($q_groups) ) {
    $groupid[$count] = $a_groups['grp_id'];
    $groupval[$count++] = $a_groups['grp_name'];
  }
  $grouptot = $count;

#######
# Retrieve all the weeks into the weekval array
#######

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $week = 0;
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
  $q_string .= "order by strp_week";
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
<title>Status Management Menu</title>

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

<h1>Status Management (Manager View)</h1>

<p>This portion of the tool is restricted to users who have reports. The drop downs should let you view data for
reports that you are responsible for. You should only be able to view you and your reports as well as the groups 
that that you manage if any. The reports are the same as on the home page other than you can see other peoples 
data.</p>

<form action="<?php print $Statusroot; ?>/status.php" name="SMAForm" action="GET">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default">Start Week</th>
  <th class="ui-state-default">End Week</th>
  <th class="ui-state-default">Individual View</th>
  <th class="ui-state-default">Group View</th>
</tr>
<tr>
  <td class="ui-widget-content delete"><select name="startweek" onchange="setenddate();">
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
  <td class="ui-widget-content">
<?php
  if (check_userlevel($db, $AL_Supervisor)) {
    print "<select name=\"user\">\n";
    print "  <option value=\"0\" />None\n";

    for ($i = 0; $i < $usertot; $i++) {
      $selected="";
      if ($formVars['id'] == $userid[$i]) {
        $selected=" selected";
      }
      print "  <option value=\"" . $userid[$i] . "\"$selected \\>" . $userval[$i] . "\n";
    }
    print "</select></td>\n";

    print "<td class=\"ui-widget-content\"><select name=\"group\">\n";
    print "  <option value=\"0\" />None\n";
    print "  <option value=\"-1\" />All Your Reports\n";

    for ($i = 0; $i < $grouptot; $i++) {
      print "  <option value=\"" . $groupid[$i] . "\" \\>" . $groupval[$i] . "\n";
    }
    print "</select>\n";
  } else {
    print "<input type=\"hidden\" name=\"user\" value=\"" . $formVars['id'] . "\"></td>\n";
    print "<td class=\"ui-widget-content\"><input type=\"hidden\" name=\"group\" value=\"0\">\n";
  }
?>
</td>
</tr>
<tr>
  <td colspan=2 class="button"><b>Review Status Report</b> <input type="submit" value="Go!"onclick="SMAForm.action='<?php print $Statusroot; ?>/status.php';return true;"></td>
  <td colspan=2>Review the status reports for <b>individuals</b> or <b>teams</b> you manage.</td>
</tr>
<tr>
  <td colspan=2 class="button"><b>Review Todo List</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Todoroot; ?>/todo.review.php';return true;"></td>
  <td colspan=2>Review the Todo Lists for <b>individuals</b> or <b>teams</b> you manage.</td>
</tr>
<tr>
  <td colspan=2 class="button"><b>Review Timecard</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/timecard.php';return true;"></td>
  <td colspan=2>Review timecards for <b>individuals</b> or <b>teams</b> you manage.</td>
</tr>
<tr>
  <td colspan=2 class="button"><b>Review Annual Report</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $Statusroot; ?>/project.php';return true;"></td>
  <td colspan=2>Review the Annual Report for <b>individuals</b> you manage.</td>
</tr>
<tr>
  <td colspan=2 class="button"><b>Resource Allocation Spreadsheet</b> <input type="submit" value="Go!" onclick="SMAForm.action='<?php print $RASroot; ?>/add.ras.php';return true;"></td>
  <td colspan=2>Review the RAS for <b>groups</b> you manage.</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
