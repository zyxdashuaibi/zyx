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

use app\common\model\Words as WordsModel;

/**
 * 敏感字模型
 * Class Words
 * @package app\store\model
 */
class Words extends WordsModel
{
    /**
     * 获取列表
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [])
    {
        // 查询参数
        $params = $this->setQueryDefaultValue($param, [
            'content' => '',    // 敏感字
            'status' => -1,    // 文章状态
        ]);
        // 检索查询条件
        $filter = [];
        // 音乐标题
        !empty($params['content']) && $filter[] = ['content', 'like', "%{$params['content']}%"];
        // 文章状态
        $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];
        // 查询列表数据
        return $this->where($filter)
            ->where('is_delete', '=', 0)
            ->order([ 'id' => 'desc'])
            ->paginate(15);
    }

    /**
     * @return Words[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll(){
        return $this->field(['content'])
            ->where('is_delete','=',0)
            ->where('status','=',1)
            ->select();
    }

}
