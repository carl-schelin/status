<?php
# Script: tickets.php
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

  $package = "tickets.php";

  logaccess($db, $_SESSION['uid'], $package, "Viewing the tickets table");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Tickets</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

<?php
  if (check_userlevel($db, $AL_Developer)) {
?>
function delete_ticket( p_script_url ) {
  var answer = confirm("Delete this Ticket?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}
<?php
  }
?>

function attach_ticket( p_script_url, update ) {
  var at_form = document.tickets;
  var at_url;

  at_url  = '?update='   + update;
  at_url += '&id='       + at_form.id.value;

  at_url += "&tik_number="       + encode_URI(at_form.tik_number.value);
  at_url += "&tik_task="         + encode_URI(at_form.tik_task.value);
  at_url += "&tik_priority="     + at_form.tik_priority.value;
  at_url += "&tik_closed="       + at_form.tik_closed.checked;

  script = document.createElement('script');
  script.src = p_script_url + at_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('tickets.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickAddTicket' ).click(function() {
    $( "#dialogTicket" ).dialog('open');
  });

  $( "#dialogTicket" ).dialog({
    autoOpen: false,
    modal: true,
    height: 230,
    width: 600,
    show: 'slide',
    hide: 'slide',
    closeOnEscape: true,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogTicket" ).hide();
    },
    buttons: [
      {
        text: "Cancel",
        click: function() {
          show_file('tickets.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update Ticket",
        click: function() {
          attach_ticket('tickets.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Ticket",
        click: function() {
          attach_ticket('tickets.mysql.php', 0);
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
  <th class="ui-state-default">Ticket Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ticket-help');">Help</a></th>
</tr>
</table>

<div id="ticket-help" style="display: none">

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
  <td class="ui-widget-content button"><input type="button" id="clickAddTicket" value="Add Ticket"></td>
</tr>
</table>

</form>

<span id="ticket_mysql"><?php print wait_Process('Waiting...')?></span>

</div>


<div id="dialogTicket" title="Ticket Form">

<form name="tickets">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default" colspan="3">Ticket Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Ticket Number: <input type="text" name="tik_number" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Description: <input type="text" name="tik_task" size="50"></td>
</tr>
<tr>
  <td class="ui-widget-content">Priority: <select name="tik_priority">
<option value="0">Lowest Priority</option>
<option value="1">Low Priority</option>
<option value="2">Low (Migrated)</option>
<option value="3" Selected='True'>Medium Priority</option>
<option value="4">High Priority</option>
<option value="5">Highest Priority</option>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Close: <input type="checkbox" name="tik_closed"></td>
</tr>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
