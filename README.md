# advanced_events
a drupal-based event calendar and room reservation system. Includes code for migrating existing registration from e-Vanced to the drupal installation

#project status
The project in this repo was tanked late in the development. I am sharing this -- and was given permission by Westlake Porter Public Library -- in order that someone else can get some use out of it. I do not expect to pursue further development of it, but who knows. I am happy to answer questions about what I have done, for however long I remember.
Snide comments are my own. :-)

#drupal files
All modules/themes used for this fully functional system are included in the file structure. 
The #1 user name is: administrator and the password is "password." You'll probably want to change that.

#roles
There are three roles related to usage of the site:
Events Coordinator - this role has the most permissions related to events and room reservations, but not full administrator privileges
Scheduler - for staff who schedule events/programs
Staff Desk - for use at desks for scheduling study rooms

Add/edit/delete roles to suit your usage.

#taxonomies
Event Category and Age Group taxonomies were created to reflect a local evanced implementation. When migrating content, keep your taxonomies exact and then change values if desired after the migration process is completed.

#database file
the mysql file in the drupal directory provides a fully-configured site without any event or room node content. Room reservation is handled by the drupal.org/project/agreservation module. See that module's documentation for info on setting up rooms. 

There are a few sample rooms and room types included. The agreservations module's way of handling rooms makes sense given that it is designed for managing hotel rooms. 

#date issues 
The date module is a dev release. The stable release would display on the month view as the first of the month, and the year. The development release will display only Month Year.

Date fields should be considered to UTC.

The reservation content type includes separate fields to account for the total booking time of an event, and the actual event time for public events.
The date field requires a year, which is redundant for event time; so I used drupal.org/project/timefield which doesn't capture date information at all. This reduces the number of clicks when creating events. 

#Reservation nodes
The fields for this content type are based on one library's usage of the evanced system. You may need additional fields. 

drupal.org/project/multi_node_add module is used for creating nodes in bulk, especially when entering a large number of related events that cannot easily be scheduled using date repeat functions.

views bulk operations is used for editing nodes in bulk. 
#Feeds/tamper
Feeds module has been set up to import nodes based on event data generated from evanced's room reservation reports in csv format. 
Produce a csv file for whatever date range you choose and select whatever fields you require. Make sure they correlate to fields in your Reservation content type. Then alter the mappings in the feeds module. 
In the feeds tamper configuration, date modules have already been set with date formats for evanced data.

#Importing existing registration data
the file evanced_reginfo_migrate.php is a simple php script that will take existing patron registration data from evanced and import to the new nodes' entity references for the entity registration module, based in the nid. Registration information must be generated for each event individually and exported to csv files. This will be kinda tedious, but better than manually entering such data. 

There could be a way to do this in a less time-consuming by working from the evanced database, but that was a question for later development.

#things I didn't get to, and might work on again if the spirit moves me
a scheduling system for posting events to social-networking 
Creating a custom module that will recommend programs that are similar to those attended by patrons
a better method for migrating registration information.

If you charge for meeting room usage (the library this was developed for doesn't) agreseravations module integrates with Commerce (I think).
