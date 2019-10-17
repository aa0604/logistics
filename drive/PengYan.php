<?php


namespace xing\logistics\drive;


use xing\logistics\core\LogisticsInterface;

/**
 * Class PengYan
 * @package xing\drive
 */
class PengYan extends \xing\logistics\core\LogisticsApiBase implements LogisticsInterface
{

    public $signTime = '';

    private $orderSn = '';

    public static $printFormat = [
        'A4' => 'LABEL_A4',
    ];

    /*public function checkResultStatus()
    {
        return isset($this->result['code']) && $this->result['code'] == 200;
    }*/

    /**
     * 检查返回的结果是否操作成功
     * @return bool
     */
    public function checkResultSuccess()
    {
        return isset($this->result['code']) && $this->result['code'] == 200
            && isset($this->result['success']) && $this->result['success'] === true
//            && isset($this->result['data']) && $this->result['data']['success'] === true
            ;
    }


    public function getOrderInfo($orderSn, $other = [])
    {
        $url = '/osc-openapi/api/order/queryByAnxOrderCodes';
        $this->log('请求网址：' . $url);
        $post = [
            'custOrderCode' => $orderSn,
            'prodCode' => $other['prodCode'],
            'endCustPhone' => $other['mobile'] ?? $this->contacts['mobile'] ?? '',
            'prodCode' => $other['prodCode'] ?? $this->data['prodCode'] ?? 'PACKAGE',
        ];
        $post = array_merge($post, $other);
        $result = $this->post($url, $post);
        return $result;
    }
    // 取消/删除订单
    public function cancelOrder($myOrderSn, $apiOrderSn = [])
    {
        $url = '/pengyan/osc-openapi/api/order/batchCancel';
        $post = [
            'anxOrderCodes' => $apiOrderSn,
        ];

        $this->post($url, $post);
        return $this->checkResultSuccess();
    }

    public function executeCreateOrder()
    {
        $this->log('\xing\logistics\drive\PengYan::executeCreateOrder 准备执行创建订单，数据初始化');
        $post = $this->data;
        $post['weight'] = $this->sumGoodsWeight();
        $post['endAddress'] = $this->getFullAddrees();

        // 地址信息
        $post['endAddress'] = [
            'cty' => $this->address['countryName'],
            'ctyCode' => $this->countryCode,
            'postalCode' => $this->cityCode,
            'addr' => $this->address['address1'],
            'addrTwo' => $this->address['address2'],
            'dist' => $this->address['areaName'],
            'city' => $this->address['cityName'],
            'prov' => $this->address['provinceName'],
        ];
        // 收货人信息
        $post['endCustName'] = $this->contacts['name'] ?? '';
        $post['endCustPhone'] = $this->contacts['mobile'] ?? '';
        $post['endCustTel'] = $this->contacts['tel'] ?: $this->contacts['mobile'] ?: '';

        // 发货人信息
        $post['startAddress'] = [
            'cty' => $this->consignor['countryName'],
            'prov' => $this->consignor['provinceName'] ?? '',
            'city' => $this->consignor['cityName'] ?? '',
            'dist' => $this->consignor['areaName'] ?? '',
            'addr' => $this->consignor['address'] ?? '',
            'ctyCode' => $this->consignorCountryCode,
            'postalCode' => $this->consignorCityCode

        ];
        $post['startCustName'] = $this->consignor['name'] ?? '';
        $post['startCustPhone'] = $this->consignor['mobile'] ?? '';
        $post['startCustTel'] = $this->consignor['tel'] ?? '';

        // 货物信息
//        $post['length'] = $post['height'] = $post['width'] = 1;
        $goodsDesc = array_filter(array_column($this->goods, 'goodsName'));
        $post['goodsDesc'] = implode(';', $goodsDesc) ?: '无';
        $goodsList = [];
        foreach ($this->goods as $good) {
            $goodsList[] = [
                'goodsValue' => round($good['totalMoney'], 2),
                'placeWeight' => $good['weight'] * $good['number'],
                'quantity' => $good['number'],
                'curr' => 'USD',
                'goodsNameCn' => $good['goodsName'],
                'goodsNameEn' => $good['goodsName2'],
            ];
        }
        $post['goodsList'] = $goodsList;

        $this->result = $this->post('/pengyan/osc-openapi/api/order/batchCreate', [$post]);

        return $this->result;
    }

