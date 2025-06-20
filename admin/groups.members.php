<?php
# Script: groups.members.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_Admin);

  $package = "groups.members.php";

  logaccess($db, $_SESSION['uid'], $package, "Accessing script");

  $formVars['id'] = 0;
  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  }


# if help has not been seen yet,
  if (show_Help($db, $Sitepath . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Group Member Listing</title>

<style type='text/css' title='currentStyle' media='screen'>
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

$(document).ready( function() {
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>


<div id="main">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">Group Member Listing</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('group-help');">Help</a></th>
</tr>
</table>

<div id="group-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>This page shows all the users that are members of the selected group.</p>

</div>

</div>

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">User Name</th>
  <th class="ui-state-default">Email Address</th>
</tr>
<?php

  $class = "ui-widget-content";
  $q_string  = "select usr_id,usr_name,usr_first,usr_last,usr_email ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_group = " . $formVars['id'] . " ";
  $q_string .= "group by usr_name ";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_st_users) > 0) {
    while ($a_st_users = mysqli_fetch_array($q_st_users)) {
      print "<tr>\n";
      print "<td class=\"" . $class . "\">" . $a_st_users['usr_name'] . "</td>\n";
      print "<td class=\"" . $class . "\">" . $a_st_users['usr_email'] . "</td>\n";
      print "</tr>\n";
    }
  }

?>
</table>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
