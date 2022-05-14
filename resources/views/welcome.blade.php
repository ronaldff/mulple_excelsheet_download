<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
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

  <button class="btn-submit" id="btn-export" onclick="getHTMLSplit();">Customer Export</button>
</body>

</html>
<!-- <script src={{ asset('js/jquery-3.2.1.min.js') }}></script> -->
<script src='js/jquery-3.2.1.min.js'></script>

<script type="text/javascript">
  function getHTMLSplit() {

    $.ajax({
      url: "/export"
      , headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      , type: 'POST'
      , dataType: 'JSON'
      , success: function(response) {
        exportHTMLSplit(response);
      }
    });
  }

  function exportHTMLSplit(response) {
    var random = Math.floor(100000 + Math.random() * 900000)
    $(response).each(function(index) {
      var excelContent = response[index];

      var excelFileData = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
      excelFileData += "<head>";
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
      fileDownload.download = "customer_sheet_" + random + '_' + (index + 1) + '.xls';
      fileDownload.click();
    });

  }

</script>
