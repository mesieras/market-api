<?php

namespace Appsco\Market\ApiBundle\Service\Notification\Validator;

use Appsco\Market\ApiBundle\Error\InvalidNotificationException;
use Appsco\Market\ApiBundle\Model\Notification;

interface NotificationValidatorInterface
{
    /**
     * @param Notification $notification
     * @throws InvalidNotificationException
     * @return void
     */
    public function validate(Notification $notification);

} 