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
    public function createOrder($orderSn);
    public function setAddress($address1, $address2 = '', $areaName = '', $cityName = '', $provinceName = '', $countryName = '');
}