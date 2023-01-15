<?php
# Script: userstories.php
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

  $package = "userstories.php";

  logaccess($db, $_SESSION['uid'], $package, "Viewing the userstories table");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage User Stories</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

<?php
  if (check_userlevel($db, $AL_Developer)) {
?>
function delete_story( p_script_url ) {
  var answer = confirm("Delete this User Story?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}
<?php
  }
?>

function attach_story( p_script_url, update ) {
  var as_form = document.userstories;
  var as_url;

  as_url  = '?update='   + update;
  as_url += '&id='       + as_form.id.value;

  as_url += "&user_epic="         + as_form.user_epic.value;
  as_url += "&user_jira="         + encode_URI(as_form.user_jira.value);
  as_url += "&user_task="         + encode_URI(as_form.user_task.value);
  as_url += "&user_closed="       + as_form.user_closed.checked;

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('userstories.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickAddStory' ).click(function() {
    $( "#dialogStory" ).dialog('open');
  });

  $( "#dialogStory" ).dialog({
    autoOpen: false,
    modal: true,
    height: 200,
    width: 1100,
    show: 'slide',
    hide: 'slide',
    closeOnEscape: true,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogStory" ).hide();
    },
    buttons: [
      {
        text: "Cancel",
        click: function() {
          show_file('userstories.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update User Story",
        click: function() {
          attach_story('userstories.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add User Story",
        click: function() {
          attach_story('userstories.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<form name="mainform">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">User Story Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('story-help');">Help</a></th>
</tr>
</table>

<div id="story-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Buttons</strong>
  <ul>
    <li><strong>Update Location</strong> - Save any changes to this form.</li>
    <li><strong>Add Location</strong> - Create a new location record. You can copy an existing location by editing it, changing a field and saving it again.</li>
  </ul></li>
</ul>

<ul>
  <li><strong>Location Form</strong>
  <ul>
    <li><strong>Name</strong> Enter the descriptive name of the Location.</li>
    <li><strong>Suite</strong> If the devices are in a suite, enter that here.</li>
    <li><strong>Address</strong> Enter the street address. The second Address is for additional information regarding the address.</li>
    <li><strong>Select a Location</strong> This is a list of cities, states, and countries that can be selected for this data center.</li>
    <li><strong>Default</strong> Checking this puts this location into the default Home Page Data Center drop down box. Default sites are <span class="ui-state-highlight">highlighted</span>.</li>
    <li><strong>Zipcode</strong> The location zipcode.</li>
    <li><strong>CLLI Prefix</strong> The Standard Naming Convention server name prefix for this location. Four character city plus two character state plus data center instance number.</li>
    <li><strong>West Designation</strong> The 5 character code identifying a data center for West.</li>
  </li></ul>
  <li><strong>Location Contact Form</strong> - Provide contact information for a location.</li>
  <li><strong>Location Access Form</strong> - Provide a link to additional documentation on how a field engineer can access this site.</li>
  <li><strong>Network Grid Form</strong> - Future: for use in creating a site map.</li>
</ul>

</div>

</div>

<table class="ui-styled-table">
<tr>
  <td class="ui-widget-content button"><input type="button" id="clickAddStory" value="Add User Story"></td>
</tr>
</table>

</form>

<span id="story_mysql"><?php print wait_Process('Waiting...')?></span>

</div>


<div id="dialogStory" title="User Story Form">

<form name="userstories">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default" colspan="3">User Story Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Epic: <select name="user_epic">
<option value="0">No Epic</option>
<?php
  $q_string  = "select epic_id,epic_jira,epic_title ";
  $q_string .= "from st_epics ";
  $q_string .= "where epic_user = " . $_SESSION['uid'] . " ";
  $q_string .= "order by epic_jira ";
  $q_st_epics = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
  while ($a_st_epics = mysqli_fetch_array($q_st_epics)) {
    print "<option value=\"" . $a_st_epics['epic_id'] . "\">" . $a_st_epics['epic_jira'] . " - " . $a_st_epics['epic_title'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Jira: <input type="text" name="user_jira" size="10"></td>
  <td class="ui-widget-content">User Story: <input type="text" name="user_task" size="90"></td>
  <td class="ui-widget-content">Close: <input type="checkbox" name="user_closed"></td>
</tr>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
