<?php
# Script: users.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_Admin);

  $package = "users.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Users</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Admin)) {
?>
function delete_user( p_script_url ) {
  var question;
  var answer;

  question  = "The preference is to change the user access level from Enabled to Disabled\n";
  question += "which prevents the orphaning of user owned or identified information. Deleting\n";
  question += "the user should be done when removing duplicate records or if you know the \n";
  question += "user has no user managed information.\n\n";

  question += "Delete this User anyway?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('users.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_users(p_script_url, update) {
  var au_form = document.user;
  var au_url;

  au_url  = '?update='   + update;
  au_url += "&id="       + au_form.id.value;

  au_url += "&usr_first="      + encode_URI(au_form.usr_first.value);
  au_url += "&usr_last="       + encode_URI(au_form.usr_last.value);
  au_url += "&usr_name="       + encode_URI(au_form.usr_name.value);
  au_url += "&usr_disabled="   + au_form.usr_disabled.value;
  au_url += "&usr_level="      + au_form.usr_level.value;
  au_url += "&usr_manager="    + au_form.usr_manager.value;
  au_url += "&usr_title="      + au_form.usr_title.value;
  au_url += "&usr_email="      + encode_URI(au_form.usr_email.value);
  au_url += "&usr_phone="      + encode_URI(au_form.usr_phone.value);
  au_url += "&usr_group="      + au_form.usr_group.value;
  au_url += "&usr_theme="      + au_form.usr_theme.value;
  au_url += "&usr_passwd="     + encode_URI(au_form.usr_passwd.value);
  au_url += "&usr_reenter="    + encode_URI(au_form.usr_reenter.value);
  au_url += "&usr_reset="      + au_form.usr_reset.checked;

  script = document.createElement('script');
  script.src = p_script_url + au_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('users.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickAddUser' ).click(function() {
    $( "#dialogUser" ).dialog('open');
  });

  $( "#dialogUser" ).dialog({
    autoOpen: false,
    modal: true,
    height: 450,
    width: 1100,
    show: 'slide',
    hide: 'slide',
    closeOnEscape: true,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogUser" ).hide();
    },
    buttons: [
      {
        text: "Cancel",
        click: function() {
          show_file('users.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update User",
        click: function() {
          attach_users('users.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add User",
        click: function() {
          attach_users('users.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<form name="mainform">

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
    <li><strong>User Login</strong> - Used by the user to log in to the system. This can be changed but the user needs to know the new name.</li>
    <li><strong>User Access</strong> - It's best to disable a user to maintain any ownerships in the system. Change to Disabled to deny access to an account.</li>
    <li><strong>Edit Level</strong> - There are four levels. The site has restrictions for access. Most users are set to Edit mode since they have parts of the Inventory that they need to be able to edit.</li>
    <li><strong>Theme</strong> - Select a theme for the user.</li>
    <li><strong>First Name</strong> - The user's first name.</li>
    <li><strong>Last Name</strong> - The user's last name.</li>
    <li><strong>E-Mail</strong> - The user's official email address. This is important in that several email portions of the system check incoming email against this address.</li>
    <li><strong>Phone Number</strong> - The user's contact phone number. Could be desk phone or cell phone.</li>
    <li><strong>Group</strong> - The group the user belongs to. This gives the user ownership over editing equipment owned by that group.</li>
  </ul></li>
  <li><strong>Password Form</strong>
  <ul>
    <li><strong>Reset User Password</strong> - Enter in a new password for the user here.</li>
    <li><strong>Re-Enter Password</strong> - Enter the password in again. If the passwords don't match, the two boxes <span class="ui-state-error">change to indicate</span> a mismatch</li>
    <li><strong>Force Password Reset on Next Login</strong> - Check this box if you're resetting a user password or otherwise want to force a password reset.</li>
  </ul></li>
</ul>

</div>

</div>

<table class="ui-styled-table">
<tr>
  <td class="ui-widget-content button"><input type="button" id="clickAddUser" value="Add User"></td>
</tr>
</table>

<p></p>

<div id="tabs">

<ul>
  <li><a href="#newuser">New Users</a></li>
  <li><a href="#registered">Active Users</a></li>
  <li><a href="#develop">Developers</a></li>
  <li><a href="#admin">Admins</a></li>
  <li><a href="#vice">Vice Presidents</a></li>
  <li><a href="#director">Directors</a></li>
  <li><a href="#manager">Managers</a></li>
  <li><a href="#supervisor">Supervisors</a></li>
  <li><a href="#report">Users</a></li>
  <li><a href="#guest">Guests</a></li>
  <li><a href="#disabled">Disabled Users</a></li>
</ul>


<div id="newuser">

<span id="new_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="registered">

<span id="all_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="develop">

<span id="develop_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="admin">

<span id="admin_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="vice">

<span id="vice_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="director">

<span id="director_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="manager">

<span id="manager_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="supervisor">

<span id="supervisor_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="report">

<span id="report_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="guest">

<span id="guest_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


<div id="disabled">

<span id="disabled_users_table"><?php print wait_Process('Loading Users...')?></span>

</div>


</div>

</div>

</form>

<div id="dialogUser" title="User Form">

<form name="user">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default" colspan="5">Profile Form</th>
</tr>
<tr>
  <td class="ui-widget-content">User Login <input type="text" name="usr_name" size="10"></td>
  <td class="ui-widget-content">User Access <select name="usr_disabled">
<option value="0">Enabled</option>
<option value="1">Disabled</option>
</select></td>
  <td class="ui-widget-content">Edit Level <select name="usr_level">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select lvl_id,lvl_name ";
  $q_string .= "from st_levels ";
  $q_string .= "where lvl_disabled = 0 ";
  $q_string .= "order by lvl_id";
  $q_st_levels = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_levels = mysqli_fetch_array($q_st_levels)) {
    print "<option value=\"" . $a_st_levels['lvl_id'] . "\">" . $a_st_levels['lvl_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Theme <select name="usr_theme">
<?php
  $q_string  = "select theme_id,theme_title ";
  $q_string .= "from st_themes ";
  $q_string .= "order by theme_title";
  $q_st_themes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_themes = mysqli_fetch_array($q_st_themes)) {
    print "<option value=\"" . $a_st_themes['theme_id'] . "\">" . $a_st_themes['theme_title'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">First Name <input type="text" name="usr_first" size="20"></td>
  <td class="ui-widget-content">Last Name <input type="text" name="usr_last" size="20"></td>
  <td class="ui-widget-content" colspan="2">E-Mail <input type="text" name="usr_email" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Phone Number <input type="text" name="usr_phone" size="20"></td>
  <td class="ui-widget-content">Group <select name="usr_group">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from st_groups ";
  $q_string .= "where grp_disabled = 0 ";
  $q_string .= "order by grp_name";
  $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_groups = mysqli_fetch_array($q_st_groups)) {
    print "<option value=\"" . $a_st_groups['grp_id'] . "\">" . $a_st_groups['grp_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">Title: <select name="usr_title">
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
  <td class="ui-widget-content" colspan="2">Manager: <select name="usr_manager">
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
</tr>
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

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
