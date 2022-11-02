<?php
require_once 'Classes/PHPExcel.php';
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);

$objPHPExcel = $objReader->load("active.xlsx");
$objWorksheet = $objPHPExcel->getActiveSheet();
//print_r($objWorksheet);

echo $highestRow = $objWorksheet->getHighestRow(); 
echo $highestColumn = $objWorksheet->getHighestColumn(); 

echo $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 

/*echo '<table border="1">' . "\n";
for ($row = 1; $row <= $highestRow; ++$row) {
  echo '<tr>' . "\n";

  for ($col = 0; $col <= $highestColumnIndex; ++$col) {
    echo '<td>' . $objWorksheet->getCellByColumnAndRow($col, $row)->getValue() . '</td>' . "\n";
  }

  echo '</tr>' . "\n";
}
echo '</table>' . "\n";*/
?>