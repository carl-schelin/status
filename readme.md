### Status Management

The purpose behind this application is to have a centralized place to describe tasks and then generate a 
weekly email which is sent to management. You have the option of identifying specific work done that 
merits elevation. This gives you an easily block of important work that can be brought to management's 
attention during your performance reviews.

An additional bit of help is it lets you add the information for tasks and then at the end of the week, 
easily update any Jira tickets as all the work you did all week can be summarized in the Jira ticket.

Finally, you can review a range of data for a year end review.

### Documents

There are two documents. The one you're reading now and an [Installation document](install/install.md). 
When initially installing the status management application, follow the installation guide to get things 
set up. Return to this document as it provides background information and recommendations so you can 
begin entering data for your day to day tasks.

The recommendations here are from my own experience. In general you should be able to make changes to 
how things work without there being an impact. Of course if there's a problem, feel free to let me 
know via github where you retrieved this application.


### How We Got Here

Management requested a weekly "status report" from his team with a list of what work was done during the 
week. While we have tickets and other tools such as Jira, they're a bit harder to keep track of everything 
including shoulder-taps. Plus some work are in tickets, which are closed, and some are with Jira which 
are also closed. This app puts the data in a single location that can be reviewed at any time.

Initially it was an email but that's annoying. So I created a very simple application which let me enter 
data and create an email from it to send to my supervisor. As someone who has written programs for years, 
the app was initially created to permit the use by others even though it was just for me at the time. So 
the core of this application is simply that. Enter what you did every day and at the end of the week, send 
an email to your boss. Typically I'd send it to myself so I could massage it if necessary.

I also worked on an Inventory/Asset Management application. Also a Shadowrun Character Manager. As I 
progressed, I updated the main applications I've created to follow the same techniques. Hence my apps 
generally look very similar. Where before it was a simple application where you just checked the box for the 
header and entered the data for the item. Then clicked to send an email. It turned into the nicer 3.0 
application with a nice title bar, menu to perform tasks, date ranges, and so on.

As time went on, I added the checkbox for using the entry in the email. For example, I log my lunch time 
but it doesn't need to be part of the email to management.

The boss also wanted me to highlight any cool things so I added a Noted Accomplishment checkbox.

Next up we started using a timecard application called iConnect. So I added code that formatted the output 
into something you can easily transcribe into iConnect. Clicking on the Week Ending link in the title bar 
switched you to the timecard.

The boss then wanted a pie chart. He provided an example of what he expected a work week to look like. So I 
created a pie chart based on what tasks you entered and displayed it next to the example pie chart.


### Recommended Data

Before you can enter your daily data, you'll need to go through the menus and make sure everything is in place.

The assumption here is you entered the default data; admin account, task progresses, task types, and created 
a template for entering data.

Much of these entries are based on how I did things in the past. Of course you can enter some or all of the 
menu items I did or simply create your own.

#### Task Progress

I added three items here.

* Complete - The task has been completed.
* Ongoing - This is used for tasks that are never completed or may take a long time to complete.
* In Progress - Tasks that you are working on but haven't quite finished yet.

#### Task Types

My manager wanted to know what were "shoulder taps" aka drive by requests, and what were in 
response to a ticket. In order to identify others, I had Other for a bit but discovered everything else 
was a meeting so changed it.

* Meeting - Meeting type task.
* Reactive - Contacted by someone else to work a task, drive-by requests.
* Proactive - You discovered the issue and are working it.

#### Templates

This data is used to set up a block of fields used when adding tasks. Here is a basic Template:

* Classification - This is the single line that describes the data that tasks will be assigned to. 
For example, a General Classification might be set up for regular department meetings, assigned training, 
and documentation.
* Template Number: This is mainly used when pulling the data from the database to be displayed.
* Project: In order to identify tasks that are project oriented vs day to day maintenance, meetings, 
and such, identify a Classification as tied to Projects. Assign a 0 to non-project Classifications, 1 
to a Project, and if more than one, increase the number.
* Title: This is the selectable title. The user will select the appropriate Template for their manager. 
This lets you set up different Templates for different teams.
* Help: This is simply alt-text that comes up when you hover over the Classification.

##### Example Template

This is the template my manager originally provided. It's simply the breakdown of tasks so he could 
see what we were working on from week to week.

Tasks you create will be tied to these items. Changing the Template for a user means all the tasks 
previously entered will not be visible under the new selected Template.

| Classification | Template # | Project | Template Title | Help Text
| General | 1 | 0 | [Manager's Name] | General ticket work for users or department Tasks like meetings.
| Escalations | 1 | 0 | [Manager's Name] | Manager intervention for the task to continue to be worked.
| Events/Incidents | 1 | 0 | [Manager's Name] | Work done as a result of an incident or a planned or emergency event.
| Projects | 1 | 1 | [Manager's Name] | Tasks that are part of a project.
| Infrastructure | 1 | 0 | [Manager's Name] | Work that affects a group of servers.
| Server Maintenance | 1 | 2 | [Manager's Name] | Work that affects a single server.

#### Projects

You set up projects so you can better identify tasks. For mine, there are two blocks of tasks. Work related ones and Company related ones.

Work ones are ticket work, incidents, projects, maintenance. Company related ones are time off, department type meetings, and the like.

For my manager at the time, we set up the following Work Related tasks:

* 1.1 Tickets - Any work that was due to a ticket being created.
* 1.2 Maintenance - Work maintaining servers. Writing scripts for example.
* 1.3 On-Call - Any work done after hours due to being paged or otherwise contacted.
* 1.4 Consulting - Drive by work where someone reached out for a quick answer. Typically 15 minutes or less.

For Company related tasks:

* 2.1 Admin - This might be more general administrative tasks.
* 2.2 Out of Office - Any time off. Generally when a full day and not partial days.
* 2.3 Training - Any training required, either in person/web or just self-taught training.
* 2.4 Meetings - Non-Project related meetings such as One-on-Ones or All Hands type meetings.

I did create a legend at the bottom of the task entry page that lists all the above tasks.

#### Jira

This is more optional as if you don't create any, you'll still have a default 'No Epic for these User Stories'.

But if you do, under Jira create the Epic first. This lets you select different Epics or Projects so you can 
organize the output. When that's done, then create the various User Stories. I mainly put in the subject 
of the Jira to make it easier to verify I have the User Story recorded.

After that, simply select the appropriate Epic. Under Jira User Story, it will then populate the drop down 
menu with all the User Stories associated with that Epic.

### All Done

Once everything has been prepared, you can start entering task.

### Next Up!

Now we're into the features of this application.

