<?php

header("Content-Type: text/html;charset=utf-8");

require_once 'mysql_service.php';
require_once 'excel_service.php';

$config = require 'config.php';

$mysqli = my_open($config['db_host'], $config['db_user'],
    $config['db_pwd'], $config['db_name']);
$table_name = $config['table_name'];
$field_length = empty($config['field_length']) ? 1024 : $config['field_length'];

if ($config['drop_table'] == true) {
    my_write($mysqli, "drop table if exists `{$table_name}`;");
}

$excel_name = 'excel_file/'.$config['excel_name'];

$header = getExcelHeader($excel_name);

$data = getExcelContent($excel_name, $header);

my_create($mysqli, $table_name, $header, $field_length);

$count = 0;
foreach ($data as $value) {
    $insert_id = my_insert($mysqli, $table_name, $value);
    $count++;
    echo "id = {$insert_id} ok\n";
    if ($config['show_detail'] == true) {
        echo json_encode($value)."\n";
    }
}

my_close($mysqli);

echo $count.' ok';

