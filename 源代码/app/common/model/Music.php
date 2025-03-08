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
 * 音乐模型
 * Class Article
 * @package app\common\model
 */
class Music extends BaseModel
{
    // 定义表名
    protected $name = 'music';

    // 定义主键
    protected $pk = 'music_id';



    /**
     * 关联音乐封面图
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('UploadFile', 'file_id', 'image_id')
            ->bind(['image_url' => 'preview_url']);
    }

    /**
     * 关联音乐分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        $module = self::getCalledModule();
        return $this->BelongsTo("app\\{$module}\\model\\music\\Category", 'category_id');
    }

    /**
     * 音乐详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
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
     * 音乐详情
     * @param int $articleId
     * @param array $with
     * @return array|null|static
     */
    public static function detail(int $articleId, array $with = [])
    {
        return self::get($articleId, $with);
    }
}
