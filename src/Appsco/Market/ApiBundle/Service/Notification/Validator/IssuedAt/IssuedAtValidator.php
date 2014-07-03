<?php

namespace Appsco\Market\ApiBundle\Service\Notification\Validator\IssuedAt;

use Appsco\Market\ApiBundle\Error\InvalidNotificationException;
use Appsco\Market\ApiBundle\Model\Notification;
use Appsco\Market\ApiBundle\Service\Notification\Validator\NotificationValidatorInterface;

class IssuedAtValidator implements NotificationValidatorInterface
{
    /** @var int  */
    protected $maxDifference = 120;

    /**
     * @param int $maxDifference
     */
    public function __construct($maxDifference = 120)
    {
        $this->maxDifference = $maxDifference;
    }


    /**
     * @param Notification $notification
     * @throws InvalidNotificationException
     * @return void
     */
    public function validate(Notification $notification)
    {
        if (abs($notification->getIssuedAt() - time()) > $this->maxDifference) {
            throw new InvalidNotificationException("Notification expired");
        }
    }

} 