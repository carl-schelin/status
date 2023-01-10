<?php
if (isset($_SESSION['username'])) {
  print "<li id=\"tm_account\"><a href=\"" . $Siteroot . "/index.account.php\">Account (" . $_SESSION['username'] . ")</a>\n";
} else {
  print "<li id=\"tm_account\"><a href=\"" . $Siteroot . "/index.account.php\">Account</a>\n";
}
?>
    <ul>
<?php
  if (isset($_SESSION['username'])) {
?>
      <li><a href="<?php print $Adminroot;  ?>/profile.php">Account Profile</a></li>
      <li><a href="<?php print $Bugroot; ?>/add.bandf.php">Report a Bug</a></li>
      <li><a href="<?php print $FAQroot; ?>/whatsnew.php">What's New?</a></li>
      <li><a href="<?php print $Reportroot; ?>/users.php">User Status Logs</a></li>
      <li><a href="<?php print $Loginroot; ?>/logout.php">Logout (<?php print $_SESSION['username']; ?>)</a></li>
<?php
    if (check_userlevel($db, $AL_Admin)) {
?>
      <li><a href="">-------------------------</a></li>
      <li><a href="<?php print $Adminroot; ?>/users.php">User Management</a></li>
      <li><a href="<?php print $Adminroot; ?>/groups.php">Group Management</a></li>
      <li><a href="<?php print $Adminroot; ?>/levels.php">Access Level Management</a></li>
      <li><a href="<?php print $Loginroot; ?>/assume.php">Change Credentials</a></li>
      <li><a href="<?php print $Reportroot;  ?>/logs.php">View Last 30 Days of Logs</a></li>
<?php
    }
  } else {
?>
      <li><a href="<?php print $Loginroot; ?>/login.php">Login</a></li>
<?php
  }
?>
    </ul>
  </li>
</ul>

</div>

</div>

<p></p>

