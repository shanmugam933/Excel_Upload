<!DOCTYPE html>
<html lang="en">
<head>
  <title>Excel Upload</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <form method="post" action="{{route('SalesInvoiceImport')}}" name="SalesInvoiceImport" enctype="multipart/form-data">
        @csrf
        <input type="file" name="excel_file" id="excel_file">
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
