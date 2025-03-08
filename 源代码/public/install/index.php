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

header('Content-Type:text/html; charset=utf-8');

// 检测php版本号
if (phpversion() < '7.1') {
    exit('很抱歉，由于您的PHP版本过低，不能安装本软件，为了系统功能全面可用，请升级到PHP7.1或更高版本再安装，谢谢！');
}

// 不限制响应时间
// error_reporting(0);
set_time_limit(0);

// 设置系统路径
define('IN_INSTALL', true);
define('INSTALL_PATH', str_replace('\\', '/', dirname(__FILE__)));
define('ROOT_PATH', dirname(INSTALL_PATH, 2));

// 版权信息设置
$cfg_copyright = '© 2018-2022 YIOVO.COM';

// 获取当前步骤
$s = getStep();

// 提示已经安装
if (is_file(INSTALL_PATH . '/install.lock') && $s != md5('done')) {
    require_once(INSTALL_PATH . '/templates/step_5.php');
    exit();
}

// 执行相应操作
$GLOBALS['isNext'] = true;

// 获取当前步骤
function getStep()
{
    $s1 = $_GET['s'] ?? 0;
    // 初始化参数
    $s2 = $_POST['s'] ?? 0;
    // 如果有GET值则覆盖POST值
    if ($s1 > 0 && in_array($s1, [1, 63832, md5('done')])) {
        $s2 = $s1;
    }
    return $s2;
}

// 协议说明
if ($s == 0) {
    require_once(INSTALL_PATH . '/templates/step_0.php');
    exit();
}
// 环境检测
if ($s == 1) {
    // 获取检测的路径数据
    $iswrite_array = getIsWriteArray();
    // 获取检测的函数数据
    $exists_array = getExistsFuncArray();
    // 获取扩展要求数据
    $extendArray = getExtendArray();
    // 引入环境检测html
    require_once(INSTALL_PATH . '/templates/step_1.php');
    exit();
}
// 配置文件
if ($s == 2) {
    require_once(INSTALL_PATH . '/templates/step_2.php');
    exit();
}
// 正在安装
if ($s == 3) {
    require_once(INSTALL_PATH . '/templates/step_3.php');

    if ($_POST['s'] == 3) {

        // 初始化信息
        $dbhost = $_POST['dbhost'] ?? '';
        $dbname = $_POST['dbname'] ?? '';
        $dbuser = $_POST['dbuser'] ?? '';
        $dbpwd = $_POST['dbpwd'] ?? '';
        $dbport = $_POST['dbport'] ?? 3306;

        $testdata = $_POST['testdata'] ?? '';

        // 连接证数据库
        try {
            $dsn = "mysql:host={$dbhost};port={$dbport};charset=utf8";
            $pdo = new PDO($dsn, $dbuser, $dbpwd);
            $pdo->query("SET NAMES utf8"); // 设置数据库编码
        } catch (Exception $e) {
            insError('数据库连接错误，请检查！');
        }

        // 查询数据库
        $res = $pdo->query('show Databases');

        // 遍历所有数据库，存入数组
        $dbnameArr = [];
        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $dbnameArr[] = $row['Database'];
        }

        // 检查数据库是否存在，没有则创建数据库
        if (!in_array(trim($dbname), $dbnameArr)) {
            if (!$pdo->exec("CREATE DATABASE `$dbname`")) {
                insError("创建数据库失败，请检查权限或联系管理员！");
            }
        }

        // 数据库创建完成，开始连接
        $pdo->query("USE `$dbname`");

        // 取出.env模板内容
        $config_str = readDataFile('.env.tpl');

        // 进行替换
        $config_str = str_replace('~db_host~', $dbhost, $config_str);
        $config_str = str_replace('~db_name~', $dbname, $config_str);
        $config_str = str_replace('~db_user~', $dbuser, $config_str);
        $config_str = str_replace('~db_pwd~', $dbpwd, $config_str);
        $config_str = str_replace('~db_port~', $dbport, $config_str);
        $config_str = str_replace('~db_charset~', 'utf8', $config_str);

        // 将替换后的内容写入.env文件
        $fp = fopen(ROOT_PATH . '/.env', 'w');
        fwrite($fp, $config_str);
        fclose($fp);

        // 防止浏览器缓存
        $buffer = ini_get('output_buffering');
        echo str_repeat(' ', $buffer + 1);

        insInfo("数据库连接文件创建完成！");
        ob_flush();
        flush();

        // 创建表结构
        $tbstruct = readDataFile('install_struct.sql');
        $pdo->exec(trim($tbstruct));

        insInfo("数据库结构导入完成！");
        ob_flush();
        flush();

        // 导入其他安装数据
        $data_str = readDataFile('install_data.sql');
        $pdo->exec(trim($data_str));

        insInfo("商城默认数据导入完成！");
        ob_flush();
        flush();

        // 查看是否需要安装测试数据
        if ($testdata == 'true') {
            insInfo("正在加载测试数据！");
            ob_flush();
            flush();

            $sqlstr_file = readDataFile('install_testdata.sql');
            $pdo->exec(trim($sqlstr_file));

            insInfo("测试数据导入完成！");
            ob_flush();
            flush();
        }

        // 结束缓存区
        ob_end_flush();

        // 安装完成进行跳转
        echo '<script>setTimeout(function () { location.href="?s=' . md5('done') . '"; }, 2000)</script>';
        exit();
    }
    exit();
}
// 检测数据库信息
if ($s == 63832) {
    $dbhost = $_GET['dbhost'] ?? '';
    $dbuser = $_GET['dbuser'] ?? '';
    $dbpwd = $_GET['dbpwd'] ?? '';
    $dbport = $_GET['dbport'] ?? '';
    try {
        $dsn = "mysql:host=$dbhost;port={$dbport};charset=utf8";
        $pdo = new PDO($dsn, $dbuser, $dbpwd);
        echo 'true';
    } catch (Exception $e) {
        echo 'false';
    }
    exit();
}
// 安装完成
if ($s == md5('done')) {
    require_once(INSTALL_PATH . '/templates/step_4.php');
    $fp = fopen(INSTALL_PATH . '/install.lock', 'w');
    fwrite($fp, '程序已正确安装，重新安装请删除本文件');
    fclose($fp);
    exit();
}

