<?php
# Script: status.report.php
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

  $package = "status.report.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET["user"], 10);
  $formVars['group']     = clean($_GET["group"], 10);
  $formVars['startweek'] = clean($_GET["startweek"], 10);

  if ($formVars['user'] == '') {
    $formVars['user'] = $_SESSION['uid'];
  }

  if ($formVars['startweek'] == '') {
    $today = date('w');
    $friday = 5 - $today;
    $thisweek = date('Y-m-d', mktime(0, 0, 0, date('m'), date("d") + $friday, date("Y")));

    $q_string  = "select wk_id,wk_date ";
    $q_string .= "from st_weeks ";
    $q_string .= "where wk_date = \"" . $thisweek . "\" ";
    $q_st_weeks = mysqli_query($db, $q_string);
    $a_st_weeks = mysqli_fetch_array($q_st_weeks);

    $formVars['startweek'] = $a_st_weeks['wk_id'];
  }

  if ($formVars['group'] == '') {
    $formVars['group'] = 0;
  }

  logaccess($db, $_SESSION['username'], "status.report.php", "Adding status data: startweek=" . $formVars['startweek'] . " user=" . $formVars['user']);

  $dow = date('w');

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from users";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_users = mysqli_fetch_array($q_users)) {
    if ($_SESSION['username'] == $a_users['usr_name']) {
      $formVars['id'] = $a_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], "status.report.php", "Escalated privileged access to " . $formVars['id']);
  }

  $q_string  = "select usr_template,usr_projects ";
  $q_string .= "from users ";
  $q_string .= "where usr_id = \"" . $formVars['user'] . "\"";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_users = mysqli_fetch_array($q_users);

  $q_string  = "select COUNT(cls_id) ";
  $q_string .= "from st_class ";
  $q_string .= "where cls_template = " . $a_users['usr_template'];
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_class = mysqli_fetch_array($q_st_class);

  $numclass = $a_st_class['COUNT(cls_id)'];

  $q_string  = "select cls_id ";
  $q_string .= "from st_class ";
  $q_string .= "where cls_template = " . $a_users['usr_template'];
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_class = mysqli_fetch_array($q_st_class);

  $class = $a_st_class['cls_id'];

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add Tasks to Weekly Status Report</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this task?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}

function touch_project() {

  if (navigator.appName == "Microsoft Internet Explorer") {
    document.getElementById('prjready').className = "ui-state-highlight";
  } else {
    document.getElementById('prjready').setAttribute("class","ui-state-highlight");
  }

}

function touch_progress() {

  if (navigator.appName == "Microsoft Internet Explorer") {
    document.getElementById('proready').className = "ui-state-highlight";
  } else {
    document.getElementById('proready').setAttribute("class","ui-state-highlight");
  }

}

function touch_type() {

  if (navigator.appName == "Microsoft Internet Explorer") {
    document.getElementById('typready').className = "ui-state-highlight";
  } else {
    document.getElementById('typready').setAttribute("class","ui-state-highlight");
  }

}

function touch_time() {

  if (navigator.appName == "Microsoft Internet Explorer") {
    document.getElementById('timready').className = "ui-state-highlight";
  } else {
    document.getElementById('timready').setAttribute("class","ui-state-highlight");
  }

}

function touch_day(ctlId) {

  for (i = 0; i < 7; i++) {
    if (ctlId == i) {
      if (navigator.appName == "Microsoft Internet Explorer") {
        document.getElementById('dayready' + i).className = "ui-state-highlight";
      } else {
        document.getElementById('dayready' + i).setAttribute("class","ui-state-highlight");
      }
    } else {
      if (navigator.appName == "Microsoft Internet Explorer") {
        document.getElementById('dayready' + i).className = "ui-widget-content";
      } else {
        document.getElementById('dayready' + i).setAttribute("class","ui-widget-content");
      }
    }
  }
  if (document.taskmgr.daily.checked) {
    show_daily();
  }
}

