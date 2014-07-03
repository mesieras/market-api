Appsco Market Order Requests
============================

With Appsco Market API client you can make custom order requests with possibility to define/override some of the
item attributes like price, trial details, first billing date, for new item purchase as well for existing
already purchased app/item instance.

Appsco Market Order API is [JWT](http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html) based with
custom claims set:
 * `package_id` - Appsco Market Package ID of the item
 * `app_id`` - Appsco Market id of existing application - an already purchased item instance that you want to reconfigure
 * `price` - Override of the item's package price
 * `description` - Override package description
 * `app_title` - Override the item title for this purchase instance
 * `app_url` - Override the item application url for this purchase instance
 * `app_icon` - Override the item application icon for this purchase instance
 * `has_trial` - Override the package has trial option. Valid only if package is on recurring/subscription plan.
 * `trial_duration` - Override the package trial duration option. Valid only if package is on recurring/subscription plan.
 * `trial_duration_unit` - Override the package trial duration unit option. Values: 'day' or 'month'. Valid only if package is on recurring/subscription plan.
 * `first_billing_date` - Override the package first billing date option. Valid only if package is on recurring/subscription plan.
 * `billing_day_of_month` - Override the package billing day of month option. Valid only if package is on recurring/subscription plan.
 * `test` - Make a simulated test payment. Requires valid test credit card numbers
 * `cancel` - Cancel existing app/item instance subscription. Valid only together with `app_id` if that app has active subscription


Beside the custom JWT claims set that defines the Appsco Market Order, and depending on the case might vary, all order
JWTs must have following standard claims:
 * `jti` - JWT ID - unique order id of the package/application - random unguessable string generated automatically by the market client
 * `iat` - Issued at - order is valid only if time between it's issued and received is less then 2 minutes
 * `iss` - Issuer - Client ID of your Appsco Accounts application that defines which certificate will be used to validate the JWT signature registered on the Market Item of package referenced in the order


Order for new app
-----------------

To make an order for a new item all you have to specify is the Package ID you wish to purchase.

``` json
{
    "iss": "your_appsco_accounts_client_id",
    "iat": 1404392353,
    "jti": "random_order_id",
    "package_id": MARKET_PACKAGE_ID
}
```

Order like this inherits all attributes as they are defined in the specified package.


``` php
class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    function indexAction()
    {
        $order = new Order();
        $order->setPackageId(MARKET_PACKAGE_ID);
        return $this->get('appsco_market_api.client')->makeOrder($order);
    }
}
```


Order for new app with custom price
-----------------------------------

To make an order with custom price, beside Package ID you also should specify the price.

``` json
{
    "iss": "your_appsco_accounts_client_id",
    "iat": 1404392353,
    "jti": "random_order_id",
    "package_id": MARKET_PACKAGE_ID,
    "price": CUSTOM_PRICE_IN_CENTS
}
```

**Note** Price must be integer value in cents (1/100 of the currency unit). For example a decimal 12.34
should be represented as integer 1234.

This order will inherit all other attributes from the specified package, except for the price attribute that will
be taken from the order request.

``` php
class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    function indexAction()
    {
        $order = new Order();
        $order->setPackageId(MARKET_PACKAGE_ID);
        $order->setPrice(1234);
        return $this->get('appsco_market_api.client')->makeOrder($order);
    }
}
```


Order with custom trial duration
--------------------------------

To make an order with trial duration different then the one specified in the package you should specify those trial
claims in the order.

``` json
{
    "iss": "your_appsco_accounts_client_id",
    "iat": 1404392353,
    "jti": "random_order_id",
    "package_id": MARKET_PACKAGE_ID,
    "has_trial": true,
    "trial_duration": 3,
    "trial_duration_unit": "month"
}
```

If Market Package is already set to have a trial period in months, you even don't have to specify `has_trial` and
`trial_duration_unit` attributes to same values as in package. Only attributes you have to specify in the Order JWT
are those that are different then on the package - that you wish to override for this purchase.

``` php
class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    function indexAction()
    {
        $order = new Order();
        $order->setPackageId(MARKET_PACKAGE_ID);
        $order->setHasTrial(true);
        $order->setTrialDuration(3);
        $order->setTrialDurationUnit(Order::DURATION_UNIT_MONTH);
        return $this->get('appsco_market_api.client')->makeOrder($order);
    }
}
```


Cancel subscription for existing app
------------------------------------

To cancel an existing subscription you have to specify the Market Application ID and the cancel claim

``` json
{
    "iss": "your_appsco_accounts_client_id",
    "iat": 1404392353,
    "jti": "random_order_id",
    "app_id": MARKET_APP_ID,
    "cancel": true
}
```

``` php
class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    function indexAction()
    {
        $order = new Order();
        $order->setAppId(MARKET_APP_ID);
        $order->setCancel(true);
        return $this->get('appsco_market_api.client')->makeOrder($order);
    }
}
```


Change the price of the existing app subscription
-------------------------------------------------

To change the price of the existing app subscription, you should specify the Market Application ID and price attribute

``` json
{
    "iss": "your_appsco_accounts_client_id",
    "iat": 1404392353,
    "jti": "random_order_id",
    "app_id": MARKET_APP_ID,
    "price": NEW_SUBSCRIPTION_PRICE_IN_CENTS
}
```

``` php
class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    function indexAction()
    {
        $order = new Order();
        $order->setAppId(MARKET_APP_ID);
        $order->setPrice(4567);
        return $this->get('appsco_market_api.client')->makeOrder($order);
    }
}
```

