<?php
# Script: epics.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('2');

  $package = "epics.php";

  logaccess($_SESSION['uid'], $package, "Viewing the Data Center Location table");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Epics</title>

<style type='text/css' title='currentStyle' media='screen'>
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.status.js"></script>

<script type="text/javascript">

<?php
  if (check_userlevel(1)) {
?>
function delete_epic( p_script_url ) {
  var answer = confirm("Deleting an Epic will also delete all associated User Stories\n\nDelete this Epic?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}
<?php
  }
?>

function attach_epic( p_script_url, update ) {
  var ae_form = document.epics;
  var ae_url;

  ae_url  = '?update='   + update;
  ae_url += '&id='       + ae_form.id.value;

  ae_url += "&epic_jira="         + encode_URI(ae_form.epic_jira.value);
  ae_url += "&epic_title="        + encode_URI(ae_form.epic_title.value);
  ae_url += "&epic_closed="       + ae_form.epic_closed.checked;

  script = document.createElement('script');
  script.src = p_script_url + ae_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('epics.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickAddEpic' ).click(function() {
    $( "#dialogEpic" ).dialog('open');
  });

  $( "#dialogEpic" ).dialog({
    autoOpen: false,
    modal: true,
    height: 180,
    width: 1100,
    show: 'slide',
    hide: 'slide',
    closeOnEscape: true,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogEpic" ).hide();
    },
    buttons: [
      {
        text: "Cancel",
        click: function() {
          show_file('epics.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update Epic",
        click: function() {
          attach_epic('epics.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Epic",
        click: function() {
          attach_epic('epics.mysql.php', 0);
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
  <th class="ui-state-default">Epic Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('epic-help');">Help</a></th>
</tr>
</table>

<div id="epic-help" style="display: none">

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
  <td class="ui-widget-content button"><input type="button" id="clickAddEpic" value="Add Epic"></td>
</tr>
</table>

</form>

<span id="epic_mysql"><?php print wait_Process('Waiting...')?></span>

</div>


<div id="dialogEpic" title="Epic Form">

<form name="epics">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default" colspan="3">Epic Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Jira: <input type="text" name="epic_jira" size="10"></td>
  <td class="ui-widget-content">Epic: <input type="text" name="epic_title" size="90"></td>
  <td class="ui-widget-content">Close: <input type="checkbox" name="epic_closed"></td>
</tr>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
