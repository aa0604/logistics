# logistics
物流接口，目前已接：鹏雁物流

注：国外物流有的对城市，地址，邮编，商品名称，中/英文检查很严格，一定要对，有些垃圾接口如果你的数据不严谨，它给你返回的错误信息会弄得你哭的，返回的错误信息牛对不对马嘴，你别想根据它的信息去解决你遇到的问题，老老实实把数据弄对了再去研究别的问题。

### 重量单位
本接口的重量单位为克，如果你的系统用的是千克，那就需要自己乘1000转换为克

# 目录
* [安装](#安装)
* [开始使用](#开始使用)
* [接口列表](#使用示例)
    * [签名示例代码](#获取签名示例代码)
    
# 安装
```$xslt
composer require xing.chen/logistics dev-master
```

# 开始使用
使用很简单，先初始化，然后执行创建订单动作即可。
```php
<?php
$driveName = '物流驱动名称（见驱动名称列表）';
$service = \xing\core\LogisticsFactory::getInstance($driveName);
// 配置
$service->config($config)
->debug(static::DEBUG)
    // 收货人
    ->setConsignee('mame', '手机号')
    // 收货地址
    ->addAddress('地址1', '地址2', '区', '市', '省', '国家名称')
    // 发货人信息
    
    ->addConsignor(...)
    
    // 收货人和发货人的城市、国家代码
    ->setCode('收货人城市邮编', '收货人国家代码', '发件人国家代码', '发件人城市邮编')
    // 增加订单
    ->addOrder('我的订单号', '使用物流产品编码')
    // 执行创建订单
    ->executeCreateOrder();
?>


```
# 使用示例

