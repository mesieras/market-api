<?php

namespace Appsco\Market\ApiBundle\Service\Notification\Validator\Certificate;

use Appsco\Dashboard\ApiBundle\Client\AppscoClient;
use Appsco\Market\ApiBundle\Error\InvalidNotificationException;
use Appsco\Market\ApiBundle\Model\Notification;
use Appsco\Market\ApiBundle\Service\Notification\Validator\NotificationValidatorInterface;
use BWC\Component\Jwe\Encoder;

class AppscoCertificateValidator implements NotificationValidatorInterface
{
    /** @var  AppscoClient */
    protected $client;

    /** @var  Encoder */
    protected $jwtEncoder;


    /**
     * @param AppscoClient $accountsClient
     * @param \BWC\Component\Jwe\Encoder $jwtEncoder
     */
    public function __construct(AppscoClient $accountsClient, Encoder $jwtEncoder)
    {
        $this->client = $accountsClient;
        $this->jwtEncoder = $jwtEncoder;
    }


    /**
     * @param Notification $notification
     * @throws InvalidNotificationException
     * @return void
     */
    public function validate(Notification $notification)
    {
        $certificateList = $this->client->certificateGet($notification->getIssuer());

        if (0 == count($certificateList->getCertificates())) {
            throw new InvalidNotificationException(sprintf(
                "Issuer '%s' has no Appsco Accounts certificates",
                $notification->getIssuer()
            ));
        }

        $error = null;
        foreach ($certificateList->getCertificates() as $certificate) {
            try {
                $this->jwtEncoder->verify($notification, $certificate->getCertificate());
                $error = null;
                break;
            } catch (\Exception $ex) {
                $error = $ex;
            }
        }

        if ($error) {
            throw new InvalidNotificationException(
                sprintf("Unable to verify certificate of issuer '%s'", $notification->getIssuer()),
                0,
                $error
            );
        }
    }

} 