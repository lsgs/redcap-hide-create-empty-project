********************************************************************************
# Hide Create Empty Project

Luke Stevens, Murdoch Children's Research Institute https://www.mcri.edu.au

[https://github.com/lsgs/redcap-hide-create-empty-project](https://github.com/lsgs/redcap-hide-create-empty-project)
********************************************************************************
## Summary

**Hides the "Empty project (blank slate)" option when creating/requesting a new project** so users must select a template for their new project (or create from an ODM XML file).

If user role names are configured at the system level then the user who creates/requests the project will be added to the first matching role in the project.

### Create New Project

When creating or requesting a new project in REDCap, users are presented with three choices:
* Empty project (blank slate)
* Upload a REDCap project XML file (CDISC ODM format)?
* Clinical Data Mart: create a project and pull multiple records from Epic [where enabled]
* Use a template (choose one below) 

This External Module hides the "Empty project (blank slate)" option, forcing the user to use a template (or create from an ODM XML file).

#### Default behaviour

<img alt="with empty project option" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=3544cbf7fb5e999c3e3122d41bc1c97dddb8524afce17dd04a5b65ea909d02e94713d13d06949bfd8fa45bed819cc3798238fb06c26ce2764e0f10efb608f25b&id=2215454&s=KSqRoICnLEuQBqR2&page=file_page&record=22&event_id=47634&field_name=thefile&instance=1" />

#### With this module enabled

<img alt="without empty project option" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=5788cf6e36a86a0aa1be5cdbe0dfb1b9bca3a61d7b673e570902f9ae0877dac8a5521e3ef998bade2ff5e4ce5ddad76de71181afb2dab04125e599af57cd5c66&id=2215456&s=h8XEFayAI83xNAqz&page=file_page&record=23&event_id=47634&field_name=thefile&instance=1" />

### Template Projects: Default User Roles

Set up each of your project templates with a range of suitable default user roles, e.g. for the "Project Owner", "Data Entry", "Investigator", "Data User" etc. 

This illustrates the User Roles functionality and encourages users to make use of it rather than managing their project users' permissions individually.


## System-Level Settings

<img alt="module settings" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=bf95b261b4955e6421343803ddea5543a5d18dc4521d81f3a53d32de0c9c55402c66e60fcf1e29ff192430a343a0f2aabd6e14979d4f1c99c543008d70b944db&id=2215457&s=cvPJUys7fpuZVorb&page=file_page&record=24&event_id=47634&field_name=thefile&instance=1" />

### Enable for All Projects

This module is designed to be used with the "Enable module on all projects by default" option swithched *ON*.

### Hide "Add with custom rights" Option

Select this box to hide the "Add with custom rights" option on project User Rights pages. This further encourages use of user roles and discourages permission management at the individual user level.

<img alt="no custom rights option" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=1e5e584cd178a67ba6d96bcd5cfafbd458b34234f62606c7e65029ee4193939fbbb41376441ca3ebbe5ce04487f89485314583e2e462bbf1dff1a29c6b90690a&id=2215458&s=azeUGgLchCyAkEw5&page=file_page&record=25&event_id=47634&field_name=thefile&instance=1" />

### Default User Role Names

The module system-level configuration enables the administrator to specify a series of user role names. The user that creates or requests the project will be automatically assigned to the first matched role in the new project.

<img alt="single project user auto-assigned to role" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=0b845a82219ea7559aa909603a39ac66cb6b60cc27f0bed751860c3f712d2c25193385f4e1c7e2891120e2f90a4b849ed76eb699ba1b7cfc20ca9a8445f0434b&id=2215459&s=a4isvwY6Dg4esfbg&page=file_page&record=26&event_id=47634&field_name=thefile&instance=1" />