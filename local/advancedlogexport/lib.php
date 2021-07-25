<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
require_once __DIR__.'/../../vendor/autoload.php'; 
    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;



class advancedexportlib_xls  {

   public static function exportExcel($data) {
    
    $headers = $data['headers'];
    
    $comment_text = $data['comment_text'];
    
    $report_title = $data['report_title'];
    
    $cell_values = $data['cell_values'];
    
    $worksheet_title = $data['worksheet_title'];
    
    $full_filename = $data['full_filename'];
     
//    $headers = ['A' => ['title' => 'Ticket ID', 'width' => 'auto'], 
//                'B' => ['title' => 'Customer', 'width' => 'auto'], 
//                'C' => ['title' => 'Skipped Datetime', 'width' => 'auto'], 
//                'D' => ['title' => 'Skipped by Agent', 'width' => 'auto'], 
//                'E' => ['title' => 'Ticket', 'width' => '100'],
//                ];
    
//    $comment_text = "Comment text";
//    $report_title = 'SKIPPED TICKETS EXPORT';
//    
//    $cell_values = self::getDataForExport();
    
//    $response = new Response();
//    $response->headers->set('Pragma', 'no-cache');
//    $response->headers->set('Expires', '0');
//    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
//    $response->headers->set('Content-Disposition', 'attachment; filename='.$full_filename);
       
//    header('Content-Type: application/vnd.ms-excel');
//    header('Content-Disposition: attachment; filename="'.$full_filename.'"');   

    $spreadsheet = new Spreadsheet();

    //Set metadata.
    $spreadsheet->getProperties()
      ->setCreator('OASIS')
      ->setLastModifiedBy('OASIS')
      ->setTitle($report_title)
      ->setLastModifiedBy('OASIS')
      ->setDescription('OASIS generated report')
      ->setSubject('OASIS generated report')
      ->setKeywords('OASIS report')
      ->setCategory('Social media');

    // Get the active sheet.
    $spreadsheet->setActiveSheetIndex(0);
    $worksheet = $spreadsheet->getActiveSheet();

    //Rename sheet
    $worksheet->setTitle($worksheet_title);

    /*
    * TITLE
    */
    //Set style Title
    $styleArrayTitle = array(
      'font' => array(
        'bold' => true,
        'color' => array('rgb' => '161617'),
        'size' => 12,
        'name' => 'Verdana'
      ));

    $worksheet->getCell('A5')->setValue($report_title);
    $worksheet->getStyle('A5')->applyFromArray($styleArrayTitle);
    
    $worksheet->getCell('A6')->setValue($comment_text);

    /*
     * HEADER
     */
    //Set Background
    foreach ($headers as $key => $value) {
        $worksheet->getStyle($key.'7')
          ->getFill()
          ->setFillType(Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('848484');
    }  

//    $worksheet->getStyle('A7:H7')
//      ->getFill()
//      ->setFillType(Fill::FILL_SOLID)
//      ->getStartColor()
//      ->setARGB('848484');

    //Set style Head
    $styleArrayHead = array(
      'font' => array(
        'bold' => true,
        'color' => array('rgb' => 'ffffff'),
      ));
    
    foreach ($headers as $key => $value) {
        $worksheet->getCell($key.'7')->setValue($value['title']);
        $worksheet->getStyle($key.'7')->applyFromArray($styleArrayHead);
    }   
    
//    $worksheet->getCell('B5')->setValue('C2');
//    $worksheet->getCell('C5')->setValue('C3');

 //   $worksheet->getStyle('A7:E7')->applyFromArray($styleArrayHead);

//    for ($i = 8; $i < 15; $i++) {
//      $worksheet->setCellValue('A' . $i, $i);
//      $worksheet->setCellValue('B' . $i, 'Test C2');
//      $worksheet->setCellValue('C' . $i, 'Test C3');
//    }
    
    $current_row = 8;
    foreach ($cell_values as $key => $value) {
        $headers_counter = 0;
        foreach ($headers as $key_head => $value_head) {
           // $worksheet->getCell($key_head.$current_row)->setValue($value[$headers_counter]);
                if (isset($value_head['format']) && $value_head['format'] == "number") {
                                    
                                try {
                                    $worksheet->setCellValueExplicit(
                                      $key_head.$current_row,
                                      $value[$headers_counter],
                                      DataType::TYPE_NUMERIC
                                    );
                                } catch(Exception $e) {
                                    $worksheet->setCellValueExplicit(
                                      $key_head.$current_row,
                                      $value[$headers_counter],
                                      DataType::TYPE_STRING
                                    );
                                }
                } else {
                                    $worksheet->setCellValueExplicit(
                                      $key_head.$current_row,
                                      $value[$headers_counter],
                                      DataType::TYPE_STRING
                                    );
                }

            if ($value_head['width'] != 'auto') {
                    $worksheet->getStyle($key_head.$current_row)->getAlignment()->setWrapText(true); 
            }

            $headers_counter++;
        }
        
        $current_row++;
        
    } 
    
    
    // This inserts the SUM() formula with some styling.
//    $worksheet->setCellValue('A10', '=SUM(A4:A9)');
//    $worksheet->getStyle('A10')
//      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
//    $worksheet->getStyle('A10')
//      ->getBorders()->getTop()->setBorderStyle(Border::BORDER_THICK);

    // This inserts the formula as text.
//    $worksheet->setCellValueExplicit(
//      'A11',
//      '=SUM(A4:A9)',
//      DataType::TYPE_STRING
//    );
    foreach ($headers as $key => $value) {
        if ($value['width'] == 'auto') {
            $worksheet->getColumnDimension($key)
                ->setAutoSize(true);
        } else {
            $worksheet->getColumnDimension($key)->setAutoSize(false);
            $worksheet->getColumnDimension($key)->setWidth($value['width']);
        }
        
    }  

    
    
//    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
//    $drawing->setName('Logo');
//    $drawing->setDescription('Logo');
//    $drawing->setPath('public://logo-white.png'); // put your path and image here
//    $drawing->setCoordinates('A1');
//    $drawing->setWidthAndHeight(148,74);
//    $drawing->setResizeProportional(true);
//    $drawing->setWorksheet($worksheet);
       
       // Get the writer and export in memory.
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       
   header('Content-Type: application/vnd.ms-excel');    
   header('Content-Disposition: attachment; filename="'.$full_filename.'"');         

    
    
   // ob_start();
    $writer->save('php://output');
    //$content = ob_get_clean();

    // Memory cleanup.
    //$spreadsheet->disconnectWorksheets();
    //unset($spreadsheet);
       
   //  $response->headers->set('Content-Type', 'application/vnd.ms-excel');  
    
    //   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  //  header('Content-Length: ' . filesize($filename));
     
   // readfile($filename); // send file
    //unlink($filename); // delete file
    exit;
   


   // $response->setContent($content);
//    return $response;
    

    
}
}
