# logistics
物流

# 使用示例

```php
<?php
$driveName = '物流名称（见驱动名称列表）';
$service = \xing\core\LogisticsFactory::getInstance($driveName);
// 配置
$service->config($config);
?>
```