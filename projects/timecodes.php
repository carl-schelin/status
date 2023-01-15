<?php
# Script: timecodes.php
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

  $package = "timecodes.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

#######
# Ready the group information to be displayed.
#######

  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from st_groups ";
  $q_string .= "where grp_id=" . $_SESSION['group'];
  $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_groups = mysqli_fetch_array($q_st_groups);
  $user_group = $a_st_groups['grp_name'];
  $user_groupid = $a_st_groups['grp_id'];

  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from st_groups ";
  $q_string .= "where grp_id not like " . $_SESSION['group'];
  $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Time Code Listing</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">
<?php

  print "<table class=\"ui-widget-content\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\" colspan=5 align=center>" . $user_group . "</th>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\" width=5%>Code</th>\n";
  print "  <th class=\"ui-state-default\" width=5%>Service Now</th>\n";
  print "  <th class=\"ui-state-default\" width=40%>iConnect Project</th>\n";
  print "  <th class=\"ui-state-default\" width=25%>iConnect Task</th>\n";
  print "  <th class=\"ui-state-default\" width=30%>Alias</th>\n";
  print "</tr>\n";

  $q_string  = "select prj_id,prj_name,prj_code,prj_snow,prj_task,prj_desc,prj_close ";
  $q_string .= "from st_project ";
  $q_string .= "where prj_group = $user_groupid ";
  $q_string .= "order by prj_name,prj_task";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
    if ($a_st_project['prj_close'] == 1) {
      $tdclass = " class=\"ui-state-error\"";
    } else {
      $tdclass = " class=\"ui-widget-content\"";
    }
    print "<tr>\n";
    print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_code'] . "</a></td>\n";
    print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_snow'] . "</a></td>\n";
    print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_name'] . "</a></td>\n";
    print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_task'] . "</a></td>\n";
    print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_desc'] . "</a></td>\n";
    print "</tr>\n";
  }
  print "</table>\n";

  while ( $a_st_groups = mysqli_fetch_array($q_st_groups) ) {
    print "<table>\n";
    print "<tr>\n";
    print "  <th class=\"ui-state-default\" colspan=5 align=center>" . $a_st_groups['grp_name'] . "</th>\n";
    print "</tr>\n";
    print "<tr>\n";
    print "  <th class=\"ui-state-default\" width=5%>Code</th>\n";
    print "  <th class=\"ui-state-default\" width=5%>Service Now</th>\n";
    print "  <th class=\"ui-state-default\" width=40%>iConnect Project</th>\n";
    print "  <th class=\"ui-state-default\" width=25%>iConnect Task</th>\n";
    print "  <th class=\"ui-state-default\" width=30%>Alias</th>\n";
    print "</tr>\n";

    $q_string  = "select prj_id,prj_name,prj_code,prj_snow,prj_task,prj_desc,prj_close ";
    $q_string .= "from st_project ";
    $q_string .= "where prj_group = " . $a_st_groups['grp_id'] . " ";
    $q_string .= "order by prj_name,prj_task";
    $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
      if ($a_st_project['prj_close'] == 1) {
        $tdclass = " class=\"ui-state-error\"";
      } else {
        $tdclass = " class=\"ui-widget-content\"";
      }
      print "<tr>\n";
      print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_code'] . "</a></td>\n";
      print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_snow'] . "</a></td>\n";
      print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_name'] . "</a></td>\n";
      print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_task'] . "</a></td>\n";
      print "  <td" . $tdclass . "><a href=\"" . $Projectroot . "/show.project.php?project=" . $a_st_project['prj_id'] . "&startweek=0&endweek=999\">" . $a_st_project['prj_desc'] . "</a></td>\n";
      print "</tr>\n";
    }
    print "</table>\n";
  }

?>
</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
