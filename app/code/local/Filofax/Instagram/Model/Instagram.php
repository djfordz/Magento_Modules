<?php

require_once __DIR__ . '/vendor/autoload.php';

class Filofax_Instagram_Model_Instagram extends Mage_Core_Model_Abstract
{
    const CACHE_LIFETIME = '86400';
    const CACHE_LABEL = 'instagram';

    protected $data = array();
    protected $cache;
    protected $cache_path = 'var/cache/';
    protected $cache_time;
    protected $cache_extension = '.cache';

    public function __construct()
    {
        $this->cache_time = $this->getCacheLifetime();
    }

    public function getFeed()
    {
        if (Mage::helper('filogram')->isEnabled()) {
            return $this->get_data(self::CACHE_LABEL);
        }

        return false;
    }

    protected function getCacheLifetime()
    {
        return !Mage::getStoreConfig('filogram/general/cache_lifetime') ? Mage::getStoreConfig('filogram/general/cache_lifetime') : self::CACHE_LIFETIME;
    }

    protected function instagramApi()
    {
        $data = array();
        $this->api = new \Instagram\Api();
        $this->api->setAccessToken(Mage::getStoreConfig('filogram/general/access_token'));
        $this->api->setUserId(Mage::getStoreConfig('filogram/general/user_id'));

        try {
            $data = $this->api->getFeed(Mage::getStoreConfig('filogram/general/username'));    
        } catch (Exception $e) {
            Mage::logException($e);
        }
        
        if ($data) {
            $mediaArr = get_object_vars($data);
            return $mediaArr;
        }

        return false;
    }

    public function get_data($label)
    {
        if($this->data = $this->get_cache($label)){
            return $this->data;
        } else {
            $this->data = $this->instagramApi();
            $this->set_cache($label, $this->data);
            return $this->data;
        }
    }

    protected function set_cache($label, $data)
    {
        file_put_contents($this->cache_path . $this->safe_filename($label) . $this->cache_extension, serialize($data));
    }

    protected function get_cache($label)
    {
        if($this->is_cached($label)){
            $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;
            return unserialize(file_get_contents($filename));
        }

        return false;
    }

    protected function is_cached($label)
    {
        $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;

        if(file_exists($filename) && (filemtime($filename) + $this->cache_time >= time())) return true;

        return false;
    }

    private function safe_filename($filename)
    {
        return preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
    }
}
