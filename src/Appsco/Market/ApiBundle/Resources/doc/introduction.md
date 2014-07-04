Introduction to Appsco Market
=============================

Appsco Market is an online web system where Appsco Partners / Application Owners can register their applications
that will be shown in the catalogue where end users can purchase those applications.

When you [register as partner](https://market.dev.appsco.com/partner/) first you have to register your company.
Under your company you can register Market Items that will be shown in the application catalogue.

Both company and item have a background registration data as well as a front end presentation data that will be shown
to market visitors.


Market Item
-----------

For the Market item you have to enter default title, URL, and icon of the application. If not overridden during the
ordering process the end users in their list of purchased applications will see your app as specified in the item.

On the Market Item you also should enter the callback URL of your application where you will receive Market
Notifications, and the error URL where users will be redirected back to your app in case your order is invalid.

Issuer field is the Appsco Accounts Client ID of your application. It is used by the Appsco Market to retrieve
your certificate from Appsco Accounts to verify if it's really you that is making the custom order request.

Each market item must have packages that are offered to the end user. Package defines the plan and package details.


Plan
----

A plan defines if it's onetime payment or a subscription and the currency of the transaction. If it's a subscription
plan also defines billing cycle. Plans are read only and can not be edited.


Package
-------

If you choose onetime plan, for the package you can only specify the price.

If you choose a subscription plan, beside the price, you can also specify on the package
 * if it has trial period and it's duration
 * first billing date
 * billing day of month


Beside the pricing details, all packages have title and description that will be presented to the end users
during the purchase and they should describe what the package offers/allows them in your application.


Approval
--------

In order to be shown in the catalogue your company and each item have to be approved by Appsco Market.


Purchase from Market
--------------------

Once your item is approved and listed in the Market application catalogue, the end users can pick some of its
packages and purchase them. You will get an `order_processed` [notification](notification.md) once the purchase
is completed successfully and you should react to it so you allow access and prepare your application environment
so once the user comes to your app he gets the purchased package of service.


Configurable items & custom order request
-----------------------------------------

In case you have a complicated pricing model with too many options that can not be presented as a simple
list of distinct packages with unique price, you can mark your Market Item as configurable and specify your
configurator URL. In that case your registered packages will not be shown to the end user, but rather your
configurator URL where she will navigate and perform the configuration. When end user completes the configuration
of your service and is content with the presented price you can send him back to Appsco Market to complete the
purchase with the [Appsco Market Order API](order.md).




