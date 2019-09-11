<?php


namespace xing\core;


class LogisticsFactory
{

    /**
     * @param string $drive
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance($drive)
    {
        $set = [
            'PengYan' => '\xing\drive\PengYan',
        ];
        $class = $set[$drive] ?? null;
        if (empty($class)) throw new \Exception('没有这个驱动');
        return new $class;
    }
}