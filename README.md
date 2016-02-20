Unfraud Magento
=======

This is the Unfraud plugin for Magento. The plugin supports the Magento Community edition (from 1.7.0.0 to 1.9.2.2).

We commit all our new features directly into our GitHub repository.
But you can also request or suggest new features or code changes yourself!


Requirements
-------------------------
1. The service uses the Unfraud REST api for processing transactions.
2. The server needs to support cURL



Installation Instructions
-------------------------
### Via modman

- Install [modman](https://github.com/colinmollenhour/modman)
- Use the command from your Magento installation folder: `modman clone https://github.com/Unfraud/Unfraud-Magento/`

### Via composer
- Install [composer](http://getcomposer.org/download/)
- Install [Magento Composer](https://github.com/magento-hackathon/magento-composer-installer)
- Create a composer.json into your project like the following sample:

```json
{
    ...
    "require": {
        "unfraud/unfraud-magento":"*"
    },
    "repositories": [
	    {
            "type": "vcs",
            "url": "https://github.com/Unfraud/Unfraud-Magento"
        }
    ],
    "extra":{
        "magento-root-dir": "./"
    }
}
```

- Then from your `composer.json` folder: `php composer.phar install` or `composer install`

### Manually
- You can copy the files from the folders of this repository to the same folders of your installation



Configuration
-------------------------
a) Enter into admin tab "System > Configuration > Unfraud > Settings"

b) Fill the input data with your Unfraud credentials: "Email","Password" and "API_KEY" (you can find it into your Unfraud panel in https://www.unfraud.com/dashboard/). In addition you can setup threshold to block suspicious orders.

c) After that you can enter into your "Unfraud.com" menu dashboard.



Operation of the module
-------------------------
The score of your transaction will be added to the Unfraud cloud service when the
user first creates order. The source code is commented on how to delay the
creation of a transaction score in Unfruad to when the order is completed.

