<?php
require_once ("DBController.php");
$db_handle = new DBController();

define("RECORD_LIMIT_PER_FILE", 5);
$start = 0;

$rowcount = $_POST["record_count"];
$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);

for ($i = $start; $i < $lastPageNo; $i ++) {
    $query = " SELECT * FROM tbl_employee limit " . $start . " , " . RECORD_LIMIT_PER_FILE;
    $result = $db_handle->runBaseQuery($query);
    
    $splitHTML[$i] = '<table class="table table-bordered">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Salary</th>
                <th>Age</th>

            </tr>
        </thead>';
    
    foreach ($result as $k => $v) {
        $splitHTML[$i] .= '<tr>
            <td width="10%">' . $result[$k]['id'] . '</td>
            <td width="40%">' . $result[$k]['employee_name'] . '</td>
            <td width="30%">' . $result[$k]['employee_salary'] . '</td>
            <td width="20%">' . $result[$k]['employee_age'] . '</td>
        </tr>';
    }
    $splitHTML[$i] .= '</table>';
    
    $start = $start + RECORD_LIMIT_PER_FILE;
}
print json_encode($splitHTML);
?>

