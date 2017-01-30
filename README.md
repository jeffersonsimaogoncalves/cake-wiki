![CakePHP 3 Websocket  Plugin](https://raw.githubusercontent.com/scherersoftware/cake-wiki/master/cake-wiki.png)

[![Build Status](https://travis-ci.org/scherersoftware/cake-wiki.svg?branch=master)](https://travis-ci.org/scherersoftware/cake-wiki)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)

A simple plugin for cakephp that allows creating hierarchical wiki pages.

## Requirements

- [cake-attachments](https://github.com/scherersoftware/cake-attachments)
for managing attachments to wiki pages
- [cake-FrontendBridge](https://github.com/scherersoftware/cake-frontend-bridge)
for easy integration of further needed js libraries
- [cake-model-history](https://github.com/scherersoftware/cake-model-history)
for historizable wiki pages
- [cake-cktools](https://github.com/scherersoftware/cake-cktools)
for view elements such as the linked, hierarchical structure of the wiki
- [bootstrap](http://getbootstrap.com/components/)
for icons in the page edit menu

## Installation

####1. require the plugin via composer

```
$ composer require scherersoftware/cake-wiki
```

#### 2. Include the plugin using composer
Open a terminal in your project-folder and run these commands:

```
$ composer update
$ composer install
```

#### 3. Load the plugin in your `config/bootstrap.php`

```
Plugin::load('Scherersoftware/Wiki', ['bootstrap' => true, 'routes' => true]);
```

#### 4. Create Table 'wiki-pages' in your Database

This plugin requires an additional table in your project database. Run the following SQL query to create the table.

```
CREATE TABLE `wiki_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `sort` int(3) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
```
Or run the migration like:

`bin/cake migrations migrate -p Scherersoftware/Wiki`


See `'vendor/scherersoftware/cake-wiki/config/schma.php'` and `'config/wiki_pages.sql'` for further information.

#### 5. Load additional JS files with FrontendBridge
There are other ways to include all the js files needed but we strongly recommend to use our [FrontendBridge](https://github.com/scherersoftware/cake-frontend-bridge) plugin because if you use it, all you need to do then is add the following line to in your `'assets.ctp'` File:

```
echo $this->FrontendBridge->getAppDataJs();
```

This loads javascript files enabling the [Ace Editor](https://ace.c9.io/#nav=about) which is a crucial part of the UI used to edit the content of the Wiki.

## Usage

Configure your access rights according to your needs and have your users create hierarchical wiki pages!
