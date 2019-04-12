# Telavox Flow Unofficial PHP Library
This SDK supports the Telavox Flow API ( https://www.telavox.com/en/developer/documentation )

I'll continue to update it for further new API releases.

## Getting started
Start out by running composer
```
composer require rainman/telavox-php:dev-master
```

Afterwards, fetch your Flow API token under: <i> Settings - My Accounts - Username and password </i>

Now you can start using the SDK.

Example of getting started:

```php5
<?php
require ('src/Telavox.php');

$telavox = new Telavox(<YOUR API TOKEN HERE>);


var_dump($telavox->getCalls());
?>
```

# Happy coding
