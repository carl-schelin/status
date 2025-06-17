<?php
# Script: class.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "class.mysql.php";
    $formVars['update']        = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']              = clean($_GET['id'],           10);
        $formVars['cls_name']        = clean($_POST['class'],       70);
        $formVars['cls_template']    = clean($_POST['template'],    10);
        $formVars['cls_project']     = clean($_POST['project'],     10);
        $formVars['cls_title']       = clean($_POST['title'],      100);
        $formVars['cls_help']        = clean($_POST['help'],       100);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['cls_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "cls_name     = \"" . $formVars['cls_name']     . "\"," .
            "cls_template =   " . $formVars['cls_template'] . "," .
            "cls_project  =   " . $formVars['cls_project']  . "," .
            "cls_title    = \"" . $formVars['cls_title']    . "\"," .
            "cls_help     = \"" . $formVars['cls_help']     . "\" ";

          if ($formVars['update'] == 0) {
            $query = "insert into st_class set cls_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update st_class set " . $q_string . " where cls_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['cls_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";

      $output .= "<table class=\"ui-widget-content\">\n";
      $output .= "<tr>\n";
      if (check_userlevel($db, $AL_Admin)) {
        $output .= "  <th class=\"ui-state-default\" width=\"160\">Delete Classification</th>";
      }
      $output .= "  <th class=\"ui-state-default\">Classification</th>\n";
      $output .= "  <th class=\"ui-state-default\">Template #</th>\n";
      $output .= "  <th class=\"ui-state-default\">Project</th>\n";
      $output .= "  <th class=\"ui-state-default\">Title</th>\n";
      $output .= "  <th class=\"ui-state-default\">Help</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select cls_id,cls_name,cls_template,cls_project,cls_title,cls_help ";
      $q_string .= "from st_class ";
      $q_string .= "order by cls_id";
      $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_class) > 0) {
        while ($a_st_class = mysqli_fetch_array($q_st_class)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('class.fill.php?id=" . $a_st_class['cls_id'] . "');jQuery('#dialogUpdate').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('class.del.php?id="  . $a_st_class['cls_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          if (check_userlevel($db, $AL_Admin)) {
            $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel   . "</td>";
          }
          $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_class['cls_name']       . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"              . $a_st_class['cls_template']              . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"              . $a_st_class['cls_project']               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"              . $a_st_class['cls_title']                 . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"              . $a_st_class['cls_help']                  . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      mysqli_free_result($q_st_class);

      $output .= "</table>\n";

      print "document.getElementById('table_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.formUpdate.cls_name.value = '';\n";
      print "document.formUpdate.cls_template.value = '';\n";
      print "document.formUpdate.cls_project.value = '';\n";
      print "document.formUpdate.cls_title.value = '';\n";
      print "document.formUpdate.cls_help.value = '';\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
