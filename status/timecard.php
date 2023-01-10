<?php
# Script: timecard.php
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

  $package = "timecard.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

// Set, clean, the GETed values
  $formVars['user']      = 0;
  $formVars['startweek'] = 0;
  $formVars['endweek']   = 0;
  $formVars['group']     = 0;
  $formVars['user']      = clean($_GET["user"], 10);
  $formVars['startweek'] = clean($_GET["startweek"], 10);
  $formVars['endweek']   = clean($_GET["endweek"], 10);
  $formVars['group']     = clean($_GET["group"], 10);

  logaccess($db, $_SESSION['username'], "timecard.php", "Viewing timecard: startweek=" . $formVars['startweek'] . " endweek=" . $formVars['endweek'] . " user=" . $formVars['user'] . " group=" . $formVars['group']);

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_users = mysqli_fetch_array($q_users) ) {
    if ($_SESSION['username'] == $a_users['usr_name']) {
      $formVars['id'] = $a_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_User);
  }

  $q_string  = "select wk_date ";
  $q_string .= "from weeks ";
  $q_string .= "where wk_id = " . $formVars['startweek'];
  $q_week = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_week = mysqli_fetch_array($q_week);
  $startname = $a_week['wk_date'];

  $q_string  = "select wk_date ";
  $q_string .= "from weeks ";
  $q_string .= "where wk_id = " . $formVars['endweek'];
  $q_week = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_week = mysqli_fetch_array($q_week);
  $endname = $a_week['wk_date'];

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Time Card Listing</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" title="Return to the status report edit screen." colspan="13">
<?php

  print "<a href=\"" . $Statusroot . "/timecard.php?startweek=" . ($formVars['startweek'] - 1) . "&endweek=" . $formVars['endweek'] . "&user=" . $formVars['user'] . "&group=" . $formVars['group'] . "\">[Add Older Week]</a>";
  print " <a href=\"" . $Statusroot . "/timecard.php?startweek=" . ($formVars['startweek'] - 1) . "&endweek=" . ($formVars['endweek'] - 1) . "&user=" . $formVars['user'] . "&group=" . $formVars['group'] . "\">[Back One Week]</a>";

  if ($startname == $endname) {
    if ($formVars['user'] > 0) {
      print " <a href=\"" . $Statusroot . "/status.report.php?startweek=" . $formVars['startweek'] . "&user=" . $formVars['user'] . "\">" . $startname . "</a> ";
    } else {
      print " " . $startname . " ";
    }
  } else {
    print " " . $startname . " to " . $endname . " ";
  }

  print "<a href=\"" . $Statusroot . "/timecard.php?startweek=" . ($formVars['startweek'] + 1) . "&endweek=" . ($formVars['endweek'] + 1) . "&user=" . $formVars['user'] . "&group=" . $formVars['group'] . "\">[Forward One Week]</a>";
  print " <a href=\"" . $Statusroot . "/timecard.php?startweek=" . $formVars['startweek'] . "&endweek=" . ($formVars['endweek'] + 1) . "&user=" . $formVars['user'] . "&group=" . $formVars['group'] . "\">[Add Newer Week]</a>";

?>
</th>
</tr>
<tr>
  <th class="ui-state-default">Code</th>
  <th class="ui-state-default">SNow</th>
  <th class="ui-state-default">Project</th>
  <th class="ui-state-default">Task</th>
  <th class="ui-state-default">Description</th>
  <th class="ui-state-default" width=5%>Sun</th>
  <th class="ui-state-default" width=5%>Mon</th>
  <th class="ui-state-default" width=5%>Tue</th>
  <th class="ui-state-default" width=5%>Wed</th>
  <th class="ui-state-default" width=5%>Thu</th>
  <th class="ui-state-default" width=5%>Fri</th>
  <th class="ui-state-default" width=5%>Sat</th>
  <th class="ui-state-default">Total</th>
</tr>
<?php

  $c_project[0] = "";
  $c_project[1] = 0;
  $daytotals[0] = 0;
  $daytotals[1] = 0;
  $daytotals[2] = 0;
  $daytotals[3] = 0;
  $daytotals[4] = 0;
  $daytotals[5] = 0;
  $daytotals[6] = 0;
  $mainttotals[0] = 0;
  $mainttotals[1] = 0;
  $mainttotals[2] = 0;
  $mainttotals[3] = 0;
  $mainttotals[4] = 0;
  $mainttotals[5] = 0;
  $mainttotals[6] = 0;

  $day = -1;
  $proj_total = 0;
  $day_total = 0;
  $week_total = 0;
  $q_user = "";
  $setor = " and (";

