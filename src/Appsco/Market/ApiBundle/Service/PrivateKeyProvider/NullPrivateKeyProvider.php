<?php

namespace Appsco\Market\ApiBundle\Service\PrivateKeyProvider;

class NullPrivateKeyProvider implements PrivateKeyProviderInterface
{
    /**
     * @throws \LogicException
     * @return string|resource Resource returned by openssl_get_privatekey() or PEM formatted key
     */
    public function get()
    {
        throw new \LogicException('You have to set appsco_market.private_key file or id config parameter');
    }

} 