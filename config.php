<?php

return array(
    //数据库host
    'db_host'      => 'localhost',

    //数据库用户名
    'db_user'      => 'root',

    //数据库密码
    'db_pwd'       => '',

    //数据库名
    'db_name'      => 'test',

    //表名
    'table_name'   => 'test',

    //excel名称
    'excel_name'   => 'test_file/test.xlsx',

    //以下项目是选填的，有默认值

    //插入之前，是否先删表（默认是false）
    'drop_table'   => true,

    //是否显示详细信息（默认是false）
    'show_detail'  => false,

    //默认的表字段varchar长度（默认是1024）
    'field_length' => 1024,

    //每次表中插入per_insert_count条数据，值为1时会返回自增ID（默认是1）
    'per_insert_count' => 1,
);