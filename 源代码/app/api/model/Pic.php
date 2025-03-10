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

use app\common\model\Pic as PicModel;

/**
 * 图片模型
 * Class Article
 * @package app\store\model
 */
class Pic extends PicModel
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
            'userId' => '',    // 图片标题
            'categoryId' => 0,    // 图片分类id
            'status' => -1,    // 图片状态
        ]);
        // 检索查询条件
        $filter = [];
        if($params['userId'] != null &&  $params['categoryId'] == 0){
            // 图片标题
            !empty($params['userId']) && $filter[] = ['user_id', '=', $params['userId']];
        }else{
            // 图片分类id
            $params['categoryId'] > 0 && $filter[] = ['category_id', '=', $params['categoryId']];
        }

        // 图片状态
        $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];
        // 查询列表数据
        return $this->with(['image'])
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 新增记录
     * @param array $data
     * @return false|int
     */
    public function add(array $data)
    {
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        return $this->save($data) !== false;
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
     * 获取图片总数量
     * @param array $where
     * @return int|string
     */
    public static function getArticleTotal(array $where = [])
    {
        return (new static)->where($where)->where('is_delete', '=', 0)->count();
    }


}
