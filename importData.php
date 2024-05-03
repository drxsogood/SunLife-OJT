<?php
// Include PhpSpreadsheet library autoloader
require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Load the database configuration file
include_once 'dbConfig.php';

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    // Validate whether selected file is an Excel file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            $reader = new Xlsx();
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet(); 
            $worksheet_arr = $worksheet->toArray();

            // Get the header row
            $header = array_shift($worksheet_arr);

            // Mapping column names to their respective indexes using the header row
            $column_map = array_flip($header);

            foreach($worksheet_arr as $row){
                $row_data = array();
                foreach ($column_map as $column_name => $column_index) {
                    $row_data[$column_name] = isset($row[$column_index]) ? $row[$column_index] : '';
                }

                $insured_name = isset($row_data['insured_name']) ? $row_data['insured_name'] : '';
                $policy_number = isset($row_data['policy_number']) ? $row_data['policy_number'] : '';
                $issue_date = isset($row_data['issue_date']) ? $row_data['issue_date'] : '';
                $status = isset($row_data['status']) ? $row_data['status'] : '';
                $st = isset($row_data['st']) ? $row_data['st'] : '';
                $md = isset($row_data['md']) ? $row_data['md'] : '';
                $current_premium = isset($row_data['current_premium']) ? $row_data['current_premium'] : '';
                $term_premium = isset($row_data['term_premium']) ? $row_data['term_premium'] : '';
                $net_premium = isset($row_data['net_premium']) ? $row_data['net_premium'] : '';
                $regular_premium_paid = isset($row_data['regular_premium_paid']) ? $row_data['regular_premium_paid'] : '';
                $original_annual_premium = isset($row_data['original_annual_premium']) ? $row_data['original_annual_premium'] : '';
                $x2_original_annual_premium1month = isset($row_data['x2_original_annual_premium1month']) ? $row_data['x2_original_annual_premium1month'] : '';
                $share = isset($row_data['share']) ? $row_data['share'] : '';
                $term_date = isset($row_data['term_date']) ? $row_data['term_date'] : '';
                $persistency_factor = isset($row_data['persistency_factor']) ? $row_data['persistency_factor'] : '';
                $projected_persistency_reinstated = isset($row_data['projected_persistency_reinstated']) ? $row_data['projected_persistency_reinstated'] : '';

                // Check whether member already exists in the database with the same policy_number
                $prevQuery = "SELECT id FROM members WHERE policy_number = '".$policy_number."'";
                $prevResult = $db->query($prevQuery);
                            
                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $db->query("UPDATE members SET insured_name = '".$insured_name."', issue_date = '".$issue_date."', status = '".$status."', st = '".$st."', md = '".$md."', current_premium = '".$current_premium."', term_premium = '".$term_premium."', net_premium = '".$net_premium."', regular_premium_paid = '".$regular_premium_paid."', original_annual_premium = '".$original_annual_premium."', x2_original_annual_premium1month = '".$x2_original_annual_premium1month."', share = '".$share."', term_date = '".$term_date."', persistency_factor = '".$persistency_factor."', projected_persistency_reinstated = '".$projected_persistency_reinstated."', modified = NOW() WHERE policy_number = '".$policy_number."'");
                }else{
                    // Insert member data in the database
                    $db->query("INSERT INTO members (insured_name, policy_number, issue_date, status, st, md, current_premium, term_premium, net_premium, regular_premium_paid, original_annual_premium, x2_original_annual_premium1month, share, term_date, persistency_factor, projected_persistency_reinstated, created, modified) VALUES ('".$insured_name."', '".$policy_number."', '".$issue_date."', '".$status."', '".$st."', '".$md."', '".$current_premium."', '".$term_premium."', '".$net_premium."', '".$regular_premium_paid."', '".$original_annual_premium."', '".$x2_original_annual_premium1month."', '".$share."', '".$term_date."', '".$persistency_factor."', '".$projected_persistency_reinstated."', NOW(), NOW())");
                }
            }
                        
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: index.php".$qstring);
?>
