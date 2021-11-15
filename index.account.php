<?php
# Script: index.account.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "index.account.php";

  logaccess($formVars['username'], $package, "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Account Management</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<?php
  if (isset($_SESSION['username'])) {
?>
<div class="main ui-widget-content">

<ul>
  <li><a href="<?php print $Adminroot;?>/profile.php">Manage Your Account Profile</a></li>
  <li><a href="<?php print $Bugroot; ?>/bugs.php">Report a Bug</a></li>
  <li><a href="<?php print $FAQroot; ?>/whatsnew.php">What's New With Status Management 3.0?</a></li>
  <li><a href="<?php print $Loginroot; ?>/logout.php">Logout (<?php print $_SESSION['username']; ?>)</a></li>
</ul>

</div>

<?php
    if (check_userlevel($AL_Admin)) {
?>
<div class="main ui-widget-content">

<h2>Administrative Tasks</h2>

<ul>
  <li><a href="<?php print $Adminroot; ?>/users.php">User Management</a></li>
  <li><a href="<?php print $Adminroot; ?>/groups.php">Group Management</a></li>
  <li><a href="<?php print $Adminroot; ?>/levels.php">Access Level Management</a></li>
  <li><a href="<?php print $Loginroot; ?>/assume.php">Change Credentials</a> - Change your login information to become another user.</li>
  <li><a href="<?php print $Reportroot;  ?>/logs.php">View Month's Logs</a></li>
</ul>

</div>

<?php
    if (check_userlevel($AL_Developer)) {
      print "<div class=\"main ui-widget-content\">\n";

      print "<h2>Development Tasks</h2>\n\n";
      print "<ul>\n";
      print "  <li><a href=\"mailusers.php\">Send a message to members of the app</a></li>\n";
      print "  <li><a href=\"add.cande.php\">Change management</a> - Enter updates to the app here.</li>\n";
      print "  <li><a href=\"add.poll.php\">Add a Poll</a> - A script I'm working in to permit the creation of polls.</li>\n";
      print "</ul>\n";

      print "</div>\n";
    }
?>

<?php
  }
} else {
?>
<div class="main ui-widget-content">

<ul>
  <li><a href="<?php print $Loginroot; ?>/login.php">Login</a></li>
</ul>

</div>

<?php
}
?>
</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
