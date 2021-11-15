<?php
# Script: quarterly.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($AL_User);

  $package = "quarterly.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET['user'], 10);
  $formVars['startweek'] = clean($_GET['startweek'], 4);
  $formVars['endweek']   = clean($_GET['endweek'], 4);
  $formVars['group']     = clean($_GET['group'], 4);
  $DEBUG = 0;

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 123;
  }

  if ($formVars['endweek'] == 0) {
    $formVars['endweek'] = 135;
  }

  if ($formVars['user'] == 0 && $formVars['group'] == 0) {
    $formVars['user'] = 5;
  }

  logaccess($_SESSION['username'], "quarterly.php", "Viewing the quarterly status report page: startweek=" . $formVars['startweek'] . " endweek=" . $formVars['endweek'] . " user=" . $formVars['user'] . " group=" . $formVars['group']);

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from users ";
  $q_string .= "where usr_name = '" . $_SESSION['username'] . "'";
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_users = mysql_fetch_array($q_users);

  $formVars['id'] = $a_users['usr_id'];

  if ($formVars['user'] != $formVars['id']) {
    logaccess($_SESSION['username'], "quarterly.php", "Escalated privileged access to " . $formVars['id']);
    check_login($AL_Supervisor);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Review Quarterly Accomplishments</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function toggleDiv(divid){
  var dv = document.getElementById(divid);
  dv.style.display = (dv.style.display == 'none'? 'block':'none');
}

function initial_show() {

  show_file('quarterly.mysql.php?id=0&startweek=<?php print $formVars['startweek'];?>&endweek=<?php print $formVars['endweek'];?>&user=<?php print $formVars['user'];?>&group=<?php print $formVars['group']; ?>&save=-1');
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

</div>

<div id="main">

<span id="from_mysql"></span>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
