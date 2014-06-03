<?php

namespace Appsco\Market\Api\Model;

abstract class AbstractApplication
{
    /**
     * @return array
     */
    public abstract function getJwtPayload();

} 