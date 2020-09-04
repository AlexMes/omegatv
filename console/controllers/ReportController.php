<?php
namespace console\controllers;

use console\models\Company;
use console\models\Customer;
use console\models\Tariff;



class ReportController extends \yii\console\Controller
{
    //Название пунктв
    public $part_arr = array(
        '2.1' => "Количество всех клиентов",
        '2.2' => "Количество неактивных клиентов",
        '2.3' => "Список тарифов и количество активных клиентов",
        '2.4' => "Список активных клиентов и тарифы",
    );

    /**
     * Отчет по компаниям
     */
    public function actionCreate(){

        //Список всех коомпаний
        $Companys = Company::find()
            ->all();

        //Список всех тарифов
        $Tariffs = Tariff::find()
            ->all();
        //Список тарифов по компаниям
        $company_tariffs_arr = array();
        foreach ($Tariffs as $tariff){
            $company_tariffs_arr[$tariff['id_company']][] = ['id_tariff' => $tariff['id'], 'name_tariff' => $tariff['name']];
        }

        //п.2.1 Количество всех клиентов, подписанных хоть на один тариф (по компаниям)
        $CustomersAll = Customer::getCountAllByCompanys();

        //п.2.2 Количество неактивных клиентов, подписанных на тарифы (по компаниям)
        $CustomersNoActive = Customer::getCountNoActiveByCompanys();

        //п.2.3 Список тарифов и количество активных клиентов подписанных на эти тарифы (по компаниям)
        $TariffCountActiveCustomers = Tariff::getCountActiveCustomersByTariffs();

        //п.2.4 Список активных клиентов и тарифы, на которые они подписаны
        $CustomersActiveTariff = Customer::getActiveTariff();


        //Формирую массив для передачи в файлы
        $result_arr = array();
        //п.2.1
        foreach ($CustomersAll as $customer){
            $result_arr[$customer['id_company']][$this->part_arr['2.1']] = $customer['count_customer'];
        }
        //п.2.2
        foreach ($CustomersNoActive as $customer){
            $result_arr[$customer['id_company']][$this->part_arr['2.2']] = $customer['count_customer'];
        }
        //п.2.3
        //Массив по компаниям
        $tariffs_count_custmer_arr = array();
        foreach ($TariffCountActiveCustomers as $tariff){
            //$tariff - id_company, id_tariff, name_tariff, count_customer
            $tariffs_count_custmer_arr[$tariff['id_company']][$tariff['name_tariff']] = $tariff['count_customer'];
        }

        //п.2.4
        //Массив по компаниям
        $custmer_list_tariffs_arr = array();
        foreach ($CustomersActiveTariff as $customer){
            //$customer - id_company, id_customer, name_customer, name_tariff
            if(!isset($custmer_list_tariffs_arr[$customer['id_company']][$customer['name_customer']])){
                $custmer_list_tariffs_arr[$customer['id_company']][$customer['name_customer']] = $customer['name_tariff'];
            }else{
                $custmer_list_tariffs_arr[$customer['id_company']][$customer['name_customer']] .= ", ".$customer['name_tariff'];
            }

        }

        //Итоговый массив, формирую отчеты компаний
        foreach ($Companys as $company){
            //Запрос ничего не вернул значит "0"
            if(!isset($result_arr[$company['id']][$this->part_arr['2.1']])){
                $result_arr[$company['id']][$this->part_arr['2.1']] = "0";
            }
            if(!isset($result_arr[$company['id']][$this->part_arr['2.2']])){
                $result_arr[$company['id']][$this->part_arr['2.2']] = "0";
            }
            if(isset($company_tariffs_arr[$company['id']])) {
                foreach ($company_tariffs_arr[$company['id']] as $tariffs) {
                    if (!isset($tariffs_count_custmer_arr[$company['id']][$tariffs['name_tariff']])) {
                        $tariffs_count_custmer_arr[$company['id']][$tariffs['name_tariff']] = "0";
                    }
                }
            }else{
                $tariffs_count_custmer_arr[$company['id']] = array();
            }
            ksort($tariffs_count_custmer_arr[$company['id']]);
            $result_arr[$company['id']][$this->part_arr['2.3']] = $tariffs_count_custmer_arr[$company['id']];

            if(!isset($custmer_list_tariffs_arr[$company['id']])){
                $custmer_list_tariffs_arr[$company['id']] = array();
            }
            $result_arr[$company['id']][$this->part_arr['2.4']] = $custmer_list_tariffs_arr[$company['id']];


            // формирую отчеты компаний
            $this->arrayToXML('./console/report/'.$company['name'].'-'.date('Y.m.d').'.xml', $result_arr[$company['id']]);
            $this->arrayToCSV('./console/report/'.$company['name'].'-'.date('Y.m.d').'.csv', $result_arr[$company['id']]);
            $this->arrayToJSON('./console/report/'.$company['name'].'-'.date('Y.m.d').'.json', $result_arr[$company['id']]);
            $this->arrayToExcel('./console/report/'.$company['name'].'-'.date('Y.m.d').'.xls', $result_arr[$company['id']]);

        }

        //var_dump($result_arr);

        die;
    }