    /**
     * 获取签名
     * @param null $time
     * @return string
     */
    protected function getSign($time = null)
    {
        return md5($this->config['custCode'] . $this->config['apiKey'] . $time);
    }

    /**
     * 获取物流
     * @return string
     */
    public function getShippingMethod()
    {
        $list = $this->post('/pengyan/aopsc-openapi/api/platform/order/queryProduct');
        if (!$this->checkResultSuccess()) throw new \Exception($this->getResultError(), $this->result['code'] ?? null);
        return $list['data'];
    }

    /**
     * 访问资源
     * @param $url
     * @param array $post
     * @param array $header
     * @return string
     */
    public function post($url, $post = [], $header = ['Content-type: application/json;charset=\'utf-8\''])
    {
        $time = time();
        $url .= '?' . http_build_query(['t' => $time, 'custCode' => $this->config['custCode'], 'sign' => $this->getSign($time)]);
//        print_r($post);die();
        if (!empty($post)) {
            $post = json_encode($post, JSON_UNESCAPED_UNICODE);
            $header[] = 'Content-Length: ' . strlen($post);
            $result = parent::post($url, $post, $header);
        } else {

            $this->url = $this->apiDomain . $url;
            $result = file_get_contents($this->url);
        }

        $this->log('LogisticsApiBase::post 开始发送请求：');
        $this->log('url：' . $this->url . "\r\n 报文：");
        $this->log($post);

        $this->log('请求返回的原文：' . $result);
        return $this->result = json_decode($result, 1);
    }

    /**
     * 增加订单
     * @param $orderSn
     * @param null $logistics
     * @param null $weight
     * @param null $length
     * @param null $width
     * @param null $height
     * @return $this|mixed
     */
    public function addOrder($orderSn, $logistics = null, $weight = null, $length = null, $width = null, $height = null)
    {
        $this->data['custOrderCode'] = $orderSn;
        $this->data['prodCode'] = $logistics;
        !empty($width) && $this->data['width'] = $width;
        !empty($weight) && $this->data['weight'] = $weight;
        !empty($height) && $this->data['height'] = $height;
        !empty($length) && $this->data['length'] = $length;
        return $this;
    }

    public function getResultError()
    {
        if (!$this->checkResultSuccess()) {
            return $this->result['message'] ?? $this->result['msg'] ?? '未能获取到错误信息（非接口信息）';
        }
        return null;
    }

    /**
     * 获取打印面单的文件地址（多个）
     * @param array $logisticsSn
     * @param string $format
     * @return array|mixed
     * @throws \Exception
     */
    public function getPrintUrls(array $logisticsSn, $format = 'A4')
    {
        $url = '/pengyan/aopsc-openapi/api/platform/order/getPrintPdf';
        $post = [
            'waybillCodes' => $logisticsSn,
            'format' => self::$printFormat[$format] ?? '',
        ];

        $result = $this->post($url, $post);
        if (!$this->checkResultSuccess()) {
            throw new \Exception('鹏雁请求失败');
        }

        $files = [];
        if (is_array($result['data'])) {
            foreach ($result['data'] as $data) {
                // 测试地址可能会需要转换
                $labelUrl = preg_replace('/http:\/\/192\.168\.0\.[0-9]+\//i', $this->printDomain, $data['labelUrl']);
                $files[] = $labelUrl;
            }
        } else {
            throw new \Exception('暂时无数据', static::CODE_EMPTY);
        }

        return $files;

    }
}