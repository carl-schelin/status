<?php
# Script: index.manage.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.manage.php";

  logaccess($formVars['username'], $package, "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage The Database</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">


<div class="main ui-widget-content">

<ul>
  <li><a href="requests/add.class.php">Manage the various classifications.</a></li>
  <li><a href="requests/add.progress.php">Manage the task progress.</a></li>
  <li><a href="requests/add.type.php">Manage the task types.</a></li>
</ul>

</div>


</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
