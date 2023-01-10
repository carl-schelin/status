<?php
# Script: index.custom.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.custom.php";

  logaccess($db, $formVars['username'], $package, "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Special Requests</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">


<div class="main ui-widget-content">

<h2>Ryan Alanis</h2>

<ul>
  <li><a href="<?php print $Requestsroot; ?>/ralanis.training.php">List the training for your reports.</a></li>
</ul>

</div>


</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
