Please note that this is the development area for HelioPanel and usually does not resemble the most stable release. For information on installing please visit the official website mentioned above. HelioPanel is a control panel offered to the user's of HelioHost's services, which allows them to easily take full control of their website without facing any problems. The aim of HelioPanel is to resolve any common issues that user's face every day, and to make website management much easier.

## Installing ##

Since HelioPanel is not a distributed web application, manual configuration will be required
to set up the latest version.

1. Check out the latest copy of HelioPanel with `git clone git://github.com/HelioNetworks/HelioPanel.git`. 
2. Create a file named `config.php`. 
3. Transfer the file named `hook.php` to the website you wish to install HelioPanel on.
3. Populate config.php with this information:

```php5
<?php

$users = array(
    'user_id' => array(
        'password' => 'user_password',
        'hook_php' => 'http://path.to/hook.php',
        'hook_auth' => 'random_string',
    ),
);
```