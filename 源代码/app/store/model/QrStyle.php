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

namespace app\store\model;

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
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [])
    {
        // 查询参数
        $params = $this->setQueryDefaultValue($param, [
            'title' => '',    // 文章标题
            'categoryId' => 0,    // 文章分类id
            'status' => -1,    // 文章状态
        ]);
        // 检索查询条件
        $filter = [];
        // 文章状态
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
        if (empty($data['image_id'])) {
            $this->error = '请上传封面图';
            return false;
        }
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
        if (empty($data['image_id'])) {
            $this->error = '请上传封面图';
            return false;
        }
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
