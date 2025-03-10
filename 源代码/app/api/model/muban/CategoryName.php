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

namespace app\api\model\muban;

use app\api\model\Muban as MubanModel;
use app\common\model\muban\CategoryName as CategoryNameModel;

/**
 * 模板分类模型
 * Class CategoryName
 * @package app\store\model\Muban
 */
class CategoryName extends CategoryNameModel
{
    /**
     * 获取分类列表(未隐藏的)
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getShowList()
    {
        $where = ['status', '=', 1];
        return $this->getList([$where]);
    }

}
