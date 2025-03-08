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
namespace app\common\library;

/**
 * 工具类
 * Class helper
 * @package app\common\library
 */
class helper
{
    /**
     * 从object中选取属性
     * @param $source
     * @param array $columns
     * @return array
     */
    public static function pick($source, array $columns): array
    {
        $dataset = [];
        foreach ($source as $key => $item) {
            in_array($key, $columns) && $dataset[$key] = $item;
        }
        return $dataset;
    }

    /**
     * 获取数组中指定的列
     * @param $source
     * @param $column
     * @return array
     */
    public static function getArrayColumn($source, $column): array
    {
        $columnArr = [];
        foreach ($source as $item) {
            isset($item[$column]) && $columnArr[] = $item[$column];
        }
        return $columnArr;
    }

    /**
     * 获取数组中指定的列 [支持多列]
     * @param $source
     * @param $columns
     * @return array
     */
    public static function getArrayColumns($source, $columns): array
    {
        $columnArr = [];
        foreach ($source as $item) {
            $temp = [];
            foreach ($columns as $index) {
                $temp[$index] = $item[$index];
            }
            $columnArr[] = $temp;
        }
        return $columnArr;
    }

    /**
     * 把二维数组中某列设置为key返回
     * @param $source
     * @param $index
     * @return array
     */
    public static function arrayColumn2Key($source, $index): array
    {
        $data = [];
        foreach ($source as $item) {
            $data[$item[$index]] = $item;
        }
        return $data;
    }

    /**
     * 格式化价格显示
     * @param mixed $number
     * @param bool $isMinimum 是否存在最小值
     * @param float $minimum
     * @return string
     */
    public static function number2($number, bool $isMinimum = false, float $minimum = 0.01): string
    {
        $isMinimum && $number = max($minimum, $number);
        return sprintf('%.2f', $number);
    }

    public static function getArrayItemByColumn($array, $column, $value)
    {
        foreach ($array as $item) {
            if ($item[$column] == $value) {
                return $item;
            }
        }
        return false;
    }

    /**
     * 获取二维数组中指定字段的和
     * @param $array
     * @param $column
     * @return float|int
     */
    public static function getArrayColumnSum($array, $column)
    {
        $sum = 0;
        foreach ($array as $item) {
            $sum = self::bcadd($sum, $item[$column]);
        }
        return $sum;
    }

    /**
     * 在二维数组中查找指定值
     * @param array $array 二维数组
     * @param string $searchIdx 查找的索引
     * @param mixed $searchVal 查找的值
     * @return bool|mixed
     */
    public static function arraySearch(array $array, string $searchIdx, $searchVal)
    {
        foreach ($array as $item) {
            if ($item[$searchIdx] == $searchVal) {
                return $item;
            }
        }
        return false;
    }

    public static function setDataAttribute(&$source, $defaultData, $isArray = false)
    {
        if (!$isArray) $dataSource = [&$source]; else $dataSource = &$source;
        foreach ($dataSource as &$item) {
            foreach ($defaultData as $key => $value) {
                $item[$key] = $value;
            }
        }
        return $source;
    }

    public static function bcsub($leftOperand, $rightOperand, $scale = 2): string
    {
        return \bcsub($leftOperand, $rightOperand, $scale);
    }

    public static function bcadd($leftOperand, $rightOperand, $scale = 2): string
    {
        return \bcadd($leftOperand, $rightOperand, $scale);
    }

    public static function bcmul($leftOperand, $rightOperand, $scale = 2): string
    {
        return \bcmul($leftOperand, $rightOperand, $scale);
    }

    public static function bcdiv($leftOperand, $rightOperand, int $scale = 2): ?string
    {
        return \bcdiv($leftOperand, $rightOperand, $scale);
    }

    /**
     * 浮点数比较
     * 若二个字符串一样大则返回 0；若左边的数字字符串 (left operand) 比右边 (right operand) 的大则返回 +1；若左边的数字字符串比右边的小则返回 -1
     * @param $leftOperand
     * @param $rightOperand
     * @param int $scale
     * @return int
     */
    public static function bccomp($leftOperand, $rightOperand, int $scale = 2): int
    {
        return \bccomp($leftOperand, $rightOperand, $scale);
    }

    /**
     * 比较两个数值是否相等
     * @param $leftOperand
     * @param $rightOperand
     * @param int $scale
     * @return bool
     */
    public static function bcequal($leftOperand, $rightOperand, int $scale = 2): bool
    {
        return self::bccomp($leftOperand, $rightOperand, $scale) === 0;
    }

    /**
     * 数组转为json
     * @param $data
     * @param int $options
     * @return false|string
     */
    public static function jsonEncode($data, int $options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($data, $options);
    }

    /**
     * json转义为数组
     * @param $json
     * @return array|mixed
     */
    public static function jsonDecode($json)
    {
        return json_decode($json, true);
    }

    /**
     * 检查目录是否可写
     * @param $path
     * @return bool
     */
    public static function checkWriteable($path): bool
    {
        try {
            !is_dir($path) && mkdir($path, 0755);
            if (!is_dir($path))
                return false;
            $fileName = $path . '/_test_write.txt';
            if ($fp = fopen($fileName, 'w')) {
                return fclose($fp) && unlink($fileName);
            }
        } catch (\Exception $e) {
        }
        return false;
    }

    /**
     * 记录info日志
     * @param string $name
     * @param array $content
     */
    public static function logInfo(string $name, array $content)
    {
        $content['name'] = $name;
        log_record($content, 'info');
    }

    /**
     * 将字符串转换为字节
     * @param string $from
     * @return int|null
     */
    public static function convertToBytes(string $from): ?int
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $number = substr($from, 0, -2);
        $suffix = strtoupper(substr($from, -2));
        // B or no suffix
        if (is_numeric(substr($suffix, 0, 1))) {
            return preg_replace('/[^\d]/', '', $from);
        }
        $exponent = array_flip($units)[$suffix] ?? null;
        if ($exponent === null) {
            return null;
        }
        return $number * (1024 ** $exponent);
    }
}
