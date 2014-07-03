<?php

namespace Appsco\Market\ApiBundle\Service\PrivateKeyProvider;

class PrivateKeyFileProvider implements PrivateKeyProviderInterface
{
    /** @var  string */
    protected $filename;

    /** @var  string */
    private $loadedContent;


    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }



    /**
     * @param string $filename
     * @return $this|PrivateKeyFileProvider
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }




    /**
     * @return string|resource Resource returned by openssl_get_privatekey() or PEM formatted key
     */
    public function get()
    {
        if (false == $this->loadedContent) {
            $this->load();
        }

        return $this->loadedContent;
    }


    protected function load()
    {
        if (false == is_file($this->filename)) {
            throw new \RuntimeException(sprintf("Specified private key file '%s' does not exist", $this->filename));
        }

        $this->loadedContent = file_get_contents($this->filename);

        $resource = openssl_pkey_get_private($this->loadedContent);

        if (false == $resource) {
            $this->loadedContent = null;
            throw new \RuntimeException(sprintf("Specified private key '%s' is invalid", $this->filename));
        }

        openssl_pkey_free($resource);
    }

} 