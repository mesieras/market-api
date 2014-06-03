<?php

namespace Appsco\Market\Api;

use Appsco\Market\Api\Model\Order;
use BWC\Component\Jwe\Algorithm;
use BWC\Component\Jwe\Encoder;
use BWC\Component\Jwe\Jwt;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MarketClient
{
    /** @var  Encoder */
    protected $encoder;

    /** @var  string */
    protected $key;

    /** @var  string */
    protected $algorithm = Algorithm::RS256;

    /** @var  string */
    protected $issuer;

    /** @var  string */
    protected $targetUrl;


    /**
     * @param \BWC\Component\Jwe\Encoder $encoder
     * @param string $key
     * @param string $issuer
     * @param string $targetUrl
     */
    public function __construct(Encoder $encoder, $key, $issuer, $targetUrl)
    {
        $this->encoder = $encoder;
        $this->key = $key;
        $this->issuer = $issuer;
        $this->targetUrl = $targetUrl;
    }



    /**
     * @param string $algorithm
     * @return $this|MarketClient
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }


    /**
     * @param Order $order
     * @return RedirectResponse
     */
    public function makeOrder(Order $order)
    {
        $jwt = $this->getOrderJwt($order);
        $token = $this->getJwtToken($jwt);
        return $this->getRedirectResponse($token);
    }

    /**
     * @param Order $order
     * @return Jwt
     */
    public function getOrderJwt(Order $order)
    {
        $jwt = new Jwt(array(), $order->getJwtPayload());
        $jwt->setIssuer($this->issuer);
        $jwt->setIssuedAt(time());
        $jwt->setJwtId(sha1(uniqid(mt_rand(), true)));

        return $jwt;
    }


    /**
     * @param Jwt $jwt
     * @return string
     */
    public function getJwtToken(Jwt $jwt)
    {
        return $this->encoder->encode($jwt, $this->key, $this->algorithm);
    }

    /**
     * @param string $token
     * @return RedirectResponse
     */
    public function getRedirectResponse($token)
    {
        return new RedirectResponse(sprintf("%s?jwt=%s", $this->targetUrl, $token));
    }
}