********************************************************************************
# Hide New Empty Project Option

Luke Stevens, Murdoch Children's Research Institute https://www.mcri.edu.au

********************************************************************************
## Summary

When creating or requesting a new project in REDCap, users are presented with 
three choices:
* 0 Create an empty project (blank slate)
* 2 Upload a REDCap project XML file (CDISC ODM format)?
* 1 Use a template (choose one below) 

This External Module hides the "Create an empty project (blank slate)", forcing 
users to use a template (or ODM XML upload).

### Default User Roles

Setting up templates enables REDCap administrators to define suitable default 
user roles, e.g. for the "Project Owner", "Data Entry", "Investigator", "Data 
User" etc.

The module system-level configuration enables the administrator to specify the 
name of a user role (the default is "Project Owner") to which the user that 
creates or requests the project will be automatically assigned once a project 
has been created from a template.
