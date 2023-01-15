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

  $package = "todo.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");


  $formVars['user']  = 0;
  $formVars['week']  = 0;
  $formVars['group'] = 0;

  $formVars['user']  = clean($_GET["user"], 10);
  $formVars['week']  = clean($_GET["week"], 10);
  $formVars['group'] = clean($_GET["group"], 10);

### This bit returns the index for the current week. Not everyone uses just the todo function.
  $today = date('w');
  $friday = 5 - $today;
  $thisweek = date('Y-m-d', mktime(0, 0, 0, date('m'), date("d") + $friday, date("Y")));

  $q_string = "select wk_id,wk_date ";
  $q_string .= "from st_weeks ";
  $q_string .= "where wk_date = \"" . $thisweek . "\" ";
  $q_st_weeks = mysqli_query($db, $q_string);
  $a_st_weeks = mysqli_fetch_array($q_st_weeks);

  if ($formVars['week'] == 0) {
    $formVars['week'] = $a_st_weeks['wk_id'];
  }
###

  logaccess($db, $_SESSION['username'], "todo.php", "Adding todo data: week=" . $formVars['week'] . " user=" . $formVars['user']);

  $q_string = "select usr_id,usr_name ";
  $q_string .= "from st_users";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_users = mysqli_fetch_array($q_st_users) ) {
    if ($_SESSION['username'] == $a_st_users['usr_name']) {
      $formVars['id'] = $a_st_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], "todo.php", "Escalated privileged access to " . $formVars['id']);
  }

  $q_string  = "select usr_template,usr_projects ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id = \"" . $formVars['user'] . "\"";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $q_string  = "select COUNT(cls_id) ";
  $q_string .= "from st_class ";
  $q_string .= "where cls_template = " . $a_st_users['usr_template'];
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));;
  $a_st_class = mysqli_fetch_array($q_st_class);

  $numclass = $a_st_class['COUNT(cls_id)'];

  $q_string  = "select cls_id ";
  $q_string .= "from st_class ";
  $q_string .= "where cls_template = " . $a_st_users['usr_template'];
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));;
  $a_st_class = mysqli_fetch_array($q_st_class);

  $class = $a_st_class['cls_id'];


######
# Retrieve all the user info into the userval array
######

  $q_string = "select usr_id,usr_first,usr_last,usr_group from st_users where (usr_id = " . $formVars['id'];
  if (check_userlevel($db, $AL_VicePresident)) {
    $q_string .= " or usr_vicepresident = " . $formVars['id'];
  } else {
    if (check_userlevel($db, $AL_Director)) {
      $q_string .= " or usr_director = " . $formVars['id'];
    } else {
      if (check_userlevel($db, $AL_Manager)) {
        $q_string .= " or usr_manager = " . $formVars['id'];
      } else {
        if (check_userlevel($db, $AL_Supervisor)) {
          $q_string .= " or usr_supervisor = " . $formVars['id'];
        }
      }
    }
  }
  $q_string .= ") and usr_id != 1 or usr_group = " . $_SESSION['group'] . " and usr_disabled = 0 order by usr_last";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $count = 0;
  while ( $a_st_users3 = mysqli_fetch_array($q_st_users) ) {
    $userid[$count] = $a_st_users3['usr_id'];
    $usergrp[$count] = $a_st_users3['usr_group'];
    $userval[$count++] = $a_st_users3['usr_last'] . ", " . $a_st_users3['usr_first'];
  }
  $usertot = $count;

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Todo List</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this item?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}

function show_all() {
  if (document.getElementById('showall').checked) {
    showall=1;
  } else {
    showall=0;
  }
  if (document.getElementById('showyour').checked) {
    showyour=1;
  } else {
    showyour=0;
  }

  show_file('todo.mysql.php?week=<?php print $formVars['week'];?>&user=<?php print $formVars['user'];?>&showall=' + showall + '&showyour=' + showyour);
}

function clear_fields() {
  show_file('todo.mysql.php?week=<?php print $formVars['week'];?>&user=<?php print $formVars['user'];?>&class=<?php print $class;?>');
}

