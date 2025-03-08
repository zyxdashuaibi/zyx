<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\common\library;

use think\facade\Log as LogFacade;

class Log
{
    // 日志内容
    private static $data = [];

    /**
     * 写入日志
     * @param mixed $value
     * @param string $type
     */
    public static function record($value, string $type = 'info')
    {
        $content = is_string($value) ? $value : print_r($value, true);
        LogFacade::record($content, $type);
    }

    /**
     * 写入日志 (使用追加的方式, 索引值是name)
     * @param string $name 日志记录名
     * @param array $data 记录内容
     */
    public static function append(string $name, array $data)
    {
        $merge = array_merge(compact('name'), $data);
        if (isset(static::$data[$name])) {
            $merge = array_merge(static::$data[$name], $merge);
        }
        static::$data[$name] = $merge;
    }

    /**
     * 在应用结束时将追加的日志数据写入到文件
     */
    public static function end()
    {
        foreach (static::$data as $name => $item) {
            static::record(array_merge(['name' => $name], $item));
        }
    }
}