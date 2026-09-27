### Installation


### Add tables

This is the set of mysql tables for the status management app.

### Need to set up the initial levels.

As the variables are used within the code, all entries need to be added

### Initial User

Need to create the administrator account.

### Weeks Update

Also need to update the st_weeks table with the dates for the last friday of each week.

See the additional file, weeks.update for 2023.

Yes, I know. Pain in the ass. That's how it goes for now. I've been working on a different app so this'll have to do for now.


### Settings File

The settings.php file needs to be updated to reflect your hostname and to add the credentials for accessing mysql you created at the first step.


### CSS files

I'm using jQuery so you'll need to install the following files for this to work correctly.

Installation

Import all the .sql files

Import all the .txt files. These prepopulate things like the admin account, group, titles, and some of the drop down menus.

Create a non-Admin group such as Unix or Linux Administration

Create a user account then as admin, approve the user for access. Make sure they're not in the Admin group. When creating projects, the group is associated with the project.



As the user, you'll need to create several items before you can successfully add daily entries.


Under the Jira menu, add the Epic for your tasks. There can be multiple Epics or just use the default No epics entry.

Under the Jira menu, at least create one non-Epic User Story.


Under the Projects menu, Click the Add Project Description line and add a couple of projects.

For this tool, I had a list of projects the Unix team used as follows. This would be the Description which is in the drop down when entering work, and the Task which is displayed for timecards. Generally the same for both fields but whatever makes it easy to understand:

1.1 Tickets
1.2 Maintenance
1.3 On-Call
1.4 Consulting
2.1 Admin
2.2 Out of Office
2.3 Training
2.4 Meetings

As we were using iConnect at the time, we had certain fields that needed to be entered so when you looked at your weekly timecard, you could easily add the work to iConnect.

For us, iConnect Project was 'Unix Systems Administration'. But it's the same name for all the Tasks. So for above, each of the Projects would have 'Unix Systems Administration' as the iConnect Project.

The Service Now field is just the id for Service Now. It can be blank if you're not using Service now.


Next up, under Database, you need to select the Classifications Table in order to create the Email Template. Generally Management wants to see what you're working on so they have some sort of layout for the information for them to see.

You'll need to create a template ID and Title to organize them all into a single email template. For the template, you'll select the same Template Number and Title for each entry. For example:

Template: 1
Title: Default Template

Project is whether this Classification is generally for Projects or a non-project one.




### Instllation process

If you're cloning/pulling, you should already have git but yea, you'll need to have git installed.

Install the following packages:

* git
* httpd
* mysql
* mysql-server
* php

For the image creation (pie chart in the timecard output mainly):

* gd
* php-gd

If after the web server is started, restart the web server

    systemctl restart httpd

### Getting Started

```
systemctl enable mysqld
systemctl enable httpd
systemctl start mysqld
systemctl start httpd
```

#### MariaDB

If you've installed Mariadb vs 8.0, this system using mysqli so you'll need to install php_mysqlnd

    dnf install -y php_mysqlnd

#### SELinux

If SELinux is installed, in the status directory, run:

    restorecon -R -v status

To manage selinux, install setroubleshoot

    dnf install -y setroubleshoot

#### MySQL/MariaDB

Once installed, run mysql_secure_installation to get it set up.

For the database, create the inventory database.

    create database status;

Create a status admin user with full rights to the inventory database.

```
CREATE USER 'statusadmin'@'localhost' IDENTIFIED BY '[password]';
GRANT ALL PRIVILEGES ON status.* TO 'statusadmin'@'localhost';
FLUSH PRIVILEGES;
```

In the sql directory, loop through the files and import them into the inventory database.

```
for IMPORT in $(ls *sql)
do
  echo ${IMPORT}
  mysql --user=root -p inventory < ${IMPORT}
done
```

You'll have to enter the password for each file.

#### Data Files

In the txt directory are multiple files used to prepopulate the status database. This data is required to set up an admin account then update various tables 
with expected defaults.

For now, you'll need to log into mysql and use the database, then just copy and paste in the information in the files.

#### Settings File

The settings.php file contains server information, mysql connection details, path variables and a few other settings. You mainly have to update the server infor
mation and connection details such as username and password to the database.

Once done, copy the settings.php file and fixsettings script into the statusroot directory and run the script. It will link the settings.php file into each directory.

Note that you can change the debugging option in the settings.php file. If you make it write errors to the screen, some aspects of the status app won't work quite as expected as the version of PHP might generate Warning messages that I haven't identified yet.

### Cascading Style Sheets

In the css directory, I have jquery.js 3.6.0, jquery-ui 1.13.1 in a jquery-ui directory, and jQuery-ui-themes in a jquery-ui-themes directory installed.

You should be able to locate a tar file in http://schelin.org/status/css.tar

### Images

In the imgs directory, I have several image files used in the system. All are necessary

* Status image header. This can be changed to a different branded value if you like and change the name in the settings.php file.
* Progress Bar images
* Pencil image to indicate editable text

You should be able to locate a tar file in http://schelin.org/status/imgs.tar

### Login Type

There are two login possibilities. Either that access to all parts of the status app requires a login account or that guests can access the app without authentication. 

Every script loads up the guest.php file which isn't part of the installation. In order to move forward, you'll need to link either the nologin.php script for guest access to guest.php

    ln nologin.php guest.php

If authentication is required, you'll need to link the login.php script to guest.php

    ln login.php guest.php


### Finished

With these tasks done, you should be able to log in to the new install with the admin:admin credentials and start adding devices.

