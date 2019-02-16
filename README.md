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
Set the document root of your web server to the `public` directory. Use your browser to access the application.

You can check for web page changes by running
```bash
$ php yii page/check
```