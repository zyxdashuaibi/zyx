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
 * Class ChildCategory
 * @package app\store\model\article
 */
class ChildCategory extends \app\common\model\codeList\ChildCategory
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
            return $this->with(['category'])
                ->where($filter)
                ->order(['sort' => 'asc', 'create_time' => 'desc'])
                ->select();
        }else{
            // 查询列表数据
            return $this->with(['category'])
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
     * 删除商品分类
     * @param int $categoryId
     * @return bool
     * @throws \Exception
     */
    public function remove(int $categoryId)
    {
        // 判断是否存在文章
        $articleCount = CodeList::getArticleTotal(['child_category_id' => $categoryId]);
        if ($articleCount > 0) {
            $this->error = '该分类下存在' . $articleCount . '个代码列表，不允许删除';
            return false;
        }
        // 删除记录
        return $this->delete();
    }

    /**
     * 获取总数量
     * @param array $where
     * @return int|string
     */
    public static function getArticleTotal(array $where = [])
    {
        return (new static)->where($where)->count();
    }

}
