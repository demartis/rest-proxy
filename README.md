rest-proxy [![Build Status](https://secure.travis-ci.org/gonzalo123/rest-proxy.png?branch=master)](http://travis-ci.org/gonzalo123/rest-proxy)
=========================

Simple Rest Proxy

Example
=========================

```
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

use RestProxy\RestProxy;
use RestProxy\CurlWrapper;

$proxy = new RestProxy(
    Request::createFromGlobals(),
    new CurlWrapper()
    );
$proxy->register('github', 'https://api.github.com');
$proxy->run();


foreach($proxy->getHeaders() as $header) {
    header($header);
}
echo $proxy->getContent();
```

How to install:
=========================
Install composer:
```
curl -s https://getcomposer.org/installer | php
```

Include in your project


```
php composer.phar require 'demartis/rest-proxy:~1.0'
```

OR Create a new project:

```
php composer.phar create-project demartis/rest-proxy proxy
```

OR include the dev release

```
php composer.phar require 'demartis/rest-proxy:dev-master'
```


Run dummy server (only with PHP5.4)

```
cd proxy
php -S localhost:8888 -t www/
```

Open a web browser and type: http://localhost:8888/github/users/gonzalo123

