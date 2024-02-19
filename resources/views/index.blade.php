<!DOCTYPE html>
<html lang="en">
<head>
    <title>Excel Upload</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .container {
            max-width: 600px;
            margin: auto;
        }

        #progress-container {
            display: none;
        }

        #progress-bar {
            width: 0;
            height: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <form method="post" action="{{ route('SalesInvoiceImport') }}" name="SalesInvoiceImport" enctype="multipart/form-data" id="upload-form">
        @csrf
        <div class="mb-3">
            <label for="excel_file" class="form-label">Choose Excel File:</label>
            <input type="file" name="excel_file" id="excel_file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <div id="progress-container" class="mt-3">
        <div class="progress">
            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<div class="card mx-4 mt-5">
    <table id="sales-table" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Region</th>
            <th>Item Type</th>
            <th>Order Date</th>
            <th>Order ID</th>
            <th>Units Sold</th>
            <th>Unit Price</th>
            <th>Total Cost</th>
            <th>Total Profit</th>
        </tr>
        </thead>
    </table>
</div>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.jqueryui.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.jqueryui.min.css">


<script>

    $(function () {
        @if(Session::has('success'))
            new PNotify({
            title: 'Success',
            delay: 1000,
            text:  "{{Session::get('success')}}",
            type: 'success'
        });

        @endif

        @if(Session::has('warning'))
            new PNotify({
            delay: 1000,
            text:  "{{Session::get('warning')}}",
            type: 'warning'
        });

        @endif

        @if ($errors->any())
        var err = "";
        @foreach ($errors->all() as $error)
            new PNotify({
            title: 'Error',
            text: "{{$error}}",
            delay: 800,
            type: 'error'
            });
            @endforeach
        @endif
    });

    function refreshDataTable() {
        $('#sales-table').DataTable().ajax.reload();
    }

    $(document).ready(function () {
        // Display progress bar on form submission
        $('#upload-form').submit(function () {
            $('#progress-container').show();
        });

        // Update progress bar during file upload
        $(document).ajaxSend(function (event, jqXHR, settings) {
            if (settings.type === 'POST' && settings.url === "{{ route('SalesInvoiceImport') }}") {
                jqXHR.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        var percentage = (e.loaded / e.total) * 100;
                        $('#progress-bar').width(percentage + '%').attr('aria-valuenow', percentage);
                    }
                }, false);
            }
        });

        $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("sales.data") }}',
            columns: [
                {data: 'region', name: 'region'},
                {data: 'item_type', name: 'item_type'},
                {data: 'formatted_order_date', name: 'order_date'},
                {data: 'order_id', name: 'order_id'},
                {data: 'units_sold', name: 'units_sold'},
                {data: 'unit_price', name: 'unit_price'},
                {data: 'total_cost', name: 'total_cost'},
                {data: 'total_profit', name: 'total_profit'},
            ],
        });
    });
</script>

</body>
</html>
