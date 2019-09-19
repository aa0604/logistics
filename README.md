# logistics
统一的物流接口，目前已接：鹏雁物流

注：国外物流有的对城市，地址，邮编，商品名称，中/英文检查很严格，一定要对，有些垃圾接口如果你的数据不严谨，它给你返回的错误信息会弄得你哭的，返回的错误信息牛对不对马嘴，你别想根据它的信息去解决你遇到的问题，老老实实把数据弄对了再去研究别的问题。

### 重量单位
本接口的重量单位为克，如果你的系统用的是千克，那就需要自己乘1000转换为克

# 目录
* [安装](#安装)
* [物流列表](#物流列表)
* [接口列表](#接口列表)
    * [初始化](#初始化)
    * [调试模式](#调试模式)
    * [创建订单](#创建订单)
    * [获取订单详情](#获取订单详情)
    * [取消/删除订单](取消/删除订单)
    * [获取面单打印url](#获取面单打印url)
    
# 安装
```$xslt
composer require xing.chen/logistics dev-master
```
# 物流列表
鹏雁：PengYan

## 初始化
使用很简单，先初始化，然后执行创建订单动作即可。
```php
<?php
$driveName = '物流驱动名称（见物流列表）';
$service = \xing\logistics\core\LogisticsFactory::getInstance($driveName)->config($config);
// 配置

?>


```
## 调试模式
```php
<?php
$service->debug(true);
```

## 创建订单

```php
<?php
// 收货人
$service->setConsignee('mame', '手机号')
// 收货地址
->addAddress('地址1', '地址2', '区', '市', '省', '国家名称')
// 发货人信息
->addConsignor('地址', '名字', '手机', '城市名', '省', '国家', '地区', '固话')
// 收货人和发货人的城市、国家代码
->setCode('收货人城市邮编', '收货人国家代码', '发件人国家代码', '发件人城市邮编')
// 增加订单
->addOrder('我的订单号', '使用物流产品编码')
// 执行创建订单
->executeCreateOrder();
```

## 获取面单打印url

```php
<?php
$service->getPrintUrls(['我方订单号/物流内部单号'], '尺寸，如A4，（目前未完全支持）');
```

## 获取订单详情
计划未来有机会再增加统一返回字段，目前是返回各物流不同的字段，需自行处理
```php
<?php
$service->getOrderInfo('订单号', []);
```

## 取消/删除订单
```php
<?php
$service->cancelOrder('我方订单号', '物流接口内部订单号');
```