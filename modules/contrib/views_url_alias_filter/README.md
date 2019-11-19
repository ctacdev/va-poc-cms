## CONTENTS OF THIS FILE

 - Introduction
 - Requirements
 - Installation
 - Configuration
 - Todo

## INTRODUCTION

The 'Views URL Alias Filter' module allows views to be filtered by path aliases.

This module is useful if you want to filter your views
(rest export, page etc.) result by URL alias i.e. if you want
to pass the URL alias value in views contextual filter.

The module converts the URL alias passed in query parameter
for your page or REST API
end-point to it's entity ID and filters the views result based on that.
This module will give you a provision to choose the following option
'URL alias to entity ID' from views contextual filter configuration section,
where you will get a text-box to fill the query parameter.

You have to select the 'Content: ID' along with
the 'URL alias to entity ID' present under 'Provide default value' option
from views contextual filter, as it is converting the alias value
to it's entity ID in-order to filter the views result.

Currently, only node aliases are supported.

## REQUIREMENTS

This module requires the following module:

* Views (https://www.drupal.org/project/views)

## INSTALLATION

* Install as usual.
* Copy/upload the module to the modules directory of your Drupal installation.
* Enable the 'Views URL Alias Filter' module from modules
  listing page (admin/modules).

## CONFIGURATION

* Choose 'Content: ID' in views contextual filter configuration to get
  the intended result

* Choose 'URL alias to entity ID' option present under
 'Provide default value' and enter the query parameter

## TODO

* Extending support of URL aliases for taxonomy terms, users etc. .

## AUTHOR/MAINTAINER

 - Subhadeep Bhandari
   https://www.drupal.org/user/3610698
