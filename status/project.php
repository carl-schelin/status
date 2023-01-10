<?php
# Script: project.php
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

  $package = "project.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET['user'], 4);
  $formVars['startweek'] = clean($_GET['startweek'], 4);
  $formVars['endweek']   = clean($_GET['endweek'], 4);

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 1;
  }

  if ($formVars['endweek'] == 0) {
    $formVars['endweek'] = 200;
  }

  logaccess($db, $_SESSION['username'], "project.php", "Viewing project reports: startweek=" . $formVars['startweek'] . " endweek=" . $formVars['endweek'] . " user=" . $formVars['user']);

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_users = mysqli_fetch_array($q_users) ) {
    if ($_SESSION['username'] == $a_users['usr_name']) {
      $formVars['id'] = $a_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], "project.php", "Escalated privileged access to " . $formVars['id']);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>View Project Listing</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<h4>Yearly Accomplishments</h4>

<?php
# Retrieve all the projects into the projval array
  $project = 0;
  $q_string  = "select prj_id,prj_desc,prj_task ";
  $q_string .= "from project ";
  $q_string .= "order by prj_name,prj_task";
  $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_project = mysqli_fetch_array($q_project) ) {
    $projval[$a_project['prj_id']] = $a_project['prj_desc'];
    $projtask[$a_project['prj_id']] = $a_project['prj_task'];
  }

  $q_string  = "select strp_project,strp_task ";
  $q_string .= "from status ";
  $q_string .= "where strp_quarter = 1 and strp_name = " . $formVars['user'] . " and strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . " ";
  $q_string .= "order by strp_project,strp_quarter,strp_task";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_status = mysqli_fetch_array($q_status) ) {

    if ($project != $a_status['strp_project']) {
      $project = $a_status['strp_project'];
      if ($project > 0) {
        print "</p>\n<h5>" . $projval[$a_status['strp_project']] . " (" . $projtask[$a_status['strp_project']] . ")</h5><p>\n";
      }
    }

    if ($project > 0) {
      print "<br>&nbsp;<b>- " . $a_status['strp_task'] . "</b>\n";
    }

  }
?>

</div>

<div id="main">

<h4>All Documented Tasks</h4>
<?php
// Now print the regular stuff
  $q_string  = "select typ_id,typ_name ";
  $q_string .= "from type";
  $q_type = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_type = mysqli_fetch_array($q_type)) {
    $typeval[$a_type['typ_id']] = $a_type['typ_name'];
  }
  $typeval[0] = "N/A";

  $type = 99;
  $q_string  = "select strp_project,strp_type,strp_task ";
  $q_string .= "from status ";
  $q_string .= "where strp_quarter = 0 and strp_name = " . $formVars['user'] . " and strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . " ";
  $q_string .= "order by strp_project,strp_type,strp_task";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_status = mysqli_fetch_array($q_status) ) {

    if ($project != $a_status['strp_project']) {
      $project = $a_status['strp_project'];
      $type = 99;
      if ($project > 0) {
        print "</p>\n<h5>" . $projval[$a_status['strp_project']] . " (" . $projtask[$a_status['strp_project']] . ")</h5><p>\n";
      }
    }

    if ($type != $a_status['strp_type']) {
      $type = $a_status['strp_type'];
      print "<br><u>" . $typeval[$a_status['strp_type']] . "</u>\n";
    }

    if ($project > 0) {
      print "<br>&nbsp;- " . $a_status['strp_task'] . "\n";
    }

  }

?>
</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
