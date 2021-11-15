<?php
# Script: whatsnew.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath  . '/guest.php');

  $package = "whatsnew.php";

  logaccess($formVars['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>What's New!</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<div id="tabs">

<ul>
  <li><a href="#intro">What's New?</a></li>
  <li><a href="#home">Home Page</a></li>
  <li><a href="#bugs">Bugs!</a></li>
  <li><a href="#features">Feature Requests</a></li>
  <li><a href="#about">About Carl</a></li>
</ul>


<div id="intro">

<h4>What's New?</h4>

<p>The intention of this page is to show you, the user, what has changed between Status Management 2.2 (S2) and Status Management 3.0 (S3). 
There are some obvious changes such as the colors and tabs you see here but there are many other changes that have been added to S3.
By the way, you can change your color theme by going to your <a href="<?php print $Adminroot; ?>/profile.php">profile</a>.</p>

<p>Right off the bat, the biggest changes are things you are unlikely to even notice. I've gone through the entire S3 code base 
(19,579 lines) and reviewed each line. Over the course of the work on the Inventory, I created a Coding Standards page in an effort to provide 
a framework to the code. A common look of the internals. I went through the S3 code with the Coding Standards in mind and ensured 
the code conformed to the standard. There are exceptions of course as specific requirements came up but overall the code is clean 
and reasonably bug free.</p>

<p>From an error review, I examined all the final HTML Markup and addressed all errors. I also have a log file for internal script 
errors which I monitored and corrected and the web server error log file that I reviewed. None of the scripts have any syntax 
errors. These kinds of issues generate strange errors that are usually more difficult to track down.</p>

<p>You probably won't notice this either however I've moved logical bits of the code into unique subdirectories in order to have 
a better visual view and simple blocks of code to manage. I made an effort to keep the functionality of S3 and just moved some of 
the bits about to clean up the interface a little.</p>

<p>The next biggest change is the look of S3. Not the themes but the over all look and feel. The User Interface design. 
One of the main purposes was to look over the User Interface and come up with a standard view that works the same regardless of 
where you are in S3. Over the years as modules get added on, the way to access these modules changed. How to use them changed. 
This effort was taken to give you, the user, a common look and feel.</p>

<ul>
  <li>Forms with lists of data such as network interfaces are initially hidden from view regardless of which module you're in. 
Editing one of the interfaces will fill in and display the form. You can also click the Title Bar to display the form for 
new entries.</li>
  <li>Other Forms are initially visible such as your Profile page.</li>
  <li><strong>Help screens are everywhere.</strong> Most of the screens are accessible by clicking on the 'Help' on the title bar 
of the page you're on. In some cases where there is no title bar, 'Help' has been added to the main menu at the top.</li>
</ul>

<p>Let's move on to the next tab, to the right.</p>

</div>


<div id="home">

<h4>Home Page</h4>

<p>I reorganized the <strong>Inventory Reports</strong> page to be a bit more logical. The filter area is clearly different 
than the available links. The links work about the same however the <strong>Location</strong> drop down is new. Before, you 
could select a group and an Intrado product and come up with a page of systems. You can now filter it further by selecting 
a location from the drop down.</p>

<p>Some of the more <strong>General Reports</strong> were moved into their own area. The <strong>Inventory Reports</strong> 
tab is used by most folks at one time or another. The <strong>General Reports</strong> are used less often so moved away to 
reduce confusion and clutter.</p>

<p>The <strong>Group Reports</strong> also use the filters but are reports that were created for specific groups. If you 
want a report, head over to the <strong>Feature Tracker</strong>.</p>

<p><strong>Archived Reports</strong> are ones that were created and superceded or just aren't used any more. Kept around 
just in case. They may not work with all the filters.</p>

<p><strong>Filters</strong> in general haven't changed. You still have your group as the default selected group. The biggest 
update is the <strong>Filter on Location</strong> filter. This one was originally used only for Data Center walkthroughs but
it came to be useful when restricting searches to cities such as Longmont or even states. With that, I've modified the 
filter to let you select Country, State, City, and even Data Center within the City. This means you can select 'Canada' 
and get all the servers in Calgary and Toronto. As an additional feature, the Data Center Location drop down is enabled 
and by default lists a select set of Data Centers. Longmont, Englewood, Honolulu, and Miami, the locations with the largest 
amount of equipment.</p>

<p><strong>Reports</strong> use all the available filters where appropriate. There are some reports such as the Product Map 
that selecting by Location doesn't provide any benefit. I've also created the ability to view reports and menus without 
the requirement to log in to the system however that's being restricted for the moment.</p>

</div>


<div id="bugs">

<h4>Bugs!</h4>

<p>The original bug reporting system was pretty minimal and the last thing to be updated. Now the system is quite a bit more 
robust and able to let you report issues. The old bug data is there and I did work to address the reported bugs where I could.</p>


</div>

<div id="features">

<h4>Request a Feature or Enhancement</h4>

<p>The original bug system also let you request enhancements to the Inventory. This is now a new area and has also been made 
more robust. In this case though, with more room to expand, you can request things like specific reports that you can't from 
the tagging system or that is a bit more than you want to do (2,000 or 3,000 systems might be hard to tag).</p>

</div>


<div id="about">

<h4>About Carl!</h4>

<p>So what the heck is this?</p>

<p>As a consultant and contractor over the years, I've had to use something (generally a notebook) to 
keep track of the work I do. I've been doing it so long that it's become a habit so when I started 
here, I snagged a notebook and kept going.</p>

<p>As a computer hobbiest, I'm always learning how to do new things. Years ago I learned BASIC on a 
Radio Shack Color Computer. Then IBM PC BASIC, gwbasic, batch files, TurboC, Borland C/C++, and then scripting as I 
became a Unix admin. As a SysAdmin at NASA, I was using a web site to manage policies and procedures 
and manage incoming code deployments. The website was a hand built job but fairly simple even as I 
used templates to make the site consistent. As a SysAdmin at IBM I started taking some of my 
programming knowledge and desire to have system docs available via web pages to use php to display 
server information. I also started messing about with mysql for some personal inventory type stuff 
(with php) and even looked into css. So when it was time to do my yearly accomplishments here in 2008, 
I decided to whip up a database with a php front end. Because of my past as a programmer, I wrote 
the front end and databases to allow for more than one user to enter data.</p>

<p>The php scripts were pretty simple with the only requirement to be able to catagorize the data 
the same way as the e-mail Jeff wanted to see for Status Reports. This worked well for a time and any 
corrections I needed to make, I simply went into the mysql command line and made the changes. Generally 
there wasn't any need to edit or otherwise manage the data though. Any further changes were done in the 
e-mail itself.</p>

<p>Recently, the e-mail layout has changed. In addition, new project codes have been created so that 
Jeff can have a better breakdown of tasks. Add on to that that I've been learning more about css, php, 
and mysql <b>and</b> beginning to learn how to code in Javascript for some personal stuff and I started 
to see ways to improve the database and the app in general.</p>

<p>It wasn't until others started to use the tool (Todd) that I began to improve it with editing, 
timecards, project codes, and other extra capabilities. With more folks joining in, I needed to make a 
front end that they (you) can easily use. And I started getting feedback on improvements they wanted to 
see. With my own observations for changes, I moved the app to a dedicated server to have a more stable 
platform.</p>

<p>Recently I've worked with SCM in order to get a Subversion site to better manage the code and I've 
added an interested backup admin to help manage the code and site.</p>

<p>Carl Schelin</p>

</div>

</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
