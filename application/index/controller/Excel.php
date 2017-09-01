<?php
namespace app\index\controller;

use think\Loader;

class Excel
{
    public function index()
    {
        echo 'excel';
    }

    public function export(){

        Loader::import('PHPExcel',EXTEND_PATH.'PHPExcel/Classes');
        $excel = new \PHPExcel();

        //excel表格式
        $letter = ['A','B','C','D','E','F','F','G'];
        //表头数组
        $table_header = ['学号','姓名','性别','年龄','班级'];

        //填充表头信息
        for ($i = 0 ; $i < count($table_header);++$i) {
            $excel->getActiveSheet()->setCellValue($letter[$i].'1',$table_header[$i]);
        }

        //表格数组
        $data = [
            ['1','小王','男','20','100'],
            ['2','小陈','男','30','101'],
            ['3','老王','男','20','100'],
            ['4','小小','男','20','100'],
            ['5','小惠','女','21','100'],
        ];

        for ($i = 0;$i < count($data) ;++$i) {
            $excel->getActiveSheet()->setCellValue($letter[0].($i + 2),$data[$i][0]);
            $excel->getActiveSheet()->setCellValue($letter[1].($i + 2),$data[$i][1]);
            $excel->getActiveSheet()->setCellValue($letter[2].($i + 2),$data[$i][2]);
            $excel->getActiveSheet()->setCellValue($letter[3].($i + 2),$data[$i][3]);
            $excel->getActiveSheet()->setCellValue($letter[4].($i + 2),$data[$i][4]);
        }

        //创建Excel输入对象
        $write = new \PHPExcel_Writer_Excel5($excel);
        header("Content-Type:application/vnd.ms-execl");
        header('Content-Disposition:attachment;filename="testdata.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
        //不知怎么用
//        header("Content-Type:application/force-download");//
//        header("Content-Type:application/octet-stream");
//        header("Content-Type:application/download");;
    }
}

