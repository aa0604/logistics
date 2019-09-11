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
 * @property string $cityCode 城市邮编
 * @property string $countryCode 国家编码
 * @property array $goods 国家编码
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
    public $cityCode;
    public $countryCode;

    public $goods;


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


    /**
     * 设置城市编码，国家编码
     * @param string $cityCode
     * @param string|null $countryCode
     * @return $this
     */
    public function setCode(string $cityCode, ? string $countryCode = '')
    {
        $this->cityCode = $cityCode;
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * 增加一个商品/货物
     * @param int $number
     * @param int $weight
     * @param int $totalMoney
     * @param string $goodsName
     * @param string $currency
     * @param float $price
     * @param string $goodsName2 商品名称 2
     * @return $this
     */
    public function addGoods(
        $number = 1, $weight = 0, $totalMoney = 0,  $goodsName = '', $currency = '', $price = 0.00, $goodsName2 = ''
    )
    {
        $this->goods[] = [
            'totalMoney' => $totalMoney,
            'number' => $number,
            'price' => $price,
            'goodsName' => $goodsName,
            'weight' => $weight,
            'currency' => $currency,
            'goodsName2' => $goodsName2,
        ];
        return $this;
    }
}