<?php


namespace xing\core;


interface LogisticsInterface
{

     public function dealCreateOrder($orderId, $omsOrder, $shippingMethodWarehouseData, $address, $OmsOrderItemInfo);
}