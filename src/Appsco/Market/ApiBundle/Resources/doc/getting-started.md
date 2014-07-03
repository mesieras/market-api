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

To make Market orders you will also need a X509 certificate registered in Appsco Accounts and paired private key.
Once you have registered your application generate a new key, download the provided private key and save it to
your project directory.


Register you Market item
------------------------

On order to appear in the Appsco Market catalogue you should [register](https://market.dev.appsco.com/partner/)
your company and item. During the item registration in the Issuer field enter Appsco Accounts Client ID and in the
Callback URL enter the url of your application where you will receive Market notifications.


Installation
============

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


Usage
=====

Common setup
------------

``` yaml
# app/config/parameters.yml
my_key: 'file://%kernel.root_dir%/../src/AcmeBundle/Resources/5rr3ks8hyi88cos88kw4s8os4k0cw88kkokow000cskwcsko4o.pem'
my_issuer: '5rr3ks8hyi88cos88kw4s8os4k0cw88kkokow000cskwcsko4o'
market_url: 'https://market.dev.appsco.com/customer/order/receive'
market_certificate: 'file://%kernel.root_dir%/../src/AcmeBundle/Resources/appsco-market.crt'
```


``` yaml
# config.yml
services:
    acme_market_client.jwt_encoder:
        class: BWC\Component\Jwe\Encoder

    acme_market_client.market_client:
        class: Appsco\Market\Api\MarketClient
        arguments:
            - @acme_market_client.jwt_encoder
            - %market_key%
            - %market_issuer%
            - %market_url%
```

Make an order
-------------

``` php
<?php

namespace Acme\MarketClientBundle\Controller;

use Appsco\Market\Api\Model\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $order = new Order();
        $order->setPackageId(123); // the item package you are making order for
        return $this->getMarketClient()->makeOrder($order);
    }


    /**
     * @return \Appsco\Market\Api\MarketClient
     */
    private function getMarketClient()
    {
        return $this->get('acme_market_client.market_client');
    }
}
```

The Market Client will make an order JWT sign it with your private key and return a `RedirectResponse` with
Market order receive URL.


Receive a Market notification
-----------------------------

``` php
<?php

namespace Acme\MarketClientBundle\Controller;

use Appsco\Market\Api\Model\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function notificationAction(Request $request)
    {
        $jwtToken = $request->request->get('jwt');
        $notificationJwt = $this->getJwtEncoder()->decode($jwtToken, 'Appsco\Market\Api\Model\Notification');

        // do some action based on the notification data

        return new Response('');
    }


    /**
     * @return \Appsco\Market\Api\MarketClient
     */
    private function getMarketClient()
    {
        return $this->get('acme_market_client.market_client');
    }

    /**
     * @return \BWC\Component\Jwe\Encoder
     */
    private function getJwtEncoder()
    {
        return $this->get('acme_market_client.jwt_encoder');
    }

}
```
