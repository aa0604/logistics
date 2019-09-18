<?php


namespace xing\logistics\core;

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
    public function addOrder($orderSn, $logistics = '');
    // 执行创建订单
    public function executeCreateOrder();


    // 获取订单详细
    public function getOrderInfo($orderSn, $other = []);

    /**
     * 取消/删除订单
     * @param $myOrderSn
     * @param string $apiOrderSn
     * @return boolean
     */
    public function cancelOrder($myOrderSn, $apiOrderSn = '');

    // 增加地址
    public function addAddress($address1, $address2 = '', $areaName = '', $cityName = '', $provinceName = '', $countryName = '');

    // 增加发货人
    public function addConsignor($address, $name, $mobile, $cityName = '', $provinceName = '', $countryName = '', $areaName = '', $tel = '');

    public function getShippingMethod();

    // 检查结果是否成功
    public function checkResultSuccess();


    // 获取请求错误信息
    public function getResultError();

    /**
     * 获取可打印的面单文件地址
     * @param array $logisticsSn 物流单号
     * @param string $format
     * @return mixed
     */
    public function getPrintUrls(array $logisticsSn, $format = 'A4');

//    public function checkResultStatus();
}