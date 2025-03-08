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

namespace app\store\model\codeList;

/**
 * 文章分类模型
 * Class CodeList
 * @package app\store\model\article
 */
class CodeList extends \app\common\model\codeList\ChildList
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
            'name' => '',
            'status' => -1,
            'childCategoryId' => 0,
        ]);
        // 检索查询条件
        $filter = [];

        !empty($params['name']) && $filter[] = ['name', 'like', "%{$params['name']}%"];

        $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];
        $params['childCategoryId'] > 0 && $filter[] = ['child_category_id', '=', $params['childCategoryId']];
        // 查询列表数据
        return $this->with(['childCategory'])
            ->where($filter)
            ->where('is_delete','=',0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15);

    }
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add(array $data)
    {
        // 保存记录
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        // 保存记录
        return $this->save($data);
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 获取总数量
     * @param array $where
     * @return int|string
     */
    public static function getArticleTotal(array $where = [])
    {
        return (new static)->where($where)->where('is_delete', '=', 0)->count();
    }

}
