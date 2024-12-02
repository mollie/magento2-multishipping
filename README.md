<p align="center">
  <img src="https://github-production-user-asset-6210df.s3.amazonaws.com/24823946/391594892-676abc44-158b-4f78-b523-974034d65c2b.jpg?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVCODYLSA53PQK4ZA%2F20241202%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20241202T150215Z&X-Amz-Expires=300&X-Amz-Signature=f7ca4b8854628f44ffc9d44f13c3b90a09c6a5be01090d115a55e4c0b4ce0421&X-Amz-SignedHeaders=host" />
</p>
<h1 align="left">Mollie Multishipping Addon for Magento 2.3.x and higher</h1>


This plugin is an **addon** on the [Mollie Magento 2 payment module](https://github.com/mollie/magento2/) and can't be installed seperatly without the Mollie Payment plugin installed.

## Installation
We recommend that you make a backup of your webshop files, as well as the database.

Step-by-step to install the Magento® 2 extension through Composer:

1.	Make sure the [Mollie Magento 2 payment module](https://github.com/mollie/magento2/) is installed.
2.	Connect to your server running Magento® 2 using SSH or other method (make sure you have access to the command line).
3.	Locate your Magento® 2 project root.
4.	Install the Magento® 2 extension through composer and wait till it's completed:
```
composer require mollie/magento2-multishipping
``` 
4.	Once completed run the Magento® module enable command:
```
bin/magento module:enable Mollie_Multishipping
``` 
5.	After that run the Magento® upgrade and clean the caches:
```
php bin/magento setup:upgrade
php bin/magento cache:flush
```
6.  If Magento® is running in production mode you also need to redeploy the static content:
```
php bin/magento setup:static-content:deploy
```
7.  Go to your Magento® admin portal and open; ‘Stores’ > ‘Configuration’ > ‘Mollie’ > ‘Advanced’ to activate Multishipping.
   

## License ##
[BSD (Berkeley Software Distribution) License](http://www.opensource.org/licenses/bsd-license.php).
Copyright (c) 2011-2021, Mollie B.V.
