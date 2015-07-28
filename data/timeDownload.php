<?php
session_start();
/** Error reporting */
require "inc/connection.php";

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

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
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$currentRow, date('D', $row['date']));
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$currentRow, $row['startTime'] . ' - ' . $row['endTIme']);
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$currentRow, $row['hoursWorked']);
}

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

echo 'Test';

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="file.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>