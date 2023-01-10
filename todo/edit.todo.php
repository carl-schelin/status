<?php
# Script: todo.php
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

  $package = "edit.todo.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET['user'], 10);
  $formVars['startweek'] = clean($_GET['startweek'], 4);

  if ($formVars['user'] == 0) {
    $formVars['user'] = 1;
  }

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 1;
  }

  logaccess($db, $_SESSION['username'], "edit.todo.php", "Editing todo detail records: week=" . $formVars['startweek'] . " user=" . $formVars['user']);

  $q_string = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_users = mysqli_fetch_array($q_users) ) {
    if ($_SESSION['username'] == $a_users['usr_name']) {
      $formVars['id'] = $a_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], "edit.status.php", "Escalated privileged access to " . $formVars['id']);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Todo List</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function attach_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function delete_line( p_script_url ) {
  var answer = confirm("Delete this line?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php

#######
# Retrieve the group info from the user
#######

  $q_string  = "select usr_group,usr_template ";
  $q_string .= "from users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $a_users = mysqli_fetch_array($q_users);

#######
# Retrieve all the weeks into the weekval array
#######

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from weeks";
  $q_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_weeks = mysqli_fetch_array($q_weeks) ) {
    $weekval[$a_weeks['wk_id']] = $a_weeks['wk_date'];
  }
  $weektot = count($weekval);

#######
# Retrieve all the classifications into the classval array
#######

  $q_string  = "select cls_id,cls_name ";
  $q_string .= "from st_class ";
  $q_string .= "where cls_template = " . $a_users['usr_template'];
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $class = 0;
  while ( $a_st_class = mysqli_fetch_array($q_st_class) ) {
    $classid[$class]    = $a_st_class['cls_id'];
    $classval[$class++] = $a_st_class['cls_name'];
  }
  $clastot = $class;

#######
# Retrieve all the projects into the projval array
#######

  $q_string  = "select prj_id,prj_desc ";
  $q_string .= "from project ";
  $q_string .= "where prj_group = " . $a_users['usr_group'] . " ";
  $q_string .= "order by prj_name";
  $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $project = 0;
  while ( $a_project = mysqli_fetch_array($q_project) ) {
    $projval[$project][0] = $a_project['prj_id'];
    $projval[$project++][1] = $a_project['prj_desc'];
  }
  $projtot = count($projval);

#######
# Now process the status reports
#######

  print "<form name=\"update\">\n";

  print "<table class=\"ui-widget-content\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">Description</th>\n";
  print "  <th class=\"ui-state-default\">Classification</th>\n";
  print "  <th class=\"ui-state-default\">Save</th>\n";
  print "  <th class=\"ui-state-default\">Delete</th>\n";
  print "</tr>\n";

  $q_string  = "select todo_id,todo_name,todo_class ";
  $q_string .= "from todo ";
  $q_string .= "where todo_completed = 0 and todo_user = " . $formVars['user'] . " ";
  $q_string .= "order by todo_due,todo_project";
  $q_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_todo = mysqli_fetch_array($q_todo) ) {

    print "<tr>\n";

    print "  <td class=\"ui-widget-content\" id=\"task_" . $a_todo['todo_id'] . "\"><input type=\"text\" name=\"task_" . $a_todo['todo_id'] . "\" size=\"107\" value=\"" . $a_todo['todo_name'] . "\"></td>\n";

    print "  <td class=\"ui-widget-content\" id=\"clas_" . $a_todo['todo_id'] . "\"><select name=\"clas_" . $a_todo['todo_id'] . "\">\n";
    for ($i = 0; $i < $clastot; $i++) {
      if ($a_todo['todo_class'] == $classid[$i]) {
        $selected = " selected";
      } else { 
        $selected = "";
      }
      print "<option" . $selected . " value=\"" . $classid[$i] . "\">" . $classval[$i] . "\n";
    }
    print "</select></td>\n";

    print "  <td class=\"ui-widget-content delete\" id=\"save_" . $a_todo['todo_id'] . "\"><input type=\"button\" value=\"Save\" onClick=\"javascript:attach_file('edit.todo.mysql.php?id=" . $a_todo['todo_id'] . "&task=' + task_" . $a_todo['todo_id'] . ".value + '&class=' + clas_" . $a_todo['todo_id'] . ".value);\"></td>\n";
    print "  <td class=\"ui-widget-content delete\" id=\"del_" . $a_todo['todo_id'] . "\"><input type=\"button\" value=\"Del\" onClick=\"javascript:delete_line('del.todo.mysql.php?id=" . $a_todo['todo_id'] . "');\"></td>\n";

  }
?>

</table>
</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
