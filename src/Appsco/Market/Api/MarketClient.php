<?php

namespace Appsco\Market\Api;

use Appsco\Market\Api\Model\Notification;
use Appsco\Market\Api\Model\Order;
use BWC\Component\Jwe\Algorithm;
use BWC\Component\Jwe\Encoder;
use BWC\Component\Jwe\Jwt;
use JMS\Serializer\Serializer;
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
        $this->timestampJwt($order);
        $token = $this->getJwtToken($order);
        return $this->getRedirectResponse($token);
    }

    /**
     * @param Jwt $jwt
     */
    public function timestampJwt(Jwt $jwt)
    {
        $jwt->setIssuer($this->issuer);
        $jwt->setIssuedAt(time());
        $jwt->setJwtId(sha1(uniqid(mt_rand(), true)));
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


    /**
     * @param string $jwtToken
     * @return \BWC\Component\Jwe\Jose
     */
    public function receiveNotification($jwtToken)
    {
        $notification = $this->encoder->decode($jwtToken, 'Appsco\Market\Api\Model\Notification');

        return $notification;
    }

    /**
     * @param Notification $notification
     * @return string
     */
    public function notificationChallengeReply(Notification $notification)
    {
        $result = new Notification();
        $result->setChallenge($notification->getChallenge());
        $this->timestampJwt($result);
        $token = $this->getJwtToken($result);
        return $token;
    }

}