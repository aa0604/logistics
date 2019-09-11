<?php


namespace xing\core;


/**
 * 物流接口对接基类
 * Class LogisticsBase
 *
 * @property bool $sandbox 沙箱模式
 * @property array $data 数据
 * @property array $config 接口配置
 * @property array $address 地址
 *
 *
 * @package xing\core
 */
class LogisticsApiBase
{
    private $sandbox = false;
    public $data = [];
    private $config = [];

    public $address = [];


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

    /**
     * 设置地址
     * @param $address1
     * @param string $address2
     * @param string $areaName
     * @param string $cityName
     * @param string $provinceName
     * @param string $countryName
     * @return $this
     */
    public function setAddress($address1, $address2 = '', $areaName = '', $cityName = '', $provinceName = '', $countryName = '')
    {
        $this->address = [
            'address1' => $address1,
            'address2' => $address2,
            'areaName' => $areaName,
            'cityName' => $cityName,
            'provinceName' => $provinceName,
            'countryName' => $countryName,
        ];
        return $this;
    }

    public function setCode($cityCode, $countryCode = '')
    {

    }
}