function attach_userstories( p_script_url ) {
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function show_daily() {

  day = 0;
  for (i = 0; i < 7; i++) {
    if (document.taskmgr.day[i].checked) {
      day = i;
    }
  }

  if (document.taskmgr.daily.checked) {
    show_file('status.report.mysql.php?startweek=<?php print $formVars['startweek'];?>&user=<?php print $formVars['user'];?>&class=<?php print $class;?>&daily=' + day);
  } else {
    show_file('status.report.mysql.php?startweek=<?php print $formVars['startweek'];?>&user=<?php print $formVars['user'];?>&class=<?php print $class;?>');
  }

}

function clear_fields() {
  if (navigator.appName == "Microsoft Internet Explorer") {
    document.getElementById('prjready').className = "ui-widget-content";
    document.getElementById('proready').className = "ui-widget-content";
    document.getElementById('typready').className = "ui-widget-content";
    document.getElementById('tskready').className = "ui-widget-content";
    document.getElementById('timready').className = "ui-widget-content";
    document.getElementById('dayready0').className = "ui-widget-content";
    document.getElementById('dayready1').className = "ui-widget-content";
    document.getElementById('dayready2').className = "ui-widget-content";
    document.getElementById('dayready3').className = "ui-widget-content";
    document.getElementById('dayready4').className = "ui-widget-content";
    document.getElementById('dayready5').className = "ui-widget-content";
    document.getElementById('dayready6').className = "ui-widget-content";
  } else {
    document.getElementById('prjready').setAttribute("class","ui-widget-content");
    document.getElementById('proready').setAttribute("class","ui-widget-content");
    document.getElementById('typready').setAttribute("class","ui-widget-content");
    document.getElementById('tskready').setAttribute("class","ui-widget-content");
    document.getElementById('timready').setAttribute("class","ui-widget-content");
    document.getElementById('dayready0').setAttribute("class","ui-widget-content");
    document.getElementById('dayready1').setAttribute("class","ui-widget-content");
    document.getElementById('dayready2').setAttribute("class","ui-widget-content");
    document.getElementById('dayready3').setAttribute("class","ui-widget-content");
    document.getElementById('dayready4').setAttribute("class","ui-widget-content");
    document.getElementById('dayready5').setAttribute("class","ui-widget-content");
    document.getElementById('dayready6').setAttribute("class","ui-widget-content");
  }

  show_file('status.report.mysql.php?startweek=<?php print $formVars['startweek'];?>&user=<?php print $formVars['user'];?>&class=<?php print $class;?>');
}


function touch_class() {

  for (i = 0; i < <?php print $numclass;?>; i++) {
    if (document.taskmgr.report[i].checked) {
      if (navigator.appName == "Microsoft Internet Explorer") {
        document.getElementById('clsready' + (i + <?php print $class;?>)).className = "ui-state-highlight";
      } else {
        document.getElementById('clsready' + (i + <?php print $class;?>)).setAttribute("class","ui-state-highlight");
      }
    } else {
      if (navigator.appName == "Microsoft Internet Explorer") {
        document.getElementById('clsready' + (i + <?php print $class;?>)).className = "ui-widget-content";
      } else {
        document.getElementById('clsready' + (i + <?php print $class;?>)).setAttribute("class","ui-widget-content");
      }
    }
  }

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

  daily = "";
  if (document.taskmgr.daily.checked) {
    daily = "&daily=" + day;
  }

// create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url + "&class=" + atchclass + "&day=" + day + "&id=" + document.taskmgr.id.value + daily;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function textCounter(field,cntfield,maxlimit) {
  if (navigator.appName == "Microsoft Internet Explorer") {
    document.getElementById('tskready').className = "ui-state-highlight";
  } else {
    document.getElementById('tskready').setAttribute("class","ui-state-highlight");
  }

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
  $class    = clean($_GET["class"], 10);
  $type     = clean($_GET["type"], 10);
  $time     = clean($_GET["time"], 10);
  $day      = clean($_GET["day"], 10);
  $project  = clean($_GET["project"], 10);
  $progress = clean($_GET["progress"], 10);

?>

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="taskmgr">

<table class="ui-widget-content">
<?php

// Show the week
  $q_string  = "select wk_date ";
  $q_string .= "from st_weeks ";
  $q_string .= "where wk_id = " . $formVars['startweek'] . " ";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_weeks = mysqli_fetch_array($q_st_weeks);

// Show the user
  $q_string  = "select usr_first,usr_last,usr_group ";
  $q_string .= "from users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $username = mysqli_fetch_array($q_users);

  print "<tr>\n";
  echo "  <th class=\"ui-state-default\" title=\"The week tasks will be recorded for. Click the &lt; and &gt; to move to different weeks. Click the date to go to your timecard.\" colspan=2><b><a href=\"" . $Statusroot . "/status.report.php?startweek=" . ($formVars['startweek'] - 1) . "&user=" . $formVars['user'] . "\">Prior Week &lt;</a> ";

  print "<a href=\"" . $Statusroot . "/timecard.php?startweek=" . $formVars['startweek'] . "&endweek=" . $formVars['startweek'] . "&user=" . $formVars['user'] . "&group=0\">Week Ending: " . $a_st_weeks['wk_date'] . "</a>";

  print " <a href=\"" . $Statusroot . "/status.report.php?startweek=" . ($formVars['startweek'] + 1) . "&user=" . $formVars['user'] . "\">&gt; Next Week</a></b></th>\n";
  print "</tr>\n";
?>
<tr>
  <td class="ui-widget-content button" title="Update Task/Add Task button. Click this once you're done entering data." colspan=2>
<input type="button" disabled="true" name="copy" value="Copy Task to Next Week" onClick="javascript:attach_file('status.report.mysql.php?update=0&startweek=<?php print ($formVars['startweek'] + 1);?>&user=<?php print $formVars['user'];?>&user_jira=' + user_jira.value + '&type=' + tcktype.value + '&progress=' + progress.value + '&project=' + project.value + '&time=' + time.value + '&task=' + encodeURIComponent(task.value) + '&save=' + save.checked + '&quarter=' + quarter.checked + '&docopy=1');">
<input type="hidden" name="id" value="0">
<input type="button" disabled="true" name="update" value="Update This Task" onClick="javascript:attach_file('status.report.mysql.php?update=1&startweek=<?php print $formVars['startweek'];?>&user=<?php print $formVars['user'];?>&user_jira=' + user_jira.value + '&type=' + tcktype.value + '&progress=' + progress.value + '&project=' + project.value + '&time=' + time.value + '&task=' + encodeURIComponent(task.value) + '&save=' + save.checked + '&quarter=' + quarter.checked + '&docopy=0');">
<input type="button" value="Add New Task" onClick="javascript:attach_file('status.report.mysql.php?update=0&startweek=<?php print $formVars['startweek'];?>&user=<?php print $formVars['user'];?>&user_jira=' + user_jira.value + '&type=' + tcktype.value + '&progress=' + progress.value + '&project=' + project.value + '&time=' + time.value + '&task=' + encodeURIComponent(task.value) + '&save=' + save.checked + '&quarter=' + quarter.checked + '&docopy=0');"></td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <td class="ui-widget-content" title="The Project description." id="prjready" colspan="2">Project: <select name="project" onclick="touch_project();">
  <option value="0">N/A
<?php 
// Generate the project array.

  $q_string  = "select prj_id,prj_desc ";
  $q_string .= "from st_project ";
  $q_string .= "where prj_group = " . $username['usr_group'] . " and prj_close = 0 ";
  $q_string .= "order by prj_desc";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_project = mysqli_fetch_array($q_st_project)) {

    if (strlen($a_users['usr_projects']) == 0) {
      print "  <option value=\"" . $a_st_project['prj_id'] . "\">" . $a_st_project['prj_desc'] . "</option>\n";
    } else {
      $projectid = "/:" . $a_st_project['prj_id'] . ":/i";
      if (preg_match($projectid, $a_users['usr_projects'])) {
        print "  <option value=\"" . $a_st_project['prj_id'] . "\">" . $a_st_project['prj_desc'] . "</option>\n";
      }
    }
  }

?>
</select></td>
  <td class="ui-widget-content" title="Progress of the task." id="proready">Progress: <select name="progress" onclick="touch_progress();">
  <option selected value="0">N/A
<?php
  $q_string  = "select pro_id,pro_name ";
  $q_string .= "from st_progress ";
  $q_string .= "order by pro_id";
  $q_st_progress = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_progress = mysqli_fetch_array($q_st_progress) ) {
    print "  <option value=\"" . $a_st_progress['pro_id'] . "\">" . $a_st_progress['pro_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" title="Type of task." id="typready">Type: <select name="tcktype" onclick="touch_type();">
  <option value="0">N/A
<?php

// Generate the type array.
  $q_string  = "select typ_id,typ_name ";
  $q_string .= "from st_type ";
  $q_string .= "order by typ_id";
  $q_st_type = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_type = mysqli_fetch_array($q_st_type) ) {
    print "  <option value=\"" . $a_st_type['typ_id'] . "\">" . $a_st_type['typ_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">Jira Epic: <select name="epic_jira" onchange="javascript:attach_userstories('status.report.options.php?epic_id=' + epic_jira.value);">
<option value="0">No Epic for these User Stories.</option>
<?php
  $q_string  = "select epic_id,epic_jira,epic_title ";
  $q_string .= "from st_epics ";
  $q_string .= "where epic_user = 5 and epic_closed = 0 ";
  $q_string .= "order by epic_jira ";
  $q_st_epics = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_epics = mysqli_fetch_array($q_st_epics)) {
    print "  <option value=\"" . $a_st_epics['epic_id'] . "\">" . $a_st_epics['epic_jira'] . " - " . $a_st_epics['epic_title'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="2">Jira User Story: <select name="user_jira">
<?php
  $q_string  = "select user_id,user_jira,user_task ";
  $q_string .= "from st_userstories ";
  $q_string .= "where user_user = 5 and user_epic = 0 and user_closed = 0 ";
  $q_string .= "order by user_jira ";
  $q_st_userstories = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_userstories = mysqli_fetch_array($q_st_userstories)) {
    print "  <option value=\"" . $a_st_userstories['user_id'] . "\">" . $a_st_userstories['user_jira'] . " - " . $a_st_userstories['user_task'] . "</option>\n";
  }
?>
</select></td>
<tr>
  <td class="ui-widget-content" title="Task Description, whether to put it in the weekly status report, and save as an accomplishment." id="tskready" colspan=8><textarea name="task" cols=90 rows=3 onKeyDown="textCounter(document.taskmgr.task,document.taskmgr.remLen,255);" onKeyUp="textCounter(document.taskmgr.task,document.taskmgr.remLen,255);"></textarea><br><label for="save"><input type="checkbox" checked id="save" name="save"> Use in Status Report email</abel> <label for="quarter"><input type="checkbox" id="quarter" name="quarter"> Noted Accomplishment</label> <input readonly type="text" name="remLen" size="3" maxlength="3" value="255"> characters left</td>
</tr>
</table>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default" colspan=8>Timecard Data</th>
</tr>
<tr>
  <td class="ui-widget-content" title="How long you worked on the task. 15 minute increments." id="timready">Time Worked: <input type="text" name="time" size=3 value="2" onchange="touch_time();"> (15 min increments) <label for="daily"><input type="checkbox" id="daily" name="daily" onchange="show_daily();"> Show selected day</label></td>
<?php
# get the current week; $a_st_weeks['wk_date']
# break into a timestamp variable: this is Friday
  $timestamp = strtotime($a_st_weeks['wk_date']);
# 

  $fullweekday[0] = "Sunday";
  $fullweekday[1] = "Monday";
  $fullweekday[2] = "Tuesday";
  $fullweekday[3] = "Wednesday";
  $fullweekday[4] = "Thursday";
  $fullweekday[5] = "Friday";
  $fullweekday[6] = "Saturday";

  $printday[0] = date("m/d", $timestamp - (86400 *  5));
  $printday[1] = date("m/d", $timestamp - (86400 *  4));
  $printday[2] = date("m/d", $timestamp - (86400 *  3));
  $printday[3] = date("m/d", $timestamp - (86400 *  2));
  $printday[4] = date("m/d", $timestamp - (86400 *  1));
  $printday[5] = date("m/d", $timestamp - (86400 *  0));
  $printday[6] = date("m/d", $timestamp - (86400 * -1));

  for ($i = 0; $i < 7; $i++) {

    if ($dow == $i) {
      $dowchecked = "checked";
    } else {
      $dowchecked = "";
    }

    print " <td class=\"ui-widget-content\" title=\"Work you did on " . $fullweekday[$i] . " " . $printday[$i] . ".\" id=\"dayready" . $i . "\">";
    print "<label for=\"day" . $i . "\"><input type=\"radio\" " . $dowchecked . " id=\"day" . $i . "\" name=\"day\" value=\"" . $i . "\" onClick=\"touch_day(" . $i . ");\"> ";
    print substr($fullweekday[$i], 0, 3) . "</label></td>\n";

  }
?>
</tr>
<tr>
  <td class="ui-widget-content button">Daily Totals: </td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="sunday">0</span></td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="monday">0</span></td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="tuesday">0</span></td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="wednesday">0</span></td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="thursday">0</span></td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="friday">0</span></td>
  <td class="ui-widget-content button" title="Total hours for this day"><span id="saturday">0</span></td>
</tr>
</table>

<span id="from_mysql"></span>

<p><a href="<?php print $Siteroot; ?>/email.php?user=<?php print $formVars['user']; ?>&startweek=<?php print $formVars['startweek']; ?>&group=<?php print $formVars['group']; ?>">Email the status report</a></p>

<?php

if ($_SESSION['group'] < 5) {

?>

<table class="ui-widget-content">
<tr>
  <th class="ui-state-default">Support and Maintenance</th>
  <th class="ui-state-default">Administration</th>
  <th class="ui-state-default">On-Call/Afterhours</th>
</tr>
<tr>
  <td class="ui-widget-content"><b>1.1 Tickets</b></td>
  <td class="ui-widget-content"><b>2.1 Admin</b></td>
  <td class="ui-widget-content"><b>1.3 On-Call</b></td>
</tr>
<tr>
  <td class="ui-widget-content">Reactive work. Work for others such as tickets, level 4 changes, and changelog</td>
  <td class="ui-widget-content">Misc time such as PTO, time sheets, expense reports, assessments, surveys, efficiency (like clean cube or Zero Inbox)</td>
  <td class="ui-widget-content">Involuntary, non-incident work such as calls for assistance or responding to system alerts</td>
</tr>
<tr>
  <td class="ui-widget-content"><b>1.3 Maintenance</b></td>
  <td class="ui-widget-content"><b>2.3 Training</b></td>
  <td class="ui-widget-content">&nbsp;</td>
</tr>
<tr>
  <td class="ui-widget-content">Proactive work. Documentation, system improvements, infrastructure projects.</td>
  <td class="ui-widget-content">Internal or external training</td>
  <td class="ui-widget-content">&nbsp;</td>
</tr>
<tr>
  <td class="ui-widget-content"><b>1.4 Consulting</b></td>
  <td class="ui-widget-content"><b>2.4 Meetings</b></td>
  <td class="ui-widget-content">&nbsp;</td>
</tr>
<tr>
  <td class="ui-widget-content">Reactive work. Drive by questions or if you're stopped in the hallway.</td>
  <td class="ui-widget-content">General meetings like staff meetings, 1:1 time, All hands, not Project Meetings</td>
  <td class="ui-widget-content">&nbsp;</td>
</tr>
</table>

<?php

}

?>

</form>
</center>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
