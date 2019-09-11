<?php


namespace xing\core;


/**
 * 物流接口对接基类
 * Class LogisticsBase
 *
 * @property bool $sandbox 沙箱模式
 * @property array $data 数据
 * @property array $config 接口配置
 *
 * @package xing\core
 */
class LogisticsApiBase
{
    private $sandbox = false;
    public $data = [];
    private $config = [];


    /**
     * 设置沙箱模式
     * @param $bool
     * @return $this
     */
    public function sandbox( $bool)
    {
        $this->sandbox = $bool;
        return $this;
    }

    /**
     * 设置各种接口所需的数据
     * @param array $data
     * @return $this
     */
    public function data(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 合并配置设置
     * @param $config
     * @return $this
     */
    public function config($config)
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }
}