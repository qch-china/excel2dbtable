<?php

class db_service
{
    private $_mysqli;

    /**
     * db_service constructor.
     * @param string $db_host 主机地址
     * @param string $db_user 用户名
     * @param string $db_pwd 密码
     * @param string $db_name 数据库名
     */
    public function __construct($db_host, $db_user, $db_pwd, $db_name)
    {
        $this->_mysqli = new mysqli($db_host, $db_user, $db_pwd, $db_name);
        if (mysqli_connect_error()) {
            die(mysqli_connect_error());
        }
        $this->_mysqli->set_charset("utf8");
    }

    /**
     * db_service constructor
     */
    public function __destruct()
    {
        $this->_mysqli->close();
    }

    /**
     * 执行SQL语句（读取），并取出结果集
     * @param $sql SQL语句
     * @return mixed 结果集
     */
    public function read_data($sql)
    {
        $res = $this->_mysqli->query($sql);
        if ($res === false) {
            die($this->_mysqli->error);
        }
        $data = $res->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    /**
     * 执行SQL语句（写入），并取出结果集
     * @param $sql SQL语句
     * @return int 影响行数
     */
    public function write_data($sql)
    {
        $res = $this->_mysqli->query($sql);
        if ($res === false) {
            die($this->_mysqli->error);
        }
        return $this->_mysqli->affected_rows;
    }

    /**
     * 住表中插入一条记录
     * @param $table_name 表名
     * @param $data 数据
     * @return mixed 自增ID
     */
    public function insert_record($table_name, $data)
    {
        if (empty($data)) {
            return -1;
        }

        $field_str = '';
        $value_str = '';
        foreach ($data as $key => $value) {
            $field_str .= "`{$key}`, ";
            $value = $this->_mysqli->real_escape_string($value);
            $value_str .= "'{$value}', ";
        }
        $field_str = substr($field_str, 0, -2);
        $value_str = substr($value_str, 0, -2);
        $sql = "INSERT INTO `{$table_name}`({$field_str}) VALUES ({$value_str});";

        $res = $this->_mysqli->query($sql);
        if ($res === false) {
            die($this->_mysqli->error);
        }
        return $this->_mysqli->insert_id;
    }

    public function insert_multi_record($table_name, $data)
    {
        if (empty($data)) {
            return -1;
        }

        $field_str = '';
        $value_str = '';

        $field_list = array();
        foreach ($data[0] as $key => $value) {
            $field_str .= "`{$key}`, ";
            $field_list[] = $key;
        }
        $field_str = substr($field_str, 0, -2);

        foreach ($data as $item) {
            $tsql = '';
            foreach ($item as $key => $value) {
                $value = $this->_mysqli->real_escape_string($value);
                $tsql .= "'{$value}', ";
            }
            $tsql = substr($tsql, 0, -2);
            $value_str .= "({$tsql}), ";
        }

        $value_str = substr($value_str, 0, -2);
        $sql = "INSERT INTO `{$table_name}`({$field_str}) VALUES {$value_str};";

        return $this->write_data($sql);

    }

    /**
     * 创建数据库表
     * @param string $table_name 表名
     * @param array $fields 字段名数组
     * @param int $field_len 字段的长度
     */
    public function create_table($table_name, $fields, $field_len)
    {
        $sql = "CREATE TABLE if not exists `{$table_name}` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, ";
        foreach ($fields as $field) {
            $sql .= "`{$field}` varchar({$field_len}) DEFAULT NULL, ";
        }
        $sql .= "PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->write_data($sql);
    }
}

