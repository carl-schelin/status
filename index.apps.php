<?php
# Script: index.apps.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.apps.php";

  logaccess($formVars['username'], $package, "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Server Management System</title>

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

<div class="main ui-widget-content">

<ul>
<?php
  if (check_userlevel($AL_Supervisor)) {
?>
  <li><a href="<?php print $Statusroot; ?>/managers.php">Management View</a> - View status reports for your reports.</li>
<?php
  }
?>
  <li><a href="<?php print $Reportroot; ?>/completed.php">Show All Completed Tasks</a></li>
  <li><a href="<?php print $Siteroot; ?>/search.php">Search Task Database</a></li>
</ul>

</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
