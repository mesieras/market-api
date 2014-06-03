<?php

namespace Appsco\Market\Api\Model;

class ExistingApplication extends AbstractApplication
{
    /** @var  int */
    protected $id;


    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }



    /**
     * @param int $id
     * @return $this|ExistingApplication
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * @return array
     */
    public function getJwtPayload()
    {
        $result = array(
            'app_id' => $this->id
        );

        return $result;
    }

}