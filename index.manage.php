<?php
# Script: index.manage.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.manage.php";

  logaccess($db, $formVars['username'], $package, "Checking out the index.");

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
  <li><a href="<?php print $Adminroot; ?>/class.php">Manage the various classifications.</a></li>
  <li><a href="<?php print $Adminroot; ?>/progress.php">Manage the task progress.</a></li>
  <li><a href="<?php print $Adminroot; ?>/type.php">Manage the task types.</a></li>
  <li><a href="<?php print $Adminroot; ?>/titles.php">Manage Titles.</a></li>
</ul>

</div>


</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
