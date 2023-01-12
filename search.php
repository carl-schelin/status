<?php
# Script: search.php
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

  $package = "search.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_users = mysqli_fetch_row($q_users) ) {
    if ($_SESSION['username'] == $a_users[1]) {
      $formVars['id'] = $a_users[0];
    }
  }

  if (!isset($_GET['task'])) {
    $formVars['task'] = "";
  } else {
    $formVars['task'] = clean($_GET['task'], 255);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Search Status Reports</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="search" action="">

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content button"><input type="submit" value="Search Task Database"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content"><textarea name="task" cols=90 rows=3></textarea></td>
</tr>
</table>

<?php

if (strlen($formVars['task']) > 0) {

  print "<table class=\"ui-widget-content\">\n";

// Retrieve the task array
  $count = 0;
  $q_string  = "select strp_name,strp_week,strp_task ";
  $q_string .= "from st_status ";
  $q_string .= "where strp_task like \"%" . $formVars['task'] . "%\" ";
  $q_string .= "order by strp_week,strp_task";
  $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_status = mysqli_fetch_array($q_st_status)) {

    print "<tr>\n";

    $q_string  = "select usr_name ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_st_status['strp_name'];
    $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_users = mysqli_fetch_array($q_users);

    print "  <td class=\"ui-widget-content\">" . $a_users['usr_name'] . "</td>\n";

    $q_string  = "select wk_date ";
    $q_string .= "from st_weeks ";
    $q_string .= "where wk_id = " . $a_st_status['strp_week'] . " ";
    $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_st_weeks = mysqli_fetch_array($q_st_weeks);

    print "  <td class=\"ui-widget-content\">" . $a_st_weeks['wk_date'] . "</td>\n";

    print "  <td class=\"ui-widget-content\">" . mysqli_real_escape_string($db, $a_st_status['strp_task']) . "</td>\n";
    print "</tr>\n";
    $count++;

  }

  if ($count == 0) {
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\" colspan=3>No records found.</td>\n";
    print "</tr>\n";
  }

  mysqli_free_result($q_st_status);

  print "</table>\n";

}

?>

</form>
</center>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
