<?php

namespace Appsco\Market\Api\Model;

class Order 
{
    /** @var  int */
    protected $packageId;

    /** @var  AbstractApplication */
    protected $application;

    /** @var  int */
    protected $oneTimePrice;

    /** @var  int */
    protected $recurringPrice;

    /** @var  int */
    protected $trialMonths;

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
    public function setApplication($application)
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
     * @param string $description
     * @return $this|Order
     */
    public function setDescription($description)
    {
        $this->description = strip_tags(trim($description));
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
     * @param int $trialMonths
     * @return $this|Order
     */
    public function setTrialMonths($trialMonths)
    {
        $this->trialMonths = intval($trialMonths);
        return $this;
    }

    /**
     * @return int
     */
    public function getTrialMonths()
    {
        return $this->trialMonths;
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
        if ($this->trialMonths) {
            $result['trial'] = $this->trialMonths;
        }
        if ($this->description) {
            $result['desc'] = $this->description;
        }

        return $result;
    }

}