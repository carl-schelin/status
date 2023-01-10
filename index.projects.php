<?php
# Script: index.projects.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.projects.php";

  logaccess($db, $formVars['username'], $package, "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Project Management</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<div class="main ui-widget-content">

<h4>Project Management</h4>

<ul>
  <li><a href="<?php print $Projectroot; ?>/timecodes.php">View Project Code Table</a> - Broken out by group.</li>
  <li><a href="<?php print $Projectroot; ?>/add.project.php?group=<?php print $formVars['group']; ?>">Add a new Project Description</a> - Go here to create a new project description.</li>
  <li><a href="<?php print $Projectroot; ?>/edit.project.php?group=<?php print $formVars['group']; ?>">Edit Project Codes</a> - Go here if you need to make a change to or close a project description. Remember that changes to the existing codes affect all members of your team. <b>Note:</b> Here you can also copy an existing project code from another group into your groups table.</li>
</ul>

</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
