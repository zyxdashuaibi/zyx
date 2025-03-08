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

use app\store\model\codeList\ChildCategory as ListChildCategoryModel;
use app\common\model\codeList\Category as CategoryModel;

/**
 * 大分类模型
 * Class Category
 * @package app\store\model\article
 */
class Category extends CategoryModel
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
        ]);
        // 检索查询条件
        $filter = [];

        !empty($params['name']) && $filter[] = ['name', 'like', "%{$params['name']}%"];

        $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];
        if(isset($param['pageSize']) && $param['pageSize'] == 100){
            // 查询列表数据
            return $this->with(['image'])
                ->where($filter)
                ->order(['sort' => 'asc', 'create_time' => 'desc'])
                ->select();
        }else{
            // 查询列表数据
            return $this->with(['image'])
                ->where($filter)
                ->order(['sort' => 'asc', 'create_time' => 'desc'])
                ->paginate(15);
        }

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
     * 删除大分类
     * @param int $categoryId
     * @return bool
     * @throws \Exception
     */
    public function remove(int $categoryId)
    {
        // 判断是否存在文章
        $articleCount =ListChildCategoryModel::getArticleTotal(['category_id' => $categoryId]);
        if ($articleCount > 0) {
            $this->error = '该大分类下存在' . $articleCount . '个子分类，不允许删除';
            return false;
        }
        // 删除记录
        return $this->delete();
    }

}
