# YouTube API Helper functions


A simple abstraction for the youtube api - providing a fluent api that is powerful and easy to use.

[![Build Status](https://img.shields.io/badge/build-passing-green.svg?style=flat-square)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-0%-red.svg?style=flat-square)](#)
[![Total Downloads](https://img.shields.io/badge/downloads-0-blue.svg?style=flat-square)](#)
[![Latest Stable Version](https://img.shields.io/badge/stable-v1.0.0-green.svg?style=flat-square)](#)
[![Latest Unstable Version](https://img.shields.io/badge/unstable-v2.0--dev-orange.svg?style=flat-square)](#)
[![License](https://img.shields.io/badge/license-Apache_2.0-lightgrey.svg?style=flat-square)](#)

## Contributing

To encourage active collaboration, we strongly encourages pull requests, not just bug reports. "Bug reports" may also be sent in the form of a pull request containing a failing unit test.

However, if you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

### Which Branch?

**All** bug fixes should be sent to the latest stable branch. Bug fixes should **never** be sent to the `master` branch unless they fix features that exist only in the upcoming release.

**Minor** features that are **fully backwards compatible** with the current release may be sent to the latest stable branch.

**Major** new features should always be sent to the `master` branch, which contains the upcoming api client release.

### Coding Style

The API Class follows the [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) and [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) coding standards. In addition to these standards, the following coding standards should be followed:

- The class namespace declaration must be on the same line as `<?php`.
- A class' opening `{` must be on the same line as the class name.
- Functions and control structures must use Allman style braces.
- Indent with tabs, align with spaces.

## Security Vulnerabilities

If you discover a security vulnerability within this API Client, please send an e-mail to Pascal Schwientek at pascal@plsk.de. All security vulnerabilities will be promptly addressed.


## Docs


### Initialize

To initialize the API client call the create method on the factory.

```php
use Pascal\YouTube\Factory;

$api = new Factory::create($content_owner, $service_account_name, $key_file);

```

### Upload a Video

To Upload a video you call the ```uploadVideo($file)``` method on the Api class. The file must be an instace of ```Pascal\Resources\UploadableFile```.

```php
$file = new UploadableFile($pathToFile);

$upload = $api->uploadVideo($file);

```

Now you have to configure the video.

```php
$upload->setTitle("My awesome video")
       ->addTag(['thecakeisalie', 'php', 'is', 'awesome'])
       ->public()
       ->category(22)
       ->...
```

Avalible methods

```php
public function addTag($tag) //string or array

public function setTags($tag) //string or array

public function setTitle($title) //string

public function setDescription($description) //string

public function category($category)  //int

public function publicVideo()

public function privateVideo()

public function unlistedVideo()
```

Before you can upload the video you need to specefie the channels the videos should be uploaded to.

**NOTE: The channel must be part of the Content Owner you initialized the API client with.**

```php
$channel =  new Pascal\Resources\Channel('CHANNEL_ID');

$upload->to($channel);
```

or to many channels

```php
$channels = [
    new Pascal\Resources\Channel('CHANNEL_ID_1');
    new Pascal\Resources\Channel('CHANNEL_ID_2');
    new Pascal\Resources\Channel('CHANNEL_ID_3');
    new Pascal\Resources\Channel('CHANNEL_ID_4');
    new Pascal\Resources\Channel('CHANNEL_ID_5');
];

$upload->to($channels);

```


As soon the Video is configured you can start uploading it. This will return an array with all videoIds from youtube.

```php
$videoIds = $upload->start();

```