function show_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_file( p_script_url ) {
  atchclass = 0;
  for (i = 0; i < <?php print $numclass;?>; i++) {
    if (document.taskmgr.report[i].checked) {
      atchclass = i + <?php print $class;?>;
    }
  }

  day = 0;
  for (i = 0; i < 7; i++) {
    if (document.taskmgr.day[i].checked) {
      day = i;
    }
  }

// create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url + "&class=" + atchclass + "&day=" + day + "&id=" + document.taskmgr.id.value;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_menu( p_script_url ) {
// create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
  else
    cntfield.value = maxlimit - field.value.length;
}

<?php

  $class = 0;
  $type = 0;
  $day = 0;
  $time = 2;
  $project = 0;
  $progress = 0;
  // Set, clean the GETed values
  if (isset($_GET['class'])) {
    $class    = clean($_GET["class"], 10);
  }
  if (isset($_GET['type'])) {
    $type     = clean($_GET["type"], 10);
  }
  if (isset($_GET['time'])) {
    $time     = clean($_GET["time"], 10);
  }
  if (isset($_GET['day'])) {
    $day      = clean($_GET["day"], 10);
  }
  if (isset($_GET['project'])) {
    $project  = clean($_GET["project"], 10);
  }
  if (isset($_GET['progress'])) {
    $progress = clean($_GET["progress"], 10);
  }

?>

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="taskmgr">

<table class="ui-styled-table">
<?php

// Show the week
  $q_string  = "select wk_date ";
  $q_string .= "from st_weeks ";
  $q_string .= "where wk_id = " . $formVars['week'] . " ";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $weekname = mysqli_fetch_array($q_st_weeks);

// Show the user
  $q_string  = "select usr_first,usr_last,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id = " . $formVars['user'] . " ";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $username = mysqli_fetch_array($q_st_users);

  print "<tr>\n";
  print "  <th class=\"ui-state-default\" title=\"The week tasks will be recorded for. Click the &lt; and &gt; to move to different weeks. Click the date to go to your status report.\" colspan=2><b><a href=\"" . $Todoroot . "/todo.php?week=" . ($formVars['week'] - 1) . "&user=" . $formVars['user'] . "\">&lt;</a> ";

  print "<a href=\"" . $Statusroot . "/status.report.php?startweek=" . $formVars['week'] . "&user=" . $formVars['user'] . "&group=0\">" . $weekname['wk_date'] . "</a>";

  print " <a href=\"" . $Todoroot . "/todo.php?week=" . ($formVars['week'] + 1) . "&user=" . $formVars['user'] . "\">&gt;</a></b></th>\n";
  print "</tr>\n";
?>
<tr>
  <td class="button ui-widget-content" title="Add to Status, Update, and Add Todo button. Click this once you're done entering data." colspan=2>
<input type="button" disabled="true" name="status" value="Add Item to Status" onClick="javascript:attach_file('todo.mysql.php?update=2&week=<?php print $formVars['week'];?>&user=<?php print $formVars['user'];?>&project=' + project.value + '&duedate=' + duedate.value + '&time=' + time.value + '&task=' + task.value + '&completed=' + completed.checked + '&showall=' + showall.checked + '&showyour=' + showyour.checked + '&save=' + save.checked + '&todo_priority=' + todo_priority.value + '&todo_status=' + todo_status.value);">
<input type="hidden" name="id" value="0">
<input type="button" disabled="true" name="update" value="Update Todo Item" onClick="javascript:attach_file('todo.mysql.php?update=1&week=<?php print $formVars['week'];?>&user=<?php print $formVars['user'];?>&project=' + project.value + '&duedate=' + duedate.value + '&time=' + time.value + '&task=' + task.value + '&completed=' + completed.checked + '&showall=' + showall.checked + '&showyour=' + showyour.checked + '&assign=' + assign.value + '&save=' + save.checked + '&todo_priority=' + todo_priority.value + '&todo_status=' + todo_status.value);">
<input type="button" value="Add Todo Item" onClick="javascript:attach_file('todo.mysql.php?update=0&week=<?php print $formVars['week'];?>&user=<?php print $formVars['user'];?>&project=' + project.value + '&duedate=' + duedate.value + '&time=' + time.value + '&task=' + task.value + '&completed=' + completed.checked + '&showall=' + showall.checked + '&showyour=' + showyour.checked + '&assign=' + assign.value + '&save=' + save.checked + '&todo_priority=' + todo_priority.value + '&todo_status=' + todo_status.value);"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content">Project: <select name="project">
  <option value="0">N/A