// 获取扩展要求数据
function getExtendArray()
{
    $data = [
        [
            'name' => 'CURL',
            'status' => extension_loaded('curl'),
        ],
        [
            'name' => 'OpenSSL',
            'status' => extension_loaded('openssl'),
        ],
        [
            'name' => 'PDO Mysql',
            'status' => extension_loaded('PDO') && extension_loaded('pdo_mysql'),
        ],
        [
            'name' => 'GD',
            'status' => extension_loaded('gd'),
        ],
        [
            'name' => 'BCMath',
            'status' => extension_loaded('bcmath'),
        ],
        [
            'name' => 'mbstring',
            'status' => extension_loaded('mbstring'),
        ],
        [
            'name' => 'SimpleXML',
            'status' => extension_loaded('SimpleXML'),
        ]
    ];
    foreach ($data as $item) {
        !$item['status'] && setIsNext(false);
    }
    return $data;
}

// 获取检测的路径数据
function getIsWriteArray()
{
    return [
        '/.env',
        // '/data/',
        '/public/install/',
        '/public/uploads/',
        '/public/temp/',
    ];
}

// 获取检测的函数数据
function getExistsFuncArray()
{
    return ['curl_init', 'bcadd', 'mb_substr', 'simplexml_load_string'];
}

// 测试可写性
function isWrite($file)
{
    if (is_writable(ROOT_PATH . $file)) {
        echo '<span>可写</span>';
    } else {
        echo '<span class="col-red">不可写</span>';
        setIsNext(false);
    }
}

// 测试函数是否存在
function isFunExists($func)
{
    $state = function_exists($func);
    if ($state === false) {
        setIsNext(false);
    }
    return $state;
}

// 测试函数是否存在
function isFunExistsTxt($func)
{
    if (isFunExists($func)) {
        echo '<span>无</span>';
    } else {
        echo '<span class="col-red">需安装</span>';
        setIsNext(false);
    }
}

// 清除txt中的BOM
function clearBOM($contents)
{
    $charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);
    if (ord($charset[1]) == 239 &&
        ord($charset[2]) == 187 &&
        ord($charset[3]) == 191) {
        return substr($contents, 3);
    } else {
        return $contents;
    }
}

// 设置是否允许下一步
function setIsNext(bool $bool)
{
    $GLOBALS['isNext'] = $bool;
}

// 获取data文件夹中的文件内容
function readDataFile(string $file)
{
    return file_get_contents(INSTALL_PATH . '/data/' . $file);
}

function insInfo($str)
{
    echo '<script>$("#install").append("' . $str . '<br>");</script>';
}

function insError($str, $isExit = false)
{
    insInfo("<span class='col-red'>$str</span>");
    exit();
}