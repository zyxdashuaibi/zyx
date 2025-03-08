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

use app\common\model\Muban as MubanModel;
use app\common\model\muban\Category as CategoryModel;

/**
 * 模板模型
 * Class Muban
 * @package app\store\model
 */
class Muban extends MubanModel
{
    /**
     * 获取列表
     * @param int $categoryNameId
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(int $categoryNameId = 0, int $limit = 15)
    {
        // 检索查询条件
        $filter = [];
        $categoryNameId > 0 && $filter[] = ['category_name_id', '=', $categoryNameId];
        return $this->with(['image'])
//            ->field(['muban_id','title','category_name_id','category_id','image_id','preview_url','content'])
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($limit);
    }

}
