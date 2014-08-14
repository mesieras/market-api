Appsco Market API Bundle Configuration
======================================


Minimal configuration

``` yaml
# app/config/config.yml
appsco_market_api:
    private_key:
        file: 'file://%kernel.root_dir%/Resources/your_private_key.pem'
    client_id: your_app_appsco_client_id
    notification:
        appsco: true
```



Complete configuration reference

``` yaml
# app/config/config.yml
appsco_market_api:
    private_key:
        # service id of an implementation of PrivateKeyProviderInterface that will provide
        # private key that will be used for order request signing
        id: private_key_provider_service_id

        # location of the pem private key file on local disk
        file: 'file://%kernel.root_dir%/Resources/your_private_key.pem'

    # appsco client_id of your application
    client_id: your_app_appsco_client_id

    # appsco market order receive API URL
    market_url: https://market.dev.appsco.com/customer/order/receive

    notification:
        # use appsco API to retrieve notification issuer certificate
        appsco: true
        # service ids of custom extra notification validators, must implement NotificationValidatorInterface
        validators:
            - notification_validator_service_1_id
            - notification_validator_service_2_id
```