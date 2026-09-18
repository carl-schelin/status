<?php
# Script: tickets.member.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_Admin);

  $package = "member.tickets.php";

  logaccess($db, $_SESSION['uid'], $package, "Viewing the tickets table");

  $formVars['id'] = clean($_GET['id'], 10);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Ticket Review</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<form name="mainform">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">Ticket Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ticket-help');">Help</a></th>
</tr>
</table>

<div id="ticket-help" style="display: none">

<div class="main-help ui-widget-content">


</div>

</div>

</form>

<?php

  $q_string  = "select tik_number ";
  $q_string .= "from st_tickets ";
  $q_string .= "where tik_id = " . $formVars['id'] . " ";
  $q_st_tickets = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
  if (mysqli_num_rows($q_st_tickets) > 0) {
    $a_st_tickets = mysqli_fetch_array($q_st_tickets);
  }

  print "<table class=\"ui-styled-table\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">Ticket " . $a_st_tickets['tik_number'] . " Details</th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<table class=\"ui-styled-table\">\n";

  $q_string  = "select strp_task ";
  $q_string .= "from st_status ";
  $q_string .= "where strp_ticket = " . $formVars['id'] . " ";
  $q_string .= "order by strp_id ";
  $q_st_status = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
  if (mysqli_num_rows($q_st_status) > 0) {
    while ($a_st_status = mysqli_fetch_array($q_st_status)) {

      $class = 'ui-widget-content';

      print "<tr>\n";
      print "<td class=\"" . $class . "\">" . $a_st_status['strp_task'] . "</td>\n";
      print "</tr>\n";
    }
  }

  print "</table>\n";

?>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
