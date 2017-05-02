<?php

require_once 'excel_lib/PHPExcel.php';

/*
 * 从excel中导出表头
 * @param string $fileName excel的文件名
 * @param string $row 行号
 * @return array 表头内容
 */
function getExcelHeader($fileName, $row = 1)
{
    $type = (substr($fileName, -4) == 'xlsx') ? 'Excel2007' : 'Excel5';
    $excel = \PHPExcel_IOFactory::createReader($type);
    $excel = $excel->setReadDataOnly(true);

    try {
        // 载入文件,
        $objPHPExcel = $excel->load($fileName);
        $sheet = $objPHPExcel->getSheet(0);
    } catch (Exception $e) {
        return false;
    }

    $allColumn = $sheet->getHighestColumn();
    $allColumn++;
    $result = array();
    for ($column = 'A'; $column != $allColumn; $column++) {
        $result[] = trim($sheet->getCell($column . $row)->getValue());
    }
    return $result;
}

/*
 * 从excel中导出数据(结果中会增加一列row，代表行号)
 * @param string $fileName excel的文件名
 * @param array $header 表头，关联数组
 * @param int $start 开始行号
 * @param int $limit 行数（0为无限制）
 * @return array 内容的关联数组
 */
function getExcelContent($fileName, $header, $start = 2, $limit = 0)
{
    $type = (substr($fileName, -4) == 'xlsx') ? 'Excel2007' : 'Excel5';
    $excel = \PHPExcel_IOFactory::createReader($type);
    $excel = $excel->setReadDataOnly(true);

    try {
        // 载入文件,
        $objPHPExcel = $excel->load($fileName);
        $sheet = $objPHPExcel->getSheet(0);
    } catch (Exception $e) {
        return false;
    }

    $allRow = $sheet->getHighestRow();
    $end = $allRow;
    if ($limit > 0 && $start + $limit < $allRow) {
        $end = $start + $limit;
    }

    $result = array();
    for($row = $start; $row <= $end; $row++) {
        $line = array();
        foreach($header as $key=>$value) {
            $tkey = getHeaderName($key);
            $line[$value] = trim($sheet->getCell($tkey . $row)->getValue());
        }
        $result[] = $line;
    }

    return $result;
}

function getHeaderName($index)
{
    if($index < 26) {
        $res = chr(ord('A') + $index);
    } else {
        $oindex = $index / 26;
        $tindex = $index % 26;
        $res = chr(ord('A') + $oindex - 1) . chr(ord('A') + $tindex);
    }
    return $res;
}