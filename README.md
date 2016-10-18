## yii2-page-change
Page Change is a simple webpage monitoring app based on Yii 2 framework.

Useful for monitoring jobs, price changes, seasonal promos, news, etc.

### Features
* Block filter (ignore everything except the text between the markers)
* One RSS feed for all pages changes

### Requirements
* PHP >= 5.6
* SQlite 3
* Composer

### Installation
```bash
$ git clone https://github.com/andr-andreev/yii2-page-change.git
$ cd yii2-page-change
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