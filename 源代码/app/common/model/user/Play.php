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
declare (strict_types = 1);

namespace app\common\model\user;

use cores\BaseModel;

/**
 * 用户积分变动明细模型
 * Class Play
 * @package app\common\model\user
 */
class Play extends BaseModel
{
    // 定义表名
    protected $name = 'bbf_play';

    // 定义主键
    protected $pk = 'id';

    protected $updateTime = false;

    /**
     * 关联会员记录表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule();
        return $this->belongsTo("app\\{$module}\\model\\User",'user_id');
    }
    /**
     * 关联模板表
     * @return \think\model\relation\BelongsTo
     */
    public function muban()
    {
        $module = self::getCalledModule();
        return $this->belongsTo("app\\{$module}\\model\\Muban",'muban_id');
    }

    public function category(){
        $module = self::getCalledModule();
        return $this->belongsTo("app\\{$module}\\model\\muban\\Category",'category_id');
    }

    /**
     * 新增记录
     * @param $data
     */
    public static function add($data)
    {
        $static = new static;
        $static->save(array_merge(['store_id' => $static::$storeId], $data));
    }
    /**
     * 详情
     * @param int $id
     * @param array $with
     * @return array|null|static
     */
    public static function detail(int $id, array $with = [])
    {
        return self::get($id, $with);
    }

}