    /**
     * @param $file_name
     * @param $arr
     */
    public function arrayToXML($file_name, $arr){
        require_once('./console/inc/Array2XML.php');
        $converter = new \Array2XML();
        $xmlStr = $converter->convert($arr);
        file_put_contents($file_name, $xmlStr);
    }

    /**
     * @param $file_name
     * @param $arr
     */
    public function arrayToCSV($file_name, $arr){
        $csvStr = "";
        $csvStr .= $this->part_arr['2.1']."; ".$arr[$this->part_arr['2.1']]."\n";
        $csvStr .= $this->part_arr['2.2']."; ".$arr[$this->part_arr['2.2']]."\n";
        $csvStr .= $this->part_arr['2.3'].";\n";
        foreach($arr[$this->part_arr['2.3']] as $tariff => $count){
            $csvStr .= $tariff."; ".$count.";\n";
        }
        $csvStr .= $this->part_arr['2.4']."\n";
        foreach($arr[$this->part_arr['2.4']] as $customer => $list){
            $csvStr .= $customer."; ".$list."\n";
        }
        file_put_contents($file_name, iconv("UTF-8", "windows-1251", $csvStr));
    }

    /**
     * @param $file_name
     * @param $arr
     */
    public function arrayToJSON($file_name, $arr){
        file_put_contents($file_name, json_encode($arr));
    }

    /**
     * @param $file_name
     * @param $arr
     */
    public function arrayToExcel($file_name, $arr){
        require_once './console/inc/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $active_sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->createSheet();

        $active_sheet->getColumnDimension('A')->setWidth(50);
        $active_sheet->getColumnDimension('B')->setWidth(25);

        $active_sheet->setCellValue('A1', $this->part_arr['2.1']);
        $active_sheet->setCellValue('B1', $arr[$this->part_arr['2.1']]);

        $active_sheet->setCellValue('A2', $this->part_arr['2.2']);
        $active_sheet->setCellValue('B2', $arr[$this->part_arr['2.2']]);

        $active_sheet->mergeCells('A3:B3');
        $active_sheet->setCellValue('A3', $this->part_arr['2.3']);
        $i=4;
        foreach($arr[$this->part_arr['2.3']] as $tariff => $count){
            $active_sheet->setCellValue('A'.$i, $tariff);
            $active_sheet->setCellValue('B'.$i, $count);
            $i++;
        }

        $active_sheet->mergeCells('A'.$i.':B'.$i);
        $active_sheet->setCellValue('A'.$i, $this->part_arr['2.4']);
        $i++;
        foreach($arr[$this->part_arr['2.4']] as $customer => $list){
            $active_sheet->setCellValue('A'.$i, $customer);
            $active_sheet->setCellValue('B'.$i, $list);
            $i++;
        }



        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($file_name);




        //file_put_contents($file_name, json_encode($arr));
    }





}