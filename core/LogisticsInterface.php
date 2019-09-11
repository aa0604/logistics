<?php


namespace xing\core;

/**
 * 接口规范
 * Interface LogisticsInterface
 * @package xing\core
 */
interface LogisticsInterface
{

    public function config($config);

    /**
     * 增加一个订单
     * @param $orderSn
     * @return mixed
     */
    public function addOrder($orderSn);

    public function address($address1, $address2 = '', $areaName = '', $cityName = '', $provinceName = '', $countryName = '');

    // 执行/发送请求
    public function send();
}