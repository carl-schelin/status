<?php
# Script: todo.review.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "todo.review.mysql.php";
    $formVars['update']         = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_User)) {

      $formVars['id']       = clean($_GET['id'], 10);
      $formVars['user']     = clean($_GET['user'], 10);
      $formVars['week']     = clean($_GET['startweek'], 4);
      $formVars['group']    = clean($_GET['group'], 4);
      $formVars['save']     = clean($_GET['save'], 10);

      logaccess($db, $_SESSION['username'], "todo.review.mysql.php", "Accessing script " . $formVars['id'] . ": week=" . $formVars['week'] . " user=" . $formVars['user'] . " group=" . $formVars['group'] . " save=" . $formVars['save']);

#### Save incoming data if any.

      if ($formVars['save'] >= 0) {

        logaccess($db, $_SESSION['username'], "todo.review.mysql.php", "Updating todo record " . $formVars['id'] . ": save=" . $formVars['save']);

        $q_string  = "update st_todo set ";
        $q_string .= "todo_save = " . $formVars['save'] . " ";
        $q_string .= "where todo_id = " . $formVars['id'];
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      }

#### Now retrieve the data from the db in order to create the page.

#######
# Retrieve information for the user
#######

      $q_string  = "select usr_group ";
      $q_string .= "from st_users ";
      $q_string .= "where usr_id = " . $formVars['user'];
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_users = mysqli_fetch_array($q_st_users);

      $usergroup = $a_st_users['usr_group'];

#######
# Retrieve information for the group
#######

#      $q_string = "select grp_week ";
#      $q_string .= "from st_groups ";
#      $q_string .= "where grp_id = $usergroup";
#      $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
#      $a_st_groups = mysqli_fetch_array($q_st_groups);
#
#      $groupstatus = $a_st_groups['grp_week'];

#######
# Retrieve all the weeks into the weekval array
#######

      $q_string  = "select wk_id,wk_date ";
      $q_string .= "from st_weeks";
      $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ( $a_st_weeks = mysqli_fetch_array($q_st_weeks) ) {
        $weekval[$a_st_weeks['wk_id']] = $a_st_weeks['wk_date'];
      }

#######
# Retrieve all the classifications into the classval array
#######

      $class = 0;
      $first = 0;
      $q_string  = "select cls_id,cls_name,cls_project ";
      $q_string .= "from st_class";
      $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      while ( $a_st_class = mysqli_fetch_array($q_st_class) ) {
        $classval[$a_st_class['cls_id']] = $a_st_class['cls_name'];
        $classprj[$a_st_class['cls_id']] = $a_st_class['cls_project'];
      }

#######
# Retrieve all the projects into the projval array
#######

      $project = 0;
      $q_string  = "select prj_id,prj_desc ";
      $q_string .= "from st_project"; 
      $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
        $projval[$a_st_project['prj_id']] = $a_st_project['prj_desc'];
      }

#######
# Now process the todo report
####### 
    
###
# Logic:
#  if group > 0
#   if user level = manager, get all the users that usr_manager = usr_id
#   if user level = supervisor, get all the users where usr_group = $formVars['group']
###

      if ($formVars['group'] != 0) {
        if (check_userlevel($db, $AL_Supervisor)) {
          $q_string = "select usr_id from st_users where usr_supervisor = " . $formVars['user'];
        }
        if (check_userlevel($db, $AL_Manager)) {
          $q_string = "select usr_id from st_users where usr_manager = " . $formVars['user'];
        }
        if (check_userlevel($db, $AL_Director)) {
          $q_string = "select usr_id from st_users where usr_director = " . $formVars['user'];
        }
        if (check_userlevel($db, $AL_VicePresident)) {
          $q_string = "select usr_id from st_users where usr_vicepresident = " . $formVars['user'];
        }

# restrict to group if looking at something other than the Management group.
        if ($formVars['group'] != 3 && $formVars['group'] != -1) {
          $q_string .= " and usr_group = " . $formVars['group'];
        }
# now build the user string this will have all the users that fit the above criteria
        $prtor = "";
        $u_string = "";

        $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        while ($a_st_users = mysqli_fetch_array($q_st_users)) {
          $u_string .= $prtor . "todo_user = " . $a_st_users['usr_id'];
          if ($prtor == "") {
            $prtor = " or ";
          }
        }
# if no users were found, empty group for instance, present just the user's data
        if ($u_string == "") {
          $u_string = "todo_user = " . $formVars['user'];
        }
        $managerview = " and todo_save = 1 ";
      } else {
        $u_string = "todo_user = " . $formVars['user'];
        $managerview = "";
      }

### Begin creating the output page.
      $output = "<table class=\"ui-widget-content\">";

      $q_string  = "select todo_id,todo_name,todo_class,todo_project,todo_save,todo_due,todo_completed,todo_user,todo_priority,todo_status ";
      $q_string .= "from st_todo ";
      $q_string .= "where (($u_string)$managerview) ";
#      $q_string .= "and todo_due <= " . ($formVars['week'] + 1) . " ";
      $q_string .= "and todo_completed = 0 ";
      $q_string .= "order by todo_class,todo_project,todo_due,todo_status,todo_priority,todo_name ";
      $q_st_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      while ( $a_st_todo = mysqli_fetch_array($q_st_todo) ) {

        $q_string  = "select usr_last ";
        $q_string .= "from st_users ";
        $q_string .= "where usr_id = " . $a_st_todo['todo_user'];
        $q_username = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        $a_username = mysqli_fetch_array($q_username);

        if ($class != $a_st_todo['todo_class']) {
          $class = $a_st_todo['todo_class'];
          if ($class > 0) {
            $output .= "<tr><td class=\"ui-widget-content\"><b>" . $classval[$a_st_todo['todo_class']] . "</b></td></tr>";
          }
        }

        $pre = "";
        if ($class > 0) {
          if ($classprj[$class] == 1) {
            $pre = "&nbsp;&nbsp;&nbsp;&nbsp;- ";
            if ($project != $a_st_todo['todo_project']) {
              $project = $a_st_todo['todo_project'];
              if ($project > 0) {
                $output .= "<tr><td class=\"ui-widget-content\"><b>&nbsp;&nbsp;* " . $projval[$a_st_todo['todo_project']] . "</b></td></tr>";
              }
            }
          } else {
            $pre = "&nbsp;&nbsp;- ";
          }
        }

        if ($a_st_todo['todo_save']) {
          $ready = " class=\"ui-state-highlight\"";
          $title = " title=\"Click to remove from Todo e-mail\"";
          $save = 0;
        } else {
          $ready = " class=\"ui-widget-content\"";
          $title = " title=\"Click to add to Todo e-mail\"";
          $save = 1;
        }

        $output .= "<tr><td" . $ready . $title . ">" . $pre;
        $output .= "<a href=\"#\" onclick=\"show_file('todo.review.mysql.php?id=" . $a_st_todo['todo_id'];
        $output .= "&startweek=" . $formVars['week'] . "&user=" . $formVars['user'] . "&group=" . $formVars['group'];
        $output .= "&save=" . $save . "');\">";
        if ($a_st_todo['todo_status']) {
          $output .= "Desired - ";
        } else {
          $output .= "Required - ";
        }
        $output .= $a_st_todo['todo_priority'] . " - ";
        $output .= $a_st_todo['todo_name'] . "</a>";
        if ($formVars['group'] != 0) {
          $output .= " (" . $a_username['usr_last'] . ")";
        }
        $output .= "</td></tr>";

      }
    }

    $output .= "</table>";
  }

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

