<?php
# Script: users.php
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

  $package = "users.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>View Users</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" valign=center rowspan=2>User</th>
  <th class="ui-state-default" colspan=2>Status Reports</th>
  <th class="ui-state-default" colspan=2>Todo Tasks</th>
  <th class="ui-state-default" colspan=2>Morning Report Tasks</th>
</tr>
<tr>
  <th class="ui-state-default">Entries</th>
  <th class="ui-state-default">Most Recent</th>
  <th class="ui-state-default">Tasks</th>
  <th class="ui-state-default">Most Recent</th>
  <th class="ui-state-default">Tasks</th>
  <th class="ui-state-default">Most Recent</th>
</tr>
<?php

  $q_string  = "select usr_id,usr_first,usr_last ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id != 1 and usr_disabled = 0 ";
  $q_string .= "order by usr_last";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_users = mysqli_fetch_array($q_st_users) ) {

    $statusweek = "--";
    $statuscount = 0;
    $statussave = 0;
    $statusannual = 0;
    $todoweek[0] = "--";
    $todocount = 0;
    $todocompleted = 0;
    $reportcount = 0;
    $reportweek[0] = "--";

# retrieve status information
    $q_string  = "select strp_save,strp_quarter,strp_week ";
    $q_string .= "from st_status ";
    $q_string .= "where strp_name = " . $a_st_users['usr_id'] . " ";
    $q_string .= "order by strp_week";
    $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ( $a_st_status = mysqli_fetch_array($q_st_status) ) {
      $statuscount++;
      $statusweek = $a_st_status['strp_week'];
      if ($a_st_status['strp_save'] == 1) {
        $statussave++;
      }
      if ($a_st_status['strp_quarter'] == 1) {
        $statusannual++;
      }
    }

    if ($statusweek != "--") {
      $q_string  = "select wk_date ";
      $q_string .= "from st_weeks ";
      $q_string .= "where wk_id = " . $statusweek . " ";
      $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_weeks = mysqli_fetch_array($q_st_weeks);
      $statusweek = $a_st_weeks['wk_date'];
    }

#retrieve todo information
    $q_string  = "select todo_completed,todo_entered ";
    $q_string .= "from todo ";
    $q_string .= "where todo_user = " . $a_st_users['usr_id'] . " ";
    $q_string .= "order by todo_entered";
    $q_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

    while ( $a_todo = mysqli_fetch_array($q_todo) ) {
      $todocount++;
      if ($a_todo['todo_completed'] > 0) {
        $todocompleted++;
      }
      $todoweek = explode(" ", $a_todo['todo_entered']);
    }

    $q_string  = "select rep_timestamp ";
    $q_string .= "from st_report ";
    $q_string .= "where rep_user = " . $a_st_users['usr_id'] . " ";
    $q_string .= "order by rep_timestamp";
    $q_st_report = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_st_report = mysqli_fetch_array($q_st_report)) {
      $reportcount++;
      $reportweek = explode(" ", $a_st_report['rep_timestamp']);
    }

    print "<tr>\n";

    print "  <td class=\"ui-widget-content\">" . $a_st_users['usr_last'] . ", " . $a_st_users['usr_first'] . "</td>\n";
    print "  <td class=\"ui-widget-content\" title=\"Number of Entries (Notable Entries/Annual Report)\" align=right>" . $statuscount . " (" . $statussave . "/" . $statusannual . ")</td>\n";
    print "  <td class=\"ui-widget-content\" title=\"Date of Last Entry\" align=center>" . $statusweek . "</td>\n";
    print "  <td class=\"ui-widget-content\" title=\"Number of Tasks (Number Completed)\" align=right>" . $todocount . " (" . $todocompleted . ")</td>\n";
    print "  <td class=\"ui-widget-content\" title=\"Date of Last Entry\" align=center>" . $todoweek[0] . "</td>\n";
    print "  <td class=\"ui-widget-content\" title=\"Number of Reports\" align=right>" . $reportcount . "</td>\n";
    print "  <td class=\"ui-widget-content\" title=\"Date of Last Entry\" align=center>" . $reportweek[0] . "</td>\n";

    print "</tr>\n";

  }

?>
</table>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
