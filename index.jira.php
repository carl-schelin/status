<?php
# Script: index.jira.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.jira.php";

  logaccess($db, $formVars['username'], $package, "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Jira</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">


<div class="main ui-widget-content">

<ul>
  <li><a href="<?php print $Jiraroot; ?>/epics.php">Jira Epic Topics.</a></li>
  <li><a href="<?php print $Jiraroot; ?>/userstories.php">Jira User Stories.</a></li>
</ul>

</div>


</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
