<?php


namespace xing\logistics\core;

use xing\logistics\drive\PengYan;

/**
 * 物流工厂
 * Class LogisticsFactory
 * @package xing\core
 */
class LogisticsFactory
{

    /**
     * @param string $drive
     * @return PengYan
     * @throws \Exception
     */
    public static function getInstance($drive)
    {
        $set = [
            'PengYan' => '\xing\logistics\drive\PengYan',
        ];
        $class = $set[$drive] ?? null;
        if (empty($class)) throw new \Exception('没有这个驱动');
        return new $class;
    }
}