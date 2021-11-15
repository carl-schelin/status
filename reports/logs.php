<?php
# Script: logs.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($AL_User);

  $package = "logs.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>View Logs</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default">ID</th>
  <th class="ui-state-default">User</th>
  <th class="ui-state-default">Date</th>
  <th class="ui-state-default">Script</th>
  <th class="ui-state-default">Detail</th>
</tr>
<?php

  $date = date('Y-m');

  $q_string  = "select log_id,log_user,log_date,log_source,log_detail ";
  $q_string .= "from log ";
  $q_string .= "where log_date like '$date%' ";
  $q_string .= "order by log_id";
  $q_log = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_log = mysql_fetch_array($q_log)) {

    $time = explode(" ", $a_log['log_date']);

    print "<tr>\n";
    print "  <td class=\"ui-widget-content\">" . $a_log['log_id'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_log['log_user'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $time[0] . "&nbsp;" . $time[1] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_log['log_source'] . "</td>\n";
    print "  <td class=\"ui-widget-content\">" . $a_log['log_detail'] . "</td>\n";
    print "</tr>\n";
  }

?>
</table>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
