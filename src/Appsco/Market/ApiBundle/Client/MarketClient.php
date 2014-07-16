<?php

namespace Appsco\Market\ApiBundle\Client;

use Appsco\Market\ApiBundle\Model\Notification;
use Appsco\Market\ApiBundle\Model\Order;
use Appsco\Market\ApiBundle\Service\PrivateKeyProvider\PrivateKeyProviderInterface;
use BWC\Component\Jwe\Algorithm;
use BWC\Component\Jwe\Encoder;
use BWC\Component\Jwe\Jwt;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MarketClient
{
    /** @var  Encoder */
    protected $encoder;

    /** @var  PrivateKeyProviderInterface */
    protected $keyProvider;

    /** @var  string */
    protected $algorithm = Algorithm::RS256;

    /** @var  string */
    protected $issuer;

    /** @var  string */
    protected $targetUrl;


    /**
     * @param \BWC\Component\Jwe\Encoder $encoder
     * @param PrivateKeyProviderInterface $keyProvider
     * @param string $issuer
     * @param string $targetUrl
     */
    public function __construct(Encoder $encoder, PrivateKeyProviderInterface $keyProvider, $issuer, $targetUrl)
    {
        $this->encoder = $encoder;
        $this->keyProvider = $keyProvider;
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
     * @param string $targetUrl
     * @return $this|MarketClient
     */
    public function setTargetUrl($targetUrl)
    {
        $this->targetUrl = $targetUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * @param string $issuer
     * @return $this|MarketClient
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
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

        if(null === $jwt->getJwtId()){
            $jwt->setJwtId(sha1(uniqid(mt_rand(), true)));
        }
    }


    /**
     * @param Jwt $jwt
     * @return string
     */
    public function getJwtToken(Jwt $jwt)
    {
        return $this->encoder->encode($jwt, $this->keyProvider->get(), $this->algorithm);
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
     * @param int $appId
     * @return RedirectResponse
     */
    public function getCancelSubscriptionToken($appId)
    {
        $order = new Order();
        $order
            ->setAppId($appId)
            ->setCancel(true)
        ;
        $this->timestampJwt($order);
        $token = $this->getJwtToken($order);

        return $token;
   }

    /**
     * @param int $appId
     * @return RedirectResponse
     */
    public function cancelSubscription($appId)
    {
        $token = $this->getCancelSubscriptionToken($appId);

        return $this->getRedirectResponse($token);
    }

    /**
     * @param string $jwtToken
     * @return \BWC\Component\Jwe\Jose
     */
    public function receiveNotification($jwtToken)
    {
        $notification = $this->encoder->decode($jwtToken, 'Appsco\Market\ApiBundle\Model\Notification');

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