<?php
// Generate the project array.

  $q_string  = "select prj_id,prj_desc ";
  $q_string .= "from st_project ";
  $q_string .= "where prj_group = " . $_SESSION['group'] . " and prj_close = 0 ";
  $q_string .= "order by prj_desc";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ($a_st_project = mysqli_fetch_array($q_st_project)) {

    if (strlen($a_st_users['usr_projects']) == 0) {
      print "  <option value=\"" . $a_st_project['prj_id'] . "\">" . $a_st_project['prj_desc'] . "</option>\n";
    } else {
      $projectid = "/:" . $a_st_project['prj_id'] . ":/i";
      if (preg_match($projectid, $a_st_users['usr_projects'])) {
        print "  <option value=\"" . $a_st_project['prj_id'] . "\">" . $a_st_project['prj_desc'] . "</option>\n";
      }
    }
  }

# old way:
#  $q_string  = "select prj_id,prj_desc ";
#  $q_string .= "from st_project ";
#  $q_string .= "where prj_group = " . $_SESSION['group'] . " and prj_close = 0 ";
#  $q_string .= "order by prj_desc";
#  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

#  while ($a_st_project = mysqli_fetch_array($q_st_project)) {
#    print "  <option value=\"" . $a_st_project['prj_id'] . "\">" . $a_st_project['prj_desc'] . "</option>\n";
#  }

?>
</select></td>
  <td class="ui-widget-content">Status: <select name="todo_status">
<option value="0">Required</option>
<option value="1">Desired</option>
</select></td>
  <td class="ui-widget-content">Priority: <input type="text" name="todo_priority" size="4"></td>
  <td class="ui-widget-content" title="The week the todo item is due." colspan=3> Due Date: <select name="duedate">
  <option value="0">N/A
<?php
  $q_st_weeks = mysqli_query($db, "select * from st_weeks") or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_weeks = mysqli_fetch_array($q_st_weeks) ) {
    if ($a_st_weeks['wk_id'] == $formVars['week']) {
      $selected = "selected ";
    } else {
      $selected = "";
    }

    print "  <option " . $selected . "value=\"$a_st_weeks[0]\">$a_st_weeks[1]</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="4"> Assign To: <select name="assign">
  <option value="0">Unassigned
<?php
  for ($i = 0; $i < $usertot; $i++) {
    $selected="";
    if ($formVars['id'] == $userid[$i]) {
      $selected=" selected";
    }
    print "  <option value=\"" . $userid[$i] . "\"$selected \\ onclick=\"javascript:attach_menu('todo.option.php?user=" . $userid[$i] . "')\">" . $userval[$i] . "\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content" title="Todo Description, mark as completed, and show or hide all completed tasks." colspan=8><textarea name="task" cols=90 rows=3 onKeyDown="textCounter(document.taskmgr.task,document.taskmgr.remLen,255);" onKeyUp="textCounter(document.taskmgr.task,document.taskmgr.remLen,255);"></textarea><br>
<input type="checkbox" checked id="save" name="save"> Use in Todo Email 
<input type="checkbox" id="completed" name="completed"> Task Completed 
<input readonly type="text" name="remLen" size="3" maxlength="3" value="255"> characters left</td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" title="This data is used for day due and time estimates for Todo items or what day and how much time was spent for Status Reports." colspan=8>Status Report Data</th>
</tr>
<tr>
  <td class="ui-widget-content" width=50%>Time Worked: <input type="text" name="time" size=3 value="2"> <input type="checkbox" id="showall" name="showall" onchange="show_all();"> Show all Tasks <input type="checkbox" id="showyour" name="showyour" onchange="show_all();"> Show just your Tasks</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="0"> Sun</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="1"> Mon</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="2"> Tue</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="3"> Wed</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="4"> Thu</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="5"> Fri</td>
  <td class="ui-widget-content"><input type="radio" name="day" value="6"> Sat</td>
</tr>
</table>

<span id="from_mysql">
</span>

</form>
</center>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
