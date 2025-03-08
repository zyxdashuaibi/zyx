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

namespace cores\exception;

use think\Exception;

/**
 * 自定义异常类的基类
 * Class BaseException
 * @package cores\exception
 */
class BaseException extends Exception
{
    // 状态码
    public $status;

    // 错误信息
    public $message = '';

    // 输出的数据
    public $data = [];

    /**
     * 构造函数，接收一个关联数组
     * @param array $params 关联数组只应包含status、msg、data，且不应该是空值
     */
    public function __construct($params = [])
    {
        parent::__construct();
        $this->status = $params['status'] ?? config('status.error');
        $this->message = $params['message'] ?? '很抱歉，服务器内部错误';
        $this->data = $params['data'] ?? [];
    }
}

