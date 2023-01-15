<?php
# Script: profile.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "profile.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Yourself</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function attach_users(p_script_url, update) {
  var au_form = document.user;
  var au_url;

  au_url  = '?update='   + update;

  au_url += "&usr_first="      + encode_URI(au_form.usr_first.value);
  au_url += "&usr_last="       + encode_URI(au_form.usr_last.value);
  au_url += "&usr_email="      + encode_URI(au_form.usr_email.value);
  au_url += "&usr_phone="      + encode_URI(au_form.usr_phone.value);
  au_url += "&usr_manager="    + au_form.usr_manager.value;
  au_url += "&usr_title="      + au_form.usr_title.value;
  au_url += "&usr_theme="      + au_form.usr_theme.value;
  au_url += "&usr_passwd="     + encode_URI(au_form.usr_passwd.value);
  au_url += "&usr_reenter="    + encode_URI(au_form.usr_reenter.value);
  au_url += "&usr_reset="      + au_form.usr_reset.checked;

  script = document.createElement('script');
  script.src = p_script_url + au_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('profile.fill.php');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<form name="user">

<div id="main">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">User Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('user-help');">Help</a></th>
</tr>
</table>

<div id="user-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Profile Form</strong>
  <ul>
    <li><strong>First Name</strong> - The user's first name.</li>
    <li><strong>Last Name</strong> - The user's last name.</li>
    <li><strong>Theme</strong> - Select a theme for the user.</li>
    <li><strong>E-Mail</strong> - The user's official email address. This is important in that several email portions of the system check incoming email against this address.</li>
    <li><strong>Phone Number</strong> - The user's contact phone number. Could be desk phone or cell phone.</li>
  </ul></li>
  <li><strong>Password Form</strong>
  <ul>
    <li><strong>Reset User Password</strong> - Enter in a new password for the user here.</li>
    <li><strong>Re-Enter Password</strong> - Enter the password in again. If the passwords don't match, the two boxes <span class="ui-state-highlight">change to indicate</span> a mismatch</li>
    <li><strong>Force Password Reset on Next Login</strong> - Check this box if you're resetting a user password or otherwise want to force a password reset.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table">
<tr>
  <td class="button ui-widget-content">
<input type="button" disabled="true" name="update" value="Update"  onClick="javascript:attach_users('profile.mysql.php', 1);">
  </td>
</tr>
</table>

<p></p>

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default" colspan="5">Profile Form</th>
</tr>
<tr>
  <td class="ui-widget-content">First Name <input type="text" name="usr_first" size="20"></td>
  <td class="ui-widget-content">Last Name <input type="text" name="usr_last" size="20"></td>
  <td class="ui-widget-content">E-Mail <input type="email" name="usr_email" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Phone Number <input type="phone" name="usr_phone" size="20"></td>
  <td class="ui-widget-content">Theme <select name="usr_theme">
<?php
  $q_string  = "select theme_id,theme_title ";
  $q_string .= "from st_themes ";
  $q_string .= "order by theme_title";
  $q_st_themes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_tst_hemes = mysqli_fetch_array($q_st_themes)) {
    print "<option value=\"" . $a_st_themes['theme_id'] . "\">" . $a_st_themes['theme_title'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Select Your Title: <select name="usr_title">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select tit_id,tit_name ";
  $q_string .= "from st_titles ";
  $q_string .= "order by tit_name ";
  $q_st_titles = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_titles = mysqli_fetch_array($q_st_titles)) {
    print "<option value=\"" . $a_st_titles['tit_id'] . "\">" . $a_st_titles['tit_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="3">Select Your Manager: <select name="usr_manager">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select usr_id,usr_last,usr_first ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_disabled = 0 ";
  $q_string .= "order by usr_last,usr_first ";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_users = mysqli_fetch_array($q_st_users)) {
    print "<option value=\"" . $a_st_users['usr_id'] . "\">" . $a_st_users['usr_last'] . ", " . $a_st_users['usr_first'] . "</option>\n";
  }
?>
</select></td>
</table>

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default" colspan="3">Password Form</th>
</tr>
<tr>
  <td class="ui-widget-content" id="password">Reset User Password <input type="password" autocomplete="off" name="usr_passwd" size="30" onKeyDown="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" onKeyUp="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);"></td>
  <td class="ui-widget-content" id="reenter">Re-Enter Password <input type="password" name="usr_reenter" size="30" onKeyDown="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);" 
onKeyUp="javascript:show_file('validate.password.php?password=' + usr_passwd.value + '&reenter=' + usr_reenter.value);"></td>
  <td class="ui-widget-content"><label>Force Password Reset on Next Login? <input type="checkbox" checked="true" name="usr_reset"></label></td>
</tr>
</table>

</div>

</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
