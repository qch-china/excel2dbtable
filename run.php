<?php

header("Content-Type: text/html;charset=utf-8");

require_once 'vendor/autoload.php';
require_once 'db_service.php';
require_once 'excel_service.php';

$config = require 'config.php';

$db_service = new db_service($config['db_host'], $config['db_user'],
    $config['db_pwd'], $config['db_name']);
$excel_service = new excel_service($config['excel_name']);

$table_name = $config['table_name'];
$field_length = empty($config['field_length']) ? 1024 : $config['field_length'];

// 删除原来的表
if ($config['drop_table'] == true) {
    $db_service->write_data("drop table if exists `{$table_name}`;");
}

// 获取excel文件中的数据
$header = $excel_service->get_excel_header();
$data = $excel_service->get_excel_content($header);

// 创建表（如果不存在的话）
$db_service->create_table($table_name, $header, $field_length);

// 将excel文件中的数据插入数据库表中
$count = 0;
foreach ($data as $value) {
    $insert_id = $db_service->insert_record($table_name, $value);
    $count++;
    echo "id = {$insert_id} ok\n";
    if ($config['show_detail'] == true) {
        echo json_encode($value)."\n";
    }
}

// 完成
echo $count.' ok';

