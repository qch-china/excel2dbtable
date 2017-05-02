<?php

function my_open($db_host, $db_user, $db_pwd, $db_name)
{
    $mysqli = new mysqli($db_host, $db_user, $db_pwd, $db_name);
    if(mysqli_connect_error()){
        die(mysqli_connect_error());
    }
    $mysqli->set_charset("utf8");

    return $mysqli;
}

function my_read($mysqli, $sql)
{
    $res = $mysqli->query($sql);
    if ($res === false) {
        die($mysqli->error);
    }
    $data = $res->fetch_all(MYSQLI_ASSOC);
    return $data;
}

function my_write($mysqli, $sql)
{
    $res = $mysqli->query($sql);
    if ($res === false) {
        die($mysqli->error);
    }
    return $mysqli->affected_rows;
}

function my_close($mysqli)
{
    $mysqli->close();
}

function my_insert($mysqli, $table, $data)
{
    $field_str = '';
    $value_str = '';
    foreach ($data as $key => $value) {
        $field_str .= "`{$key}`, ";
        $value = $mysqli->real_escape_string($value);
        $value_str .= "'{$value}', ";
    }
    $field_str = substr($field_str, 0, -2);
    $value_str = substr($value_str, 0, -2);
    $sql = "INSERT INTO `{$table}`({$field_str}) VALUES ({$value_str});";

    $res = $mysqli->query($sql);
    if ($res === false) {
        die($mysqli->error);
    }
    return $mysqli->insert_id;
}

function my_create($mysqli, $table, $fields, $field_len)
{
    $sql = "CREATE TABLE if not exists `{$table}` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, ";
    foreach ($fields as $field) {
        $sql .= "`{$field}` varchar({$field_len}) DEFAULT NULL, ";
    }
    $sql .= "PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    my_write($mysqli, $sql);

    return true;
}

