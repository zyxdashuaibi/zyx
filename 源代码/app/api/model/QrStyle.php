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

namespace app\api\model;

use app\common\model\QrStyle as QrStyleModel;

/**
 * 文章模型
 * Class QrStyle
 * @package app\store\model
 */
class QrStyle extends QrStyleModel
{
    /**
     * 获取列表
     * @return QrStyle[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        // 查询列表数据
        return $this->with(['image'])
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 获取二维码总数量
     * @param array $where
     * @return int|string
     */
    public static function getQrStyleTotal(array $where = [])
    {
        return (new static)->where($where)->where('is_delete', '=', 0)->count();
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