// Either a user is selected or group. If both are zero, show all data

  if ($formVars['group'] != 0) {
    if (check_userlevel($db, $AL_Supervisor)) {
      $q_string = "select usr_id from users where usr_supervisor = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Manager)) {
      $q_string = "select usr_id from users where usr_manager = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Director)) {
      $q_string = "select usr_id from users where usr_director = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_VicePresident)) {
      $q_string = "select usr_id from users where usr_vicepresident = " . $formVars['user'];
    }
  
# restrict to group if looking at something other than the Management group.
    if ($formVars['group'] != 3 && $formVars['group'] != -1) {
      $q_string .= " and usr_group = " . $formVars['group'];
    }
    
# now build the user string this will have all the users that fit the above criteria
    $prtor = "";
    $u_string = "";
  
    $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_users = mysqli_fetch_array($q_users)) {
      $u_string .= $prtor . "strp_name = " . $a_users['usr_id'];
      if ($prtor == "") {
        $prtor = " or ";
      }
    }
# if no users were found, empty group for instance, present just the user's data
    if ($u_string == "") {
      $u_string = "strp_name = " . $formVars['user'];
    }
  } else {
    $u_string = "strp_name = " . $formVars['user'];
  }

// Retrieve the requested data

  $q_string  = "select prj_id,prj_code,prj_snow,prj_name,prj_task,prj_desc ";
  $q_string .= "from project ";
  $q_string .= "order by prj_code,prj_task";
  $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_project = mysqli_fetch_array($q_project) ) {

    $class = "ui-widget-content";
    if ($a_project['prj_code'] == '7884' && $a_project['prj_task'] == '1.2 Maintenance') {
      $class = "ui-state-highlight";
    }

    $project_total = 0;
    $output = "<tr>";
    $output .= "  <td class=\"" . $class . "\">" . $a_project['prj_code'] . "</td>";
    $output .= "  <td class=\"" . $class . "\">" . $a_project['prj_snow'] . "</td>";
    $output .= "  <td class=\"" . $class . "\">" . $a_project['prj_name'] . "</td>";
    $output .= "  <td class=\"" . $class . "\">" . $a_project['prj_task'] . "</td>";
    $output .= "  <td class=\"" . $class . "\">" . $a_project['prj_desc'] . "</td>";

    $dailytot[0] = 0;
    $dailytot[1] = 0;
    $dailytot[2] = 0;
    $dailytot[3] = 0;
    $dailytot[4] = 0;
    $dailytot[5] = 0;
    $dailytot[6] = 0;

    $q_string  = "select strp_day,strp_time ";
    $q_string .= "from status ";
    $q_string .= "where strp_project = " . $a_project['prj_id'] . " and strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . " and (" . $u_string . ")";
    $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ( $a_status = mysqli_fetch_array($q_status) ) {
      $dailytot[$a_status['strp_day']] += $a_status['strp_time'];
    }

    for ($i = 0; $i < 7; $i++) {
      if ($dailytot[$i] > 0) {
        $output .= "  <td class=\"" . $class . " button\">" . number_format((($dailytot[$i] * 15) / 60), 2, '.', ',') . "</td>";
        $project_total += $dailytot[$i];
        $daytotals[$i] += $dailytot[$i];
        $mainttotals[$i] += $dailytot[$i];
      } else {
        $output .= "  <td class=\"" . $class . "\">&nbsp;</td>";
      }
    }

    $week_total += $project_total;

    $output .= "  <td class=\"" . $class . " button\">" . number_format((($project_total * 15) / 60), 2, '.', ',') . "</td>";
    $output .= "</tr>";

    if ($project_total > 0) {
      print $output;
    }
  }

// And finally, print the total for the week
  print "<tr>\n";
  print "  <td class=\"ui-widget-content\" colspan=\"5\">&nbsp;</td>\n";
  for ($i = 0; $i < 7; $i++) {
    if ($daytotals[$i] > 0) {
      print "  <td class=\"ui-widget-content button\">" . number_format((($daytotals[$i] * 15) / 60), 2, '.', ',') . "</td>\n";
    } else {
      print "  <td class=\"ui-widget-content\">&nbsp;</td>\n";
    }
  }
  print "  <td class=\"ui-widget-content button\">" . number_format((($week_total * 15) / 60), 2, '.', ',') . "</td>\n";
  print "</td>\n";
  print "</table>\n";

  print "</div>\n";

  print "<center>\n";

  print "<img src=\"" . $Statusroot . "/timegraph.php?user=" . $formVars['user'] . "&startweek=" . $formVars['startweek'] . "&endweek=" . $formVars['endweek'] . "&group=" . $formVars['group'] . "\">";

  if ($_SESSION['group'] < 5) {

    print "<img src=\"" . $Siteroot . "/imgs/graph.jpg\">\n";
  }

  print "</center>\n";
?>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
