<?php namespace App\Libraries;

class CacheManager {    
    private static $instance = null;

    public function __construct() 
    {
        $this->cache = \Config\Services::cache();
    }

    public static function getInstance()
    {
        return (self::$instance == null) ? self::$instance = new CacheManager() : self::$instance;	
    }

    public function setCache(string $key, array $data)
    {
        $this->cache->save($key, $data, 1200);
    }

    public function getCache($key)
    {
        return $this->cache->get($key);
    }

    public function cleanCache()
    {
        $this->cache->clean();
    }
    
}