<?php
namespace Riesenia\RedisCluster\Cache\Engine;

use Cake\Cache\Engine\RedisEngine;

/**
 * RedisCluster storage engine for cache.
 */
class RedisClusterEngine extends RedisEngine
{
    /**
     * Redis wrapper.
     *
     * @var \RedisCluster
     */
    protected $_Redis;

    /**
     * The default config used unless overridden by runtime configuration.
     *
     * - `duration` Specify how long items in this cache configuration last.
     * - `groups` List of groups or 'tags' associated to every key stored in this config.
     *    handy for deleting a complete group from cache.
     * - `persistent` Connect to the Redis server with a persistent connection
     * - `prefix` Prefix appended to all entries. Good for when you need to share a keyspace
     *    with either another cache config or another application.
     * - `probability` Probability of hitting a cache gc cleanup. Setting to 0 will disable
     *    cache::gc from ever being called automatically.
     * - `server` array of Redis server hosts.
     * - `timeout` timeout in seconds (float).
     * - `read_timeout` read timeout in seconds (float).
     *
     * @var array
     */
    protected $_defaultConfig = [
        'name' => 'cache',
        'duration' => 3600,
        'groups' => [],
        'persistent' => true,
        'prefix' => 'cake_',
        'probability' => 100,
        'server' => [],
        'timeout' => 2,
        'read_timeout' => 2
    ];

    /**
     * {@inheritdoc}
     */
    public function init(array $config = [])
    {
        if (!extension_loaded('redis')) {
            return false;
        }

        parent::init($config);

        return $this->_connect();
    }

    /**
     * {@inheritdoc}
     */
    protected function _connect()
    {
        try {
            $this->_Redis = new \RedisCluster($this->_config['name'], $this->_config['server'], $this->_config['timeout'], $this->_config['read_timeout'], $this->_config['persistent']);
        } catch (\RedisClusterException $e) {
            return false;
        }

        return true;
    }
}