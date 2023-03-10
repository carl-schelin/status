<?php
# Script: status.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "status.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user'] = clean($_GET['user'], 10);
  $formVars['startweek'] = clean($_GET['startweek'], 4);
  $formVars['endweek'] = clean($_GET['endweek'], 4);
  $formVars['group'] = clean($_GET['group'], 4);
  $DEBUG = 0;

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 155;
  }

  if ($formVars['user'] == 0 && $formVars['user'] == 0) {
    $formVars['user'] = 1;
  }

  $logfile = "status.php";

  logaccess($db, $_SESSION['username'], $logfile, "Viewing the status report page: week=" . $formVars['startweek'] . " user=" . $user . " group=" . $formVars['group']);

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_name = '" . $_SESSION['username'] . "'";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $formVars['id'] = $a_st_users['usr_id'];

  if ($formVars['user'] != $formVars['id']) {
    logaccess($db, $_SESSION['username'], $logfile, "Escalated privileged access to " . $formVars['id']);
    check_login($db, $AL_Supervisor);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Review and Email Weekly Status Report</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function initial_show() {

  show_file('status.mysql.php?id=0&startweek=<?php print $formVars['startweek'];?>&endweek=<?php print $formVars['endweek'];?>&user=<?php print $formVars['user'];?>&group=<?php print $formVars['group']; ?>&save=-1');
}

function show_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

</script>

</head>
<body onLoad="initial_show();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<span id="from_mysql"></span>

<p><a href="<?php print $Siteroot; ?>/email.php?user=<?php print $formVars['user']; ?>&startweek=<?php print $formVars['startweek']; ?>&endweek=<?php print $formVars['endweek']; ?>&group=<?php print $formVars['group']; ?>">Email the status report</a> - <a href="<?php print $Statusroot; ?>/edit.status.php?user=<?php print $formVars['user']; ?>&startweek=<?php print $formVars['startweek']; ?>&endweek=<?php print $formVars['endweek']; ?>&group=<?php print $formVars['group']; ?>">Edit the status report</a></p> 

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
