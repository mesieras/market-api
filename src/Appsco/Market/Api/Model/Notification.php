<?php

namespace Appsco\Market\Api\Model;

use BWC\Component\Jwe\Jwt;

class Notification extends Jwt
{
    const CLAIM_KIND = 'kind';
    const CLAIM_ORDER_ID = 'order_id';
    const CLAIM_APP_ID = 'app_id';
    const CLAIM_PACKAGE_ID = 'package_id';
    const CLAIM_OWNER_ID = 'owner_id';
    const CLAIM_CHALLENGE = 'challenge';

    const KIND_ORDER_PROCESSED = 'order_processed';

    const KIND_SUBSCRIPTION_CANCELED = 'subscription_canceled';
    const KIND_SUBSCRIPTION_CHARGED_SUCCESSFULLY = 'subscription_charged_successfully';
    const KIND_SUBSCRIPTION_CHARGED_UNSUCCESSFULLY = 'subscription_charged_unsuccessfully';
    const KIND_SUBSCRIPTION_EXPIRED = 'subscription_expired';
    const KIND_SUBSCRIPTION_TRIAL_ENDED = 'subscription_trial_ended';
    const KIND_SUBSCRIPTION_WENT_ACTIVE = 'subscription_went_active';
    const KIND_SUBSCRIPTION_WENT_PAST_DUE = 'subscription_went_past_due';



    /**
     * @param string $kind
     * @param string $orderId
     * @param int $appId
     * @return \Appsco\Market\Api\Model\Notification
     */
    public static function create($kind, $orderId, $appId)
    {
        $result = new Notification();
        $result
            ->setAppId($appId)
            ->setKind($kind)
            ->setOrderId($orderId)
        ;

        return $result;
    }


    /**
     * @param int $appId
     * @return $this|Notification
     */
    public function setAppId($appId)
    {
        return $this->set(self::CLAIM_APP_ID, $appId);
    }

    /**
     * @return int
     */
    public function getAppId()
    {
        return $this->get(self::CLAIM_APP_ID);
    }

    /**
     * @param string $kind
     * @return $this|Notification
     */
    public function setKind($kind)
    {
        return $this->set(self::CLAIM_KIND, $kind);
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->get(self::CLAIM_KIND);
    }

    /**
     * @param string $orderId
     * @return $this|Notification
     */
    public function setOrderId($orderId)
    {
        return $this->set(self::CLAIM_ORDER_ID, $orderId);
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->get(self::CLAIM_ORDER_ID);
    }

    /**
     * @param int $packageId
     * @return $this|Notification
     */
    public function setPackageId($packageId)
    {
        return $this->set(self::CLAIM_PACKAGE_ID, $packageId);
    }

    /**
     * @return int
     */
    public function getPackageId()
    {
        return $this->get(self::CLAIM_PACKAGE_ID);
    }

    /**
     * @param string $ownerId
     * @return $this|Notification
     */
    public function setOwnerId($ownerId)
    {
        return $this->set(self::CLAIM_OWNER_ID, $ownerId);
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->get(self::CLAIM_OWNER_ID);
    }

    /**
     * @param string $challenge
     * @return $this|Notification
     */
    public function setChallenge($challenge)
    {
        return $this->set(self::CLAIM_CHALLENGE, $challenge);
    }

    /**
     * @return string|null
     */
    public function getChallenge()
    {
        return $this->get(self::CLAIM_CHALLENGE);
    }

} 