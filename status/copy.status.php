<?php
# Script: copy.status.php
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

  $package = "copy.status.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user'] = 1;
  if (isset($_GET['user'])) {
    $formVars['user']      = clean($_GET['user'], 10);
  }
  if ($formVars['user'] == '') {
    $formVars['user'] = 1;
  }

  $formVars['startweek'] = 1;
  if (isset($_GET['startweek'])) {
    $formVars['startweek'] = clean($_GET['startweek'], 4);
  }
  if ($formVars['startweek'] == '') {
    $formVars['startweek'] = 1;
  }

  $formVars['endweek'] = 2;
  if (isset($_GET['endweek'])) {
    $formVars['endweek']   = clean($_GET['endweek'], 4);
  }
  if ($formVars['endweek'] == '') {
    $formVars['endweek'] = 2;
  }

  logaccess($db, $_SESSION['username'], $package, "Copy detail records: from=" . $formVars['startweek'] . " to=" . $formVars['endweek'] . " user=" . $formVars['user']);

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_users = mysqli_fetch_array($q_users)) {
    if ($_SESSION['username'] == $a_users['usr_name']) {
      $formVars['id'] = $a_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], $package, "Escalated privileged access to " . $formVars['id']);
  }

  $q_string  = "select strp_name,strp_class,strp_type,strp_progress,strp_project,";
  $q_string .= "strp_day,strp_time,strp_task,strp_save,strp_quarter ";
  $q_string .= "from status ";
  $q_string .= "where strp_name = " . $formVars['user'] . " and strp_week = " . $formVars['startweek'] . " ";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_status = mysqli_fetch_array($q_status)) {
    $q_string = "insert into status set " . 
      "strp_id       = "   . "NULL"                      . ", " . 
      "strp_week     = "   . $formVars['endweek']        . ", " . 
      "strp_name     = "   . $a_status['strp_name']      . ", " . 
      "strp_class    = "   . $a_status['strp_class']     . ", " . 
      "strp_type     = "   . $a_status['strp_type']      . ", " . 
      "strp_progress = "   . $a_status['strp_progress']  . ", " . 
      "strp_project  = "   . $a_status['strp_project']   . ", " . 
      "strp_day      = "   . $a_status['strp_day']       . ", " . 
      "strp_time     = "   . $a_status['strp_time']      . ", " . 
      "strp_task     = \"" . $a_status['strp_task']      . "\", " . 
      "strp_save     = "   . $a_status['strp_save']      . ", " . 
      "strp_quarter  = "   . $a_status['strp_quarter'];

    mysqli_query($db, $q_string);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="REFRESH" content="3; url=<?php print $Siteroot; ?>">
<title>Copy Completed</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main ui-widget-content">

<p>Week successfully copied! Redirecting...</p>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
