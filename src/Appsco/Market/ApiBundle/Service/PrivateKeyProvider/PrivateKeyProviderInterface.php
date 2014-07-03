<?php

namespace Appsco\Market\ApiBundle\Service\PrivateKeyProvider;

interface PrivateKeyProviderInterface
{
    /**
     * @return string|resource Resource returned by openssl_get_privatekey() or PEM formatted key
     */
    public function get();
} 