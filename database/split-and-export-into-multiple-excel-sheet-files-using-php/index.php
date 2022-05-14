<?php
require_once ("DBController.php");

$db_handle = new DBController();
$query = "select * from tbl_employee";
$result = $db_handle->runBaseQuery($query);

?>

<!DOCTYPE html>

<head>
<title>Split and Export into Multiple Excel Sheet Files using PHP</title>
<script type="text/javascript"
    src="jquery-3.2.1.min.js"></script>

<style>
body {
    font-family: Arial;
    width: 550px;
}

.table-container {
    border: #e0dfdf 1px solid;
    border-radius: 2px;
    width: 100%;
}

.table-container th {
    background: #F0F0F0;
    padding: 10px;
    text-align: left;
}

.table-container td {
    padding: 10px;
    border-bottom: #e8e8e8 1px solid;
}

.btn-submit {
    padding: 10px 30px;
    background: #333;
    border: #1d1d1d 1px solid;
    color: #f0f0f0;
    font-size: 0.9em;
    border-radius: 2px;
    cursor: pointer;
    margin-top: 10px;
}
</style>
</head>
<body>
    <h3>Split and Export into Multiple Excel Sheet Files using PHP</h3>
    <?php
    if (! empty($result)) {
        ?>
    <table class="table-container" cellspacing="0">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Salary</th>
                <th>Age</th>

            </tr>
        </thead>
                    <?php
        
        foreach ($result as $k => $v) {
            ?>
                        <tr>
            <td width="10%"> <?php echo $result[$k]['id']; ?></td>
            <td width="40%"> <?php echo $result[$k]['employee_name']; ?></td>
            <td width="30%"> <?php echo $result[$k]['employee_salary']; ?></td>
            <td width="20%"> <?php echo $result[$k]['employee_age']; ?></td>
        </tr>
                        <?php
        }
        
        ?>
                </table>
    <?php
    }
    ?>


    <button class="btn-submit" id="btn-export" onclick="getHTMLSplit();">Export to Excel File</button>

</body>
</html>

<script type="text/javascript">
function getHTMLSplit() {
	$.ajax({
    		url: 'splitData.php',
    		type: 'POST',
    		dataType: 'JSON',
        data: {record_count:<?php echo count($result); ?>},
    		success:function(response){
           exportHTMLSplit(response); 
    		}
    	});
}

function exportHTMLSplit(response) {
	var random = Math.floor(100000 + Math.random() * 900000)
    $(response).each(function (index) {
      
        var excelContent = response[index];

        var excelFileData = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
        excelFileData += "<head>";
        excelFileData += "<!--[if gte mso 9]>";
        excelFileData += "<xml>";
        excelFileData += "<x:ExcelWorkbook>";
        excelFileData += "<x:ExcelWorksheets>";
        excelFileData += "<x:ExcelWorksheet>";
        excelFileData += "<x:Name>";
        excelFileData += "{worksheet}";
        excelFileData += "</x:Name>";
        excelFileData += "<x:WorksheetOptions>";
        excelFileData += "<x:DisplayGridlines/>";
        excelFileData += "</x:WorksheetOptions>";
        excelFileData += "</x:ExcelWorksheet>";
        excelFileData += "</x:ExcelWorksheets>";
        excelFileData += "</x:ExcelWorkbook>";
        excelFileData += "</xml>";
        excelFileData += "<![endif]-->";
        excelFileData += "</head>";
        excelFileData += "<body>";
        excelFileData += excelContent;
        excelFileData += "</body>";
        excelFileData += "</html>";

        var sourceHTML = excelFileData + response[index];

        var source = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(sourceHTML);
        var fileDownload = document.createElement("a");
        document.body.appendChild(fileDownload);
        fileDownload.href = source;
        fileDownload.download = "sheet_" + random + '_'+(index+1)+'.xls';
        fileDownload.click();
        document.body.removeChild(fileDownload);
    }) 
}
</script>