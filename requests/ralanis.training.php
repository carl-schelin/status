<?php
# Script: ralanis.training.php
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

  $package = "ralanis.training.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  if (isset($_GET['group'])) {
    $formVars['group']     = clean($_GET['group'], 4);
  } else {
    $formVars['group'] = 1;
  }
  if (isset($_GET['startweek'])) {
    $formVars['startweek'] = clean($_GET['startweek'], 4);
  } else {
    $formVars['startweek'] = 228;
  }
  if (isset($_GET['endweek'])) {
    $formVars['endweek']   = clean($_GET['endweek'], 4);
  } else {
    $formVars['endweek'] = 279;
  }

  logaccess($db, $_SESSION['username'], "ralanis.training.php", "Viewing Training: startweek=" . $formVars['startweek'] . " endweek=" . $formVars['endweek'] . " group=" . $formVars['group']);

  $q_string  = "select grp_name ";
  $q_string .= "from groups ";
  $q_string .= "where grp_id = " . $formVars['group'];
  $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_groups = mysqli_fetch_array($q_groups);

  if ($a_groups['grp_name'] == '') {
    $a_groups['grp_name'] = "Unknown Group";
  }

  $q_string  = "select prj_id ";
  $q_string .= "from project ";
  $q_string .= "where prj_code = 7884 and prj_task like \"%Training%\"";
  $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_project = mysqli_fetch_array($q_project);

  if ($a_project['prj_id'] == '') {
    $a_project['prj_id'] = 0;
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>View Training Listing</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=2><?php print $a_groups['grp_name']; ?></th>
</tr>
<tr>
  <th class="ui-state-default">User</th>
  <th class="ui-state-default">Class</th>
</tr>
<?php

  $q_string  = "select strp_task,usr_first,usr_last ";
  $q_string .= "from status ";
  $q_string .= "left join users on users.usr_id = status.strp_name ";
  $q_string .= "where strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . " and strp_project = " . $a_project['prj_id'] . " and usr_group = " . $formVars['group'];
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_status = mysqli_fetch_array($q_status)) {

    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_status['usr_last'] . ", " . $a_status['usr_first'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_status['strp_task'] . "</td>\n";
    print "</tr>\n";

  }

?>
</table>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
