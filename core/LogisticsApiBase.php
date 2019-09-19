<?php


namespace xing\logistics\core;


use xing\helper\resource\HttpHelper;

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
 * @property string $consignorCityCode 发货人城市邮编
 * @property string $consignorCountryCode 发货人国家编码
 * @property array $goods 商品
 * @property string $apiDomain 接口域名
 * @property string $printDomain 面单打印域名
 * @property string|array $result 请求结果
 * @property array $contacts 联系人
 * @property array $consignor 发货人
 * @property array $post $_POST 记录提交的请求数据
 * @property string $url 记录发送请求的url
 *
 *
 * @package xing\core
 */
class LogisticsApiBase
{
    private $sandbox = false;
    public $data = [];
    protected $config = [];

    public $address = [];
    public $cityCode;
    public $countryCode;
    public $consignorCityCode = '';
    public $consignorCountryCode = '';

    public $goods;

    protected $apiDomain;
    public $result;
    public $contacts = [];
    public $consignor = [];

    public $post;
    public $url;
    public $debug = false;

    public $printDomain;


    /**
     * 调试输出日志
     * @param $error
     * @param bool $isStop
     */
    public function log($error, $isStop = false)
    {
        if ($this->debug) {
            print_r($error);
            print_r("<br>\r\n");
            if ($isStop) exit();
        }
    }

    public function debug($debug)
    {
        $this->debug = $debug;
        return $this;
    }


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
     * @TODO 设计原则：在这里定义各种非核心参数，因为各个接口的参数键名不一样，比如重量，可以在这里统一为weight，再在各个独立接口将weight转换为各个独立接口独有的键名，之后会增加在send之前自动转换的流程，这样就能实现使用统一的参数进来，各种不一样的参数出去。
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = array_merge($this->data, $data);
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
        $this->apiDomain = $config['apiDomain'] ?? '';
        $this->printDomain = $config['printDomain'] ?? '';
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
    public function addAddress($address1, $address2 = '', $areaName = '', $cityName = '', $provinceName = '', $countryName = '')
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


    public function addConsignor($address, $name, $mobile, $cityName = '', $provinceName = '', $countryName = '', $areaName = '', $tel = '')
    {
        $this->consignor = [
            'name' => $name,
            'mobile' => $mobile,
            'tel' => $tel,
            'address' => $address,
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
    public function setCode(string $cityCode, ? string $countryCode, $consignorCountryCode = '', $consignorCityCode = '')
    {
        $this->cityCode = $cityCode;
        $this->countryCode = $countryCode;
        $this->consignorCityCode = $consignorCityCode;
        $this->consignorCountryCode = $consignorCountryCode;
        return $this;
    }

    /**
     * 增加一个商品/货物
     * @param int $number
     * @param int $weight
     * @param int $totalMoney
     * @param float $price
     * @param string $currency
     * @param string $goodsName
     * @param string $goodsName2 商品名称2
     * @return $this
     */
    public function addGoods(
        $number = 1, $weight = 0, $totalMoney = 0,  $price = 0.00, $currency = '', $goodsName = '', $goodsName2 = ''
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


    /**
     * 请求资源
     * @param $url
     * @param array $post
     * @param array $header
     * @return string
     */
    public function post($url, $post = [], $header = [])
    {
        $this->url = $this->apiDomain . $url;
        $this->log('LogisticsApiBase::post 开始发送请求：');
        $this->log('url：' . $this->url . "\r\n 报文：");
        $this->log($post);
        $this->post = $post;

//        print_r($this->post);die($url);
        $this->result = HttpHelper::post($this->url, $post, $header);
        return $this->result;
    }


    public function setConsignee($name, $mobile, $tel = '')
    {
        $this->contacts = [
            'name' => $name,
            'mobile' => $mobile,
            'tel' => $tel,
        ];
        return $this;
    }

    /**
     * 计算商品总重量
     * @return float|int
     */
    protected function sumGoodsWeight()
    {
        $sum = 0;
        foreach ($this->goods as $k => $good)
        {
            $sum += $good['weight'] * $good['number'];
        }
        return $sum;
    }

    /**
     * 获取完整地址
     * @return string
     */
    public function getFullAddrees()
    {

        $address = & $this->address;
        return $address['countryName'] . $address['provinceName'] . $address['cityName'] . $address['areaName']
            . $address['address1']  . $address['address2'];
    }
}