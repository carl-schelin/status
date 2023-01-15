<?php
# Script: completed.php
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

  if (isset($_GET['sort'])) {
    $orderby = "order by " . clean($_GET['sort'], 30) . " ";
  } else {
    $orderby = "order by prj_desc,wk_date desc ";
  }

  if (isset($_GET['uid'])) {
    $user = clean($_GET['uid'], 5);
  } else {
    $user = $_SESSION['uid'];
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>List Completed Tasks</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan="6">Completed Tasks</th>
</tr>
<tr>
  <th class="ui-state-default"><a href="completed.php?sort=prj_desc">Project</a></th>
  <th class="ui-state-default"><a href="completed.php?sort=todo_name">Task</a></th>
  <th class="ui-state-default"><a href="completed.php?sort=todo_priority">Priority</a></th>
  <th class="ui-state-default"><a href="completed.php?sort=todo_entered">Entered</a></th>
  <th class="ui-state-default">Due</th>
  <th class="ui-state-default"><a href="completed.php?sort=wk_date">Completed</a></th>
</tr>
<?php

  $q_string  = "select todo_id,todo_name,prj_desc,todo_entered,todo_due,wk_date,todo_priority ";
  $q_string .= "from st_todo ";
  $q_string .= "left join st_weeks   on st_weeks.wk_id    = st_todo.todo_completed ";
  $q_string .= "left join st_project on st_project.prj_id = st_todo.todo_project ";
  $q_string .= "where todo_user = " . $user . " ";
  $q_string .= $orderby;
  $q_st_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_todo = mysqli_fetch_array($q_st_todo) ) {

    $q_string  = "select wk_date ";
    $q_string .= "from st_weeks ";
    $q_string .= "where wk_id = " . $a_st_todo['todo_due'] . " ";
    $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_st_weeks = mysqli_fetch_array($q_st_weeks);

    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_st_todo['prj_desc'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_st_todo['todo_name'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_st_todo['todo_priority'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_st_todo['todo_entered'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_st_weeks['wk_date'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_st_todo['wk_date'] . "</td>\n";
    print "</tr>\n";

  }

?>
</table>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
