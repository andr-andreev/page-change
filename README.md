## Page Change
[![Yii](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)

Page Change is a simple webpage monitoring app.

Useful for monitoring jobs, price changes, seasonal promos, news, etc.

![Dashboard](docs/screenshots/dashboard.png?raw=true "Dashboard")
---
![Page history](docs/screenshots/page-history.png?raw=true "Page history")

### Features
* Block filter (ignore everything except the text between the markers)
* One RSS feed for all pages changes

### Requirements
* PHP >= 7.1
* SQLite 3
* Composer

### Installation
```bash
$ git clone https://github.com/andr-andreev/page-change.git
$ cd page-change
$ make install
```

### Usage ###
Set the document root of your webserver to the `web` folder.

To add webpage to monitoring open in your browser:
```
http://example.com/
```
To check for all webpages changes run (or add to cron):
```bash
$ php yii page/check
```
To view changes for all webpages open in your browser:
```
http://example.com/rss
```