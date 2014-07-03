<?php

namespace Appsco\Market\ApiBundle\Service\Notification\Validator;

use Appsco\Market\ApiBundle\Error\InvalidNotificationException;
use Appsco\Market\ApiBundle\Model\Notification;

class CompositeValidator implements NotificationValidatorInterface
{
    /** @var NotificationValidatorInterface[] */
    protected $validators = array();




    /**
     * @return \Appsco\Market\ApiBundle\Service\Notification\Validator\NotificationValidatorInterface[]
     */
    public function getValidators()
    {
        return $this->validators;
    }


    public function addValidator(NotificationValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }



    /**
     * @param Notification $notification
     * @throws InvalidNotificationException
     * @return void
     */
    public function validate(Notification $notification)
    {
        foreach ($this->validators as $validator) {
            $validator->validate($notification);
        }
    }


}