<?php
# Script: ras.php
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

  $package = "ras.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['startweek'] = 0;
  $formVars['endweek']   = 0;
  $formVars['user']      = 0;
  $formVars['startweek'] = clean($_GET['startweek'], 10);
  $formVars['endweek']   = clean($_GET['endweek'], 10);
  $formVars['user']      = clean($_GET['user'], 10);
  $DEBUG = 0;

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 176;
  }

  if ($formVars['endweek'] == 0) {
    $formVars['endweek'] = 179;
  }

  logaccess($db, $_SESSION['username'], "ras.php", "Viewing the ras");

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from users ";
  $q_string .= "where usr_name = '" . $_SESSION['username'] . "'";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_users = mysqli_fetch_array($q_users);

  $formVars['id'] = $a_users['usr_id'];
  $formVars['group'] = $a_users['usr_group'];

  if ($formVars['user'] != $formVars['id']) {
    logaccess($db, $_SESSION['username'], "ras.php", "Escalated privileged access to " . $formVars['id']);
    check_login($db, $AL_Supervisor);
  }

  $projnum = 0;
# Load the project codes into an array
  $q_string  = "select prj_id,prj_code,prj_name,prj_desc ";
  $q_string .= "from st_project ";
#  $q_string .= "where prj_code != 7884 and prj_code != 2839 ";
  $q_string .= "order by prj_name";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_project = mysqli_fetch_array($q_st_project)) {
    $projid[$projnum]     = $a_st_project['prj_id'];
    $projdesc[$projnum]   = $a_st_project['prj_desc'];
    $projcode[$projnum]   = $a_st_project['prj_code'];
    $projname[$projnum++] = $a_st_project['prj_name'];
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Review Resource Allocations</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<?php

# Plan: 
# 1. Parse through the groups to get the group name and group id
# 2. Parse through the projects
# 3. Iterate through the users based on the group id
# 4. Parse through the status lines based on user and project id and for January

$q_string  = "select grp_id,grp_name ";
$q_string .= "from groups ";
$q_string .= "order by grp_name";
$q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

while ($a_groups = mysqli_fetch_array($q_groups)) {

  $total = 0;
  $projects = 0;
  print "<tr>\n";
  print "  <th class=\"ui-state-default\" colspan=2>" . $a_groups['grp_name'] . "</th>\n";
  print "</tr>\n";

# build the user portion of the query
  $query = "(";
  $orstr = "";
  $q_string  = "select usr_id ";
  $q_string .= "from users ";
  $q_string .= "where usr_group = " . $a_groups['grp_id'];
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_users = mysqli_fetch_array($q_users)) {
    $query .= $orstr . "strp_name = " . $a_users['usr_id'];
    $orstr = " or ";
  }
  $query .= ") and ";
  if ($query != "() and ") {
    for ($i = 0; $i < $projnum; $i++) {
      $prototal = 0;
      $subtotal = 0;
      $q_string  = "select strp_time ";
      $q_string .= "from status ";
      $q_string .= "where (strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . ") and " . $query . " strp_project = " . $projid[$i] . " ";
      $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_status = mysqli_fetch_array($q_status)) {
        if ($projcode[$i] == 7884 || $projcode[$i] == 2839) {
          $subtotal += $a_status['strp_time'];
        } else {
          $prototal += $a_status['strp_time'];
        }
      }

      if ($subtotal > 0 || $prototal > 0) {
        print "<tr>\n";
        print "  <td class=\"ui-widget-content\" title=\"Click to show breakdown of hours and outstanding todo items by team member for this item\"><a href=\"" . $Projectroot . "/show.project.php?project=" . $projid[$i] . "&startweek=" . $formVars['startweek'] . "&endweek=" . $formVars['endweek'] . "\" target=\"_blank\">" . $projname[$i] . " (" . $projdesc[$i] . ")</a></td>\n";
        if ($subtotal > 0) {
          print "<td class=\"ui-widget-content\">" . ($subtotal * 15 / 60) . "</td>\n";
        } else {
          print "<td class=\"ui-widget-content\">" . ($prototal * 15 / 60) . "</td>\n";
        }
        print "</tr>\n";
      }
      $total += $subtotal;
      $projects += $prototal;
    }
  }
  if ($total > 0) {
    print "<tr>\n";
    print "  <td class=\"ui-widget-content button\"><b>Overhead Total</b></td>\n";
    print "  <td class=\"ui-widget-content button\"><b>" . ($total * 15 / 60) . "</b></td>\n";
    print "</tr>\n";
  }
  if ($projects > 0) {
    print "<tr>\n";
    print "  <td class=\"ui-widget-content button\"><b>Project Total</b></td>\n";
    print "  <td class=\"ui-widget-content button\"><b>" . ($projects * 15 / 60) . "</b></td>\n";
    print "</tr>\n";
  }
}

?>
</table>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
