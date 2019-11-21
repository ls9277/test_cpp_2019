<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/20
 * Time: 12:34
 */

class Mysqli
{
    protected $host;    // 主机名
    protected $port;    // 端口
    protected $dbname;  // 数据库名
    protected $timeout = 30; // 超时时间

    /**
     * Mysqli constructor.
     * @param string $host
     * @param int $port
     * @param int $timeout
     */
    public function __construct($host = "127.0.0.1", $port = 3306, $timeout = 30)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function connect(): bool
    {
        return true;
    }

}