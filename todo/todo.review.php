<?php
# Script: todo.review.php
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

  $package = "todo.review.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET['user'], 10);
  $formVars['group']     = clean($_GET['group'], 4);
  $formVars['startweek'] = clean($_GET['startweek'], 4);

  if ($formVars['user'] == 0 && $formVars['user'] == 0) {
    $formVars['user'] = 1;
  }

  logaccess($db, $_SESSION['username'], "todo.review.php", "Viewing the todo review page: user=" . $user . " group=" . $formVars['group']);

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_name = '" . $_SESSION['username'] . "'";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $formVars['id'] = $a_st_users['usr_id'];

  if ($formVars['user'] != $formVars['id']) {
    logaccess($db, $_SESSION['username'], "todo.review.php", "Escalated privileged access to " . $formVars['id']);
    check_login($db, $AL_Supervisor);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Review and Email Weekly Todo Tasks</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function initial_show() {

  show_file('todo.review.mysql.php?id=0&startweek=<?php print $formVars['startweek'];?>&user=<?php print $formVars['user'];?>&group=<?php print $formVars['group']; ?>&save=-1');
}

function show_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

</script>

</head>
<body class="ui-widget-content" onLoad="initial_show();">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<span id="from_mysql">
</span>

<p><a href="todo.email.php?user=<?php print $formVars['user']; ?>&group=<?php print $formVars['group']; ?>&startweek=<?php print $formVars['startweek']; ?>">Email the todo report</a> - <a href="edit.todo.php?user=<?php print $formVars['user']; ?>&group=<?php print $formVars['group']; ?>">Edit the todo report</a></p> 

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
