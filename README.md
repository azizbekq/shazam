# shazam

# 🎤 Musiqaning 10 sekundgacha bo'lgan qismidan butun musiqani topuvchi api

```shell
composer require dublix/shazam
```

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Dublix\Shazam;

$api = new Shazam;
$data = $api->init('test.ogg');
print_r($data);

```
