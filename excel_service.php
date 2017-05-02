<?php

class excel_service
{
    private $_excel;
    
    public function __construct($file_name)
    {
        $type = (substr($file_name, -4) == 'xlsx') ? 'Excel2007' : 'Excel5';
        $excel_file = \PHPExcel_IOFactory::createReader($type);
        $excel_file = $excel_file->setReadDataOnly(true);

        try {
            // 载入文件,
            $this->_excel = $excel_file->load($file_name);
        } catch (Exception $e) {
            die("open file <{$file_name}> fail");
        }
    }

    /*
     * 从excel中导出表头
     * @param string $row 行号
     * @return array 表头内容
     */
    public function get_excel_header($row = 1)
    {
        $sheet = $this->_excel->getSheet(0);

        $allColumn = $sheet->getHighestColumn();
        $allColumn++;
        $result = array();
        for ($column = 'A'; $column != $allColumn; $column++) {
            $result[] = trim($sheet->getCell($column . $row)->getValue());
        }
        return $result;
    }

    /*
     * 从excel中导出数据
     * @param array $header 表头，关联数组
     * @param int $start 开始行号
     * @param int $limit 行数（0为无限制）
     * @return array 内容的关联数组
     */
    function get_excel_content($header, $start = 2, $limit = 0)
    {
        $sheet = $this->_excel->getSheet(0);

        $allRow = $sheet->getHighestRow();
        $end = $allRow;
        if ($limit > 0 && $start + $limit < $allRow) {
            $end = $start + $limit;
        }

        $result = array();
        for ($row = $start; $row <= $end; $row++) {
            $line = array();
            foreach ($header as $key => $value) {
                $tkey = $this->_get_header_name($key);
                $line[$value] = trim($sheet->getCell($tkey . $row)->getValue());
            }
            $result[] = $line;
        }

        return $result;
    }

    private function _get_header_name($index)
    {
        if ($index < 26) {
            $res = chr(ord('A') + $index);
        } else {
            $oindex = $index / 26;
            $tindex = $index % 26;
            $res = chr(ord('A') + $oindex - 1) . chr(ord('A') + $tindex);
        }
        return $res;
    }
}