Appsco Market API Getting Started
=================================

Prerequisites
=============

Register your application
-------------------------

In order to be able to make custom orders, or have configurable Market items, and receive notification from Appsco
Market about purchases and subscription status changes, you should register your application on
[Appsco Accounts](https://accounts.dev.appsco.com/). When you register your application you will get a unique
Client ID and Client Secret.

To make Market orders you will also need a private key and paired X509 certificate registered on your Accounts app.
Once you have registered your application generate a new key, download the provided private key and save it to
your project directory.


Register you Market item
------------------------

On order to appear in the Appsco Market catalogue you should [register](https://market.dev.appsco.com/partner/)
your company and item. During the item registration in the Issuer field enter Appsco Accounts Client ID and in the
Callback URL enter the url of your application where you will receive Market notifications.


Download appsco/market-api with composer
----------------------------------------

Add `appsco/market-api` to your composer.json requirements:

``` json
{
    "require": {
        "appsco/market-api": "dev-master"
    }
}
```

Check `appsco/market-api` [releases](https://github.com/Appsco/market-api/releases) for the latest stable release.

Run `composer update appsco/market-api` command to download the component to your vendor directory.


Appsco Accounts setup
---------------------

The Appsco Market API bundle depends on the [Appsco Accounts API Bundle](https://github.com/Appsco/accounts-api) for
market order notification validation. It is automatically downloaded by composer together with appsco/market-api.
So, first you have to setup the `appsco/accounts-api` bundle as specified in
[documentation](https://github.com/Appsco/accounts-api/blob/master/src/Appsco/Accounts/ApiBundle/Resources/doc/getting-started.md)


Configuration
-------------

Now you can configure `appsco/market-api`, and tell to the bundle what are your Appsco Accounts
Client ID and private key of certificate registered in your app registration info.


``` yaml
# app/config/config.yml
appsco_market_api:
    private_key:
        file: 'file://%kernel.root_dir%/Resources/your_private_key.pem'
    client_id: your_app_appsco_accounts_client_id
    notification:
        appsco: true
```

For configuration details check [configuration reference](configuration.md)


Next steps
----------

At this point you are ready to make Market orders, receive and validate Market notifications, except JTI validation.
Check for details how to make your own [JTI validation](jti.md).

Next steps
 * [Order requests](order.md)
 * [Notifications](notification.md)
 * [JTI validation](jti.md)

