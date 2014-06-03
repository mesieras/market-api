<?php

namespace Appsco\Market\Api\Model;

class Order 
{
    const DURATION_UNIT_DAY = 'day';
    const DURATION_UNIT_MONTH = 'month';

    private static $validDurationUnits = array(self::DURATION_UNIT_DAY, self::DURATION_UNIT_MONTH);


    /** @var  int */
    protected $packageId;

    /** @var  AbstractApplication */
    protected $application;

    /** @var  int */
    protected $oneTimePrice;

    /** @var  int */
    protected $recurringPrice;

    /** @var  int */
    protected $trialDuration;

    /** @var  string */
    protected $trialDurationUnit;

    /** @var  \DateTime|null */
    protected $firstBillingDate;

    /** @var  int|null */
    protected $billingDayOfMonth;

    /** @var  string */
    protected $description;


    /**
     * @param int $packageId
     * @param AbstractApplication $application
     */
    public function __construct($packageId, AbstractApplication $application)
    {
        $this->packageId = $packageId;
        $this->application = $application;
    }


    /**
     * @param \Appsco\Market\Api\Model\AbstractApplication $application
     * @return $this|Order
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return \Appsco\Market\Api\Model\AbstractApplication
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param int|null $billingDayOfMonth
     * @return $this|Order
     */
    public function setBillingDayOfMonth($billingDayOfMonth)
    {
        $this->billingDayOfMonth = intval($billingDayOfMonth);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBillingDayOfMonth()
    {
        return $this->billingDayOfMonth;
    }

    /**
     * @param string $description
     * @return $this|Order
     */
    public function setDescription($description)
    {
        $this->description = trim($description);
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \DateTime|null $firstBillingDate
     * @return $this|Order
     */
    public function setFirstBillingDate(\DateTime $firstBillingDate = null)
    {
        $this->firstBillingDate = $firstBillingDate;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstBillingDate()
    {
        return $this->firstBillingDate;
    }

    /**
     * @param int $oneTimePrice
     * @return $this|Order
     */
    public function setOneTimePrice($oneTimePrice)
    {
        $this->oneTimePrice = intval($oneTimePrice);
        return $this;
    }

    /**
     * @return int
     */
    public function getOneTimePrice()
    {
        return $this->oneTimePrice;
    }

    /**
     * @param int $packageId
     * @return $this|Order
     */
    public function setPackageId($packageId)
    {
        $this->packageId = intval($packageId);
        return $this;
    }

    /**
     * @return int
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * @param int $recurringPrice
     * @return $this|Order
     */
    public function setRecurringPrice($recurringPrice)
    {
        $this->recurringPrice = intval($recurringPrice);
        return $this;
    }

    /**
     * @return int
     */
    public function getRecurringPrice()
    {
        return $this->recurringPrice;
    }

    /**
     * @param int $trialPeriodDuration
     * @return $this|Order
     */
    public function setTrialPeriodDuration($trialPeriodDuration)
    {
        $this->trialDuration = intval($trialPeriodDuration);
        return $this;
    }

    /**
     * @return int
     */
    public function getTrialPeriodDuration()
    {
        return $this->trialDuration;
    }

    /**
     * @param string $trialPeriodDurationUnit
     * @throws \InvalidArgumentException
     * @return $this|Order
     */
    public function setTrialDurationUnit($trialPeriodDurationUnit)
    {
        if (false == in_array($trialPeriodDurationUnit, self::$validDurationUnits)) {
            throw new \InvalidArgumentException(sprintf("Invalid trial period duration unit '%s'", $trialPeriodDurationUnit));
        }

        $this->trialDurationUnit = $trialPeriodDurationUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrialDurationUnit()
    {
        return $this->trialDurationUnit;
    }



    /**
     * @return array
     */
    public function getJwtPayload()
    {
        $result = array_merge(
            array(
                'package_id' => $this->packageId
            ),
            $this->application->getJwtPayload()
        );

        if ($this->oneTimePrice) {
            $result['ot_price'] = $this->oneTimePrice;
        }
        if ($this->recurringPrice) {
            $result['r_price'] = $this->recurringPrice;
        }
        if ($this->trialDuration) {
            $result['trial_duration'] = $this->trialDuration;
        }
        if ($this->trialDurationUnit) {
            $result['trial_unit'] = $this->trialDurationUnit;
        }
        if ($this->firstBillingDate) {
            $result['first_billing_date'] = $this->firstBillingDate->format('Y-m-d');
        }
        if ($this->billingDayOfMonth) {
            $result['billing_day_of_month'] = $this->billingDayOfMonth;
        }
        if ($this->description) {
            $result['desc'] = $this->description;
        }

        return $result;
    }

}