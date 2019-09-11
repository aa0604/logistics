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
    public function dealCreateOrder($orderId, $omsOrder, $shippingMethodWarehouseData, $address, $OmsOrderItemInfo);
}