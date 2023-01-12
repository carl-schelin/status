<?php
# Script: show.project.php
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

  $package = "show.project.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['project']   = clean($_GET['project'], 10);
  $formVars['startweek'] = clean($_GET['startweek'], 10);
  $formVars['endweek']   = clean($_GET['endweek'], 10);

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_users = mysqli_fetch_array($q_users)) {
    $userval[$a_users['usr_id']] = $a_users['usr_name'];
  }

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks ";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_weeks = mysqli_fetch_array($q_st_weeks)) {
    $weekval[$a_st_weeks['wk_id']] = $a_st_weeks['wk_date'];
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Show Completed Project Tasks</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<?php
  $q_string  = "select prj_name,prj_code,prj_task,prj_desc ";
  $q_string .= "from st_project ";
  $q_string .= "where prj_id = " . $formVars['project'];
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_project = mysqli_fetch_array($q_st_project);

  print "<tr>\n";
  print "  <th class=\"ui-state-default\" colspan=2>" . $a_st_project['prj_desc'] . "</th>\n";
  print "</tr>\n";

  $header  = "<tr>\n";
  $header .= "  <th class=\"ui-state-default\" align=left colspan=2><i>N/A</i></th>\n";
  $header .= "</tr>\n";

  $q_string  = "select strp_name,strp_week,strp_time,strp_task ";
  $q_string .= "from status ";
  $q_string .= "where strp_type = 0 and strp_project = " . $formVars['project'] . " and (strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . ") ";
  $q_string .= "order by strp_week";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_status = mysqli_fetch_array($q_status)) {
    print $header;
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_status['strp_task'] . " (" . $userval[$a_status['strp_name']] . ")</td>\n";
    print "  <td class=\"ui-widget-content\" align=center>" . $weekval[$a_status['strp_week']] . "&nbsp;" . number_format((($a_status['strp_time'] * 15) / 60), 2, '.', ',') . "</td>\n";
    print "</tr>\n";
    $header = "";
  }

  $header  = "<tr>\n";
  $header .= "  <th class=\"ui-state-default\" align=left colspan=2><i>Meeting</i></th>\n";
  $header .= "</tr>\n";

  $q_string  = "select strp_name,strp_week,strp_time,strp_task ";
  $q_string .= "from status ";
  $q_string .= "where strp_type = 1 and strp_project = " . $formVars['project'] . " and (strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . ") ";
  $q_string .= "order by strp_week";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_status = mysqli_fetch_array($q_status)) {
    print $header;
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_status['strp_task'] . " (" . $userval[$a_status['strp_name']] . ")</td>\n";
    print "  <td class=\"ui-widget-content\" align=center>" . $weekval[$a_status['strp_week']] . "&nbsp;" . number_format((($a_status['strp_time'] * 15) / 60), 2, '.', ',') . "</td>\n";
    print "</tr>\n";
    $header = "";
  }

  $header  = "<tr>\n";
  $header .= "  <th class=\"ui-state-default\" align=left colspan=2><i>Reactive</i></th>\n";
  $header .= "</tr>\n";

  $q_string  = "select strp_name,strp_week,strp_time,strp_task from status ";
  $q_string .= "where strp_type = 2 and strp_project = " . $formVars['project'] . " and ";
  $q_string .= "(strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . ") ";
  $q_string .= "order by strp_week";

  while ($a_status = mysqli_fetch_array($q_status)) {
    print $header;
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_status['strp_task'] . " (" . $userval[$a_status['strp_name']] . ")</td>\n";
    print "  <td class=\"ui-widget-content\" align=center>" . $weekval[$a_status['strp_week']] . "&nbsp;" . number_format((($a_status['strp_time'] * 15) / 60), 2, '.', ',') . "</td>\n";
    print "</tr>\n";
    $header = "";
  }

  $header  = "<tr>\n";
  $header .= "  <th class=\"ui-state-default\" align=left colspan=2><i>Proactive</i></th>\n";
  $header .= "</tr>\n";

  $q_string  = "select strp_name,strp_week,strp_time,strp_task from status ";
  $q_string .= "where strp_type = 3 and strp_project = " . $formVars['project'] . " and ";
  $q_string .= "(strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . ") ";
  $q_string .= "order by strp_week";

  while ($a_status = mysqli_fetch_array($q_status)) {
    print $header;
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_status['strp_task'] . " (" . $userval[$a_status['strp_name']] . ")</td>\n";
    print "  <td class=\"ui-widget-content\" align=center>" . $weekval[$a_status['strp_week']] . "&nbsp;" . number_format((($a_status['strp_time'] * 15) / 60), 2, '.', ',') . "</td>\n";
    print "</tr>\n";
    $header = "";
  }

  $header  = "<tr>\n";
  $header .= "  <th class=\"ui-state-default\" align=left><i>Todo</i></th>\n";
  $header .= "  <th class=\"ui-state-default\">Due</th>\n";
  $header .= "</tr>\n";

  $q_string  = "select todo_name,todo_due,todo_time,todo_user ";
  $q_string .= "from todo ";
  $q_string .= "where todo_completed = 0 and todo_project = " . $formVars['project'] . " ";
  $q_string .= "order by todo_due";
  $q_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ($a_todo = mysqli_fetch_array($q_todo)) {
    print $header;
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_todo['todo_name'] . " (" . $userval[$a_todo['todo_user']] . ")</td>\n";
    print "  <td class=\"ui-widget-content\" align=center>" . $weekval[$a_todo['todo_due']] . "&nbsp;" . number_format((($a_todo['todo_time'] * 15) / 60), 2, '.', ',') . "</td>\n";
    print "</tr>\n";
    $header = "";
  }

?>
</table>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
