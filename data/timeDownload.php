<?php
session_start();
/** Error reporting */
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

// Set properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


// Add some data
$objPHPExcel->setActiveSheetIndex(0);

$currentRow = 1;

while ($row= $result->fetch_assoc()){
	$currentRow++;
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$currentRow, date('D', strtotime($row['date'])))
            ->setCellValue('C'.$currentRow, $row['startTime'] . ' - ' . $row['endTIme'])
            ->setCellValue('D'.$currentRow, $row['hoursWorked']);
}

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
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