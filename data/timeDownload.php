<?php
session_start();
/** Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
require "inc/connection.php";

/** Include PHPExcel */
require_once 'PHPExcel.php';

$strWhereDate = '';

if (isset($_GET['startDate']) && !empty($_GET['startDate'])){
	$strWhereDate .= ' AND date >= "' . $_GET['startDate'] . '"'; 
}

if (isset($_GET['endDate']) && !empty($_GET['endDate'])){
	$strWhereDate .= ' AND date <= "' . $_GET['endDate'] . '"';
}

$sqlStr = "SELECT * FROM timeDetail WHERE userID = " . $_SESSION["userID"] . $strWhereDate . " ORDER BY date";
	
$result = $conn->query($sqlStr);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

/* Set properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
*/

$styleArray = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 10,
        'name'  => 'Arial'
    ));

// Add some data
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

$currentRow = 1;
$currentWeek = 0;

while ($row= $result->fetch_assoc()){
	$currentRow++;
	
	if (date('W', strtotime($row['date'])) !== $currentWeek){
		$currentWeek = date('W', strtotime($row['date']));
		$currentRow++;
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$currentRow, date('D', strtotime($row['date'])))
            ->setCellValue('C'.$currentRow, $row['startTime'] . ' - ' . $row['endTIme'])
            ->setCellValue('D'.$currentRow, $row['hoursWorked']);
            
    $objPHPExcel->getActiveSheet()->getStyle('A'.$currentRow)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$currentRow)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$currentRow)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$currentRow)->applyFromArray($styleArray);
}

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.($currentRow+1), 'Total')
            ->setCellValue('D'.($currentRow+1), '=SUM(D1:D'.$currentRow.')');
            
        $objPHPExcel->getActiveSheet()->getStyle('C'.($currentRow+1))->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('D'.($currentRow+1))->applyFromArray($styleArray);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Book1');

		
// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

ob_end_clean();
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$objWriter->save('php://output');
?>