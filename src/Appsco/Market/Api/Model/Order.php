<?php

namespace Appsco\Market\Api\Model;

use BWC\Component\Jwe\Jwt;

class Order extends Jwt
{
    const DURATION_UNIT_DAY = 'day';
    const DURATION_UNIT_MONTH = 'month';

    const CLAIM_PACKAGE_ID = 'package_id';
    const CLAIM_APP_ID = 'app_id';
    const CLAIM_APP_TITLE = 'app_title';
    const CLAIM_APP_URL = 'app_url';
    const CLAIM_APP_ICON = 'app_icon';
    const CLAIM_ONETIME_PRICE = 'onetime_price';
    const CLAIM_RECURRING_PRICE = 'recurring_price';
    const CLAIM_HAS_TRIAL = 'has_trial';
    const CLAIM_TRIAL_DURATION = 'trial_duration';
    const CLAIM_TRIAL_DURATION_UNIT = 'trial_duration_unit';
    const CLAIM_FIRST_BILLING_DATE = 'first_billing_date';
    const CLAIM_BILLING_DAY_OF_MONTH = 'billing_day_of_month';
    const CLAIM_DESCRIPTION = 'description';


    /**
     * @var array
     */
    private static $validDurationUnits = array(self::DURATION_UNIT_DAY, self::DURATION_UNIT_MONTH);


    /**
     * @param int $packageId
     * @return Order
     */
    public static function create($packageId)
    {
        return new Order(array(), array(self::CLAIM_PACKAGE_ID => intval($packageId)));
    }


    /**
     * @param string $value
     * @return $this|Order
     */
    public function setAppIcon($value)
    {
        return $this->set(self::CLAIM_APP_ICON, $value);
    }

    /**
     * @return string|null
     */
    public function getAppIcon()
    {
        return $this->get(self::CLAIM_APP_ICON);
    }

    /**
     * @param int $value
     * @return $this|Order
     */
    public function setAppId($value)
    {
        return $this->set(self::CLAIM_APP_ID, $value);
    }

    /**
     * @return int|null
     */
    public function getAppId()
    {
        return $this->get(self::CLAIM_APP_ID);
    }

    /**
     * @param string $value
     * @return $this|Order
     */
    public function setAppTitle($value)
    {
        return $this->set(self::CLAIM_APP_TITLE, $value);
    }

    /**
     * @return string|null
     */
    public function getAppTitle()
    {
        return $this->get(self::CLAIM_APP_TITLE);
    }

    /**
     * @param string $value
     * @return $this|Order
     */
    public function setAppUrl($value)
    {
        return $this->set(self::CLAIM_APP_URL, $value);
    }

    /**
     * @return string|null
     */
    public function getAppUrl()
    {
        return $this->get(self::CLAIM_APP_URL);
    }


    /**
     * @param int $value
     * @return $this|Order
     */
    public function setBillingDayOfMonth($value)
    {
        return $this->set(self::CLAIM_BILLING_DAY_OF_MONTH, intval($value));
    }

    /**
     * @return int|null
     */
    public function getBillingDayOfMonth()
    {
        return $this->get(self::CLAIM_BILLING_DAY_OF_MONTH);
    }

    /**
     * @param string $value
     * @return $this|Order
     */
    public function setDescription($value)
    {
        return $this->set(self::CLAIM_DESCRIPTION, trim($value));
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->get(self::CLAIM_DESCRIPTION);
    }

    /**
     * @param \DateTime|null $value
     * @return $this|Order
     */
    public function setFirstBillingDate(\DateTime $value = null)
    {
        return $this->set(self::CLAIM_FIRST_BILLING_DATE, $value ? $value->format('Y-m-d') : null);
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstBillingDate()
    {
        $result = $this->get(self::CLAIM_FIRST_BILLING_DATE);
        if ($result) {
            $result = new \DateTime($result);
        }

        return $result;
    }

    /**
     * @param int $value
     * @return $this|Order
     */
    public function setOneTimePrice($value)
    {
        return $this->set(self::CLAIM_ONETIME_PRICE, intval($value));
    }

    /**
     * @return int|null
     */
    public function getOneTimePrice()
    {
        return $this->get(self::CLAIM_ONETIME_PRICE);
    }

    /**
     * @param int $packageId
     * @return $this|Order
     */
    public function setPackageId($packageId)
    {
        return $this->set(self::CLAIM_PACKAGE_ID, intval($packageId));
    }

    /**
     * @return int
     */
    public function getPackageId()
    {
        return $this->get(self::CLAIM_PACKAGE_ID);
    }

    /**
     * @param int $value
     * @return $this|Order
     */
    public function setRecurringPrice($value)
    {
        return $this->set(self::CLAIM_RECURRING_PRICE, intval($value));
    }

    /**
     * @return int|null
     */
    public function getRecurringPrice()
    {
        return $this->get(self::CLAIM_RECURRING_PRICE);
    }

    /**
     * @param int $value
     * @return $this|Order
     */
    public function setTrialDuration($value)
    {
        return $this->set(self::CLAIM_TRIAL_DURATION, intval($value));
    }

    /**
     * @return int|null
     */
    public function getTrialDuration()
    {
        return $this->get(self::CLAIM_TRIAL_DURATION);
    }

    /**
     * @param bool $value
     * @return $this|Order
     */
    public function setHasTrial($value)
    {
        return $this->set(self::CLAIM_HAS_TRIAL, (bool)$value);
    }

    /**
     * @return bool
     */
    public function getHasTrial()
    {
        return (bool)$this->get(self::CLAIM_HAS_TRIAL);
    }

    /**
     * @param string $value
     * @throws \InvalidArgumentException
     * @return $this|Order
     */
    public function setTrialDurationUnit($value)
    {
        if (false == in_array($value, self::$validDurationUnits)) {
            throw new \InvalidArgumentException(sprintf("Invalid trial period duration unit '%s'", $value));
        }

        $this->set(self::CLAIM_TRIAL_DURATION_UNIT, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getTrialDurationUnit()
    {
        return $this->get(self::CLAIM_TRIAL_DURATION_UNIT);
    }


}