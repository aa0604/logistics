<?php


namespace xing\core;



abstract class LogisticsBase
{

    // 沙箱模式
    private $sandbox = false;


    /**
     * 设置沙箱模式
     * @param $bool
     * @return $this
     */
    public function sandbox($bool)
    {
        $this->sandbox = $bool;
        return $this;
    }

}