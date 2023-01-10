<?php
# Script: add.bandf.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "add.bandf.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $DEBUG=0;

// Get the user information
  $q_string  = "select usr_id ";
  $q_string .= "from users ";
  $q_string .= "where usr_name = '" . $_SESSION['username'] . "'";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_users = mysqli_fetch_array($q_users);

  $formVars['id'] = $a_users['usr_id'];

// Get all the weeks into an array
  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from weeks";
  $q_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $week = 0;
  while ( $a_weeks = mysqli_fetch_array($q_weeks) ) {
    $weekval[$a_weeks['wk_id']] = $a_weeks['wk_date'];
  }
  $weektot = count($weekval) + 1;

// Now get the last week in the database to set the week drop down
  $q_string  = "select bf_week ";
  $q_string .= "from bandf ";
  $q_string .= "order by bf_week";
  $q_bandf = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_bandf = mysqli_fetch_array($q_bandf) ) {
    $week = $a_bandf['bf_week'];
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Report Bugs and Request Features</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
  else
    cntfield.value = maxlimit - field.value.length;
}

function delete_item( p_script_url ) {
  var answer = confirm("Delete this item?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}

function attach_file( p_script_url ) {
  borf = 0;
  if (document.bandf.borf[1].checked == 1) {
    borf = 1;
  }

  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url + "&id=" + document.bandf.id.value + '&borf=' + borf + '&username=' + document.bandf.username.value;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function show_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.bandf.mysql.php?update=-1');
}

</script>

</head>

<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="bandf" action="" method="POST">

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content button" title="Click this once you're done entering data.">
<input type="button" disabled="true" name="update" value="Update Bug or Feature"         onClick="javascript:attach_file('add.bandf.mysql.php?update=1&week=' + week.value + '&bftext=' + bftext.value + '&developer=' + developer.value + '&completed=' + completed.checked);">
<input type="hidden" name="id" value="0">
<input type="hidden" name="username" value="0">
<input type="button"                 name="bandf"  value="Report Bug or Request Feature" onClick="javascript:attach_file('add.bandf.mysql.php?update=0&week=' + week.value + '&bftext=' + bftext.value + '&developer=' + developer.value + '&completed=' + completed.checked);"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content" colspan=4>
    <textarea name="bftext" cols=90 rows=5 onKeyDown="textCounter(document.bandf.bftext,document.bandf.remLen,1024);" onKeyUp="textCounter(document.bandf.bftext,document.bandf.remLen,1024);"></textarea>
    <br><input readonly type="text" name="remLen" size="3" maxlength="3" value="1024"> characters left</td>
</tr>
<tr>
  <td class="ui-widget-content"><input type="radio" value="0" name="borf" checked> Is This A Feature <input type="radio" value="1" name="borf"> or a Bug?</td>
  <td class="ui-widget-content">Report Date: <select name="week">
    <option value="0" />None
<?php

  for ($i = 1; $i < $weektot; $i++) {
    $selected="";
    if ($week == $i) {
      $selected=" selected";
    }
    print "    <option value=\"" . $i . "\"$selected \\>" . $weekval[$i] . "\n";
  }
?>
</select></td>
<?php
# if developer, show the developer pages
  print "  <td class=\"ui-widget-content\">Assign task to: <select name=\"developer\"><option value=0>Unassigned</option>\n";
  $q_string  = "select usr_id,usr_last,usr_first ";
  $q_string .= "from users ";
  $q_string .= "where usr_level = 1 ";
  $q_string .= "order by usr_last";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_users = mysqli_fetch_array($q_users)) {
    print "<option value=" . $a_users['usr_id'] . ">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
  }
  print "</select></td>\n";
  print "  <td class=\"ui-widget-content\"><input type=\"checkbox\" name=\"completed\"> Task Completed?</td>\n";
?>
</tr>

</table>

</form>

</div>

<span id="from_mysql"></span>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
