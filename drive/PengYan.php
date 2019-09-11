<?php


namespace xing\drive;


use xing\core\LogisticsInterface;

/**
 * Class PengYan
 * @package xing\drive
 */
class PengYan extends \xing\core\LogisticsApiBase implements LogisticsInterface
{

    public $signTime = '';

    protected function getSign($time = null)
    {
        return md5($this->config['custCode'] . $this->config['apiKey'] . $time);
    }



    public function createOrder($orderSn, $sku, $consignee, $tel, $address, $weight = null)
    {

    }
}