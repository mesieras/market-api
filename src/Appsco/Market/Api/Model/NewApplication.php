<?php

namespace Appsco\Market\Api\Model;

class NewApplication extends AbstractApplication
{
    /** @var  string */
    protected $title;

    /** @var  string */
    protected $url;

    /** @var  string */
    protected $icon;


    /**
     * @param string $url
     * @param string $title
     * @param string $icon
     */
    public function __construct($url = null, $title = null, $icon = null)
    {
        $this->url = $url;
        $this->icon = $icon;
        $this->title = $title;
    }


    /**
     * @param string $icon
     * @return $this|NewApplication
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $title
     * @return $this|NewApplication
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $url
     * @return $this|NewApplication
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }



    /**
     * @return array
     */
    public function getJwtPayload()
    {
        $result = array();
        if ($this->title) {
            $result['app_title'] = $this->title;
        }
        if ($this->url) {
            $result['app_url'] = $this->url;
        }
        if ($this->icon) {
            $result['app_icon'] = $this->icon;
        }

        return $result;
    }

}