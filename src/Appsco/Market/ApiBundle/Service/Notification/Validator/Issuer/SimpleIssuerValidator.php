<?php

namespace Appsco\Market\ApiBundle\Service\Notification\Validator\Issuer;

use Appsco\Market\ApiBundle\Error\InvalidNotificationException;
use Appsco\Market\ApiBundle\Model\Notification;
use Appsco\Market\ApiBundle\Service\Notification\Validator\NotificationValidatorInterface;

class SimpleIssuerValidator implements NotificationValidatorInterface
{
    /** @var string[]  */
    protected $validIssuers = array();


    /**
     * @return \string[]
     */
    public function getValidIssuers()
    {
        return $this->validIssuers;
    }


    /**
     * @param string $issuer
     * @return $this|SimpleIssuerValidator
     */
    public function addValidIssuer($issuer)
    {
        $this->validIssuers[$issuer] = true;
    }


    /**
     * @param Notification $notification
     * @throws InvalidNotificationException
     * @return void
     */
    public function validate(Notification $notification)
    {
        if (false == array_key_exists($notification->getIssuer(), $this->validIssuers)) {
            throw new InvalidNotificationException(sprintf("Invalid issuer '%s'", $notification->getIssuer()));
        }
    }

} 