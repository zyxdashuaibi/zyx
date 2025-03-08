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

namespace app\common\model;

use cores\BaseModel;

/**
 * 模板模型
 * Class Muban
 * @package app\common\model
 */
class Muban extends BaseModel
{
    // 定义表名
    protected $name = 'bbf_muban';

    // 定义主键
    protected $pk = 'muban_id';

    // 追加字段
    protected $append = ['show_views'];

    /**
     * 关联文章封面图
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('UploadFile', 'file_id', 'image_id')
            ->bind(['image_url' => 'preview_url']);
    }

    /**
     * 关联文章分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        $module = self::getCalledModule();
        return $this->BelongsTo("app\\{$module}\\model\\muban\\Category", 'category_id');
    }

    /**
     * 关联文章分类表
     * @return \think\model\relation\BelongsTo
     */
    public function categoryName()
    {
        $module = self::getCalledModule();
        return $this->BelongsTo("app\\{$module}\\model\\muban\\CategoryName", 'category_name_id');
    }


    /**
     * 展示的浏览次数
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getShowViewsAttr($value, $data)
    {
        return $data['virtual_views'] + $data['actual_views'];
    }

    /**
     * 文章详情
     * @param int $mubanId
     * @param array $with
     * @return array|null|static
     */
    public static function detail(int $mubanId, array $with = [])
    {
        return self::get($mubanId, $with);
    }
}
