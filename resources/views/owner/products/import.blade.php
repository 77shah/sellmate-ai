<x-layouts.app>

    @section('title', 'Import Products')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📥 Import Products</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.products.index') }}">Products</a></li>
                                <li class="breadcrumb-item active">Import</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5><i class="bx bx-info-circle me-2"></i> Quick Instructions</h5>
                                        <ul class="mb-0">
                                            <li><strong>Required Columns:</strong> <code>name</code>, <code>price</code>, <code>stock_qty</code></li>
                                            <li><strong>Optional Columns:</strong> <code>description</code>, <code>sku</code>, <code>category</code>, <code>status</code></li>
                                            <li><strong>Status Values:</strong> <code>active</code> or <code>inactive</code> (default: active)</li>
                                            <li>If <strong>SKU</strong> is not provided, it will be auto-generated</li>
                                            <li>If <strong>Category</strong> does not exist, it will be auto-created</li>
                                            <li>Existing products with same SKU will be <strong>updated</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="mdi mdi-alert-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="mdi mdi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('owner.products.import.store') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Upload Excel File <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   name="file" id="fileInput" accept=".xlsx,.xls,.csv" required>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Supported formats: .xlsx, .xls, .csv | Max size: 10MB</small>
                                        </div>
                                        <div id="fileNameDisplay" class="text-muted mt-1" style="display: none;">
                                            <i class="bx bx-file me-1"></i> Selected: <span id="fileName" class="fw-bold"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="mb-3 w-100">
                                            <button type="submit" class="btn btn-primary w-100" id="importBtn">
                                                <i class="bx bx-upload me-1"></i> Start Import
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>📄 Download Sample Format</h6>
                                    <button class="btn btn-outline-success btn-sm" onclick="downloadSampleCSV()">
                                        <i class="bx bx-download me-1"></i> Download Sample CSV
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="downloadSampleExcel()">
                                        <i class="bx bx-download me-1"></i> Download Sample Excel
                                    </button>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="{{ route('owner.products.export') }}" class="btn btn-info">
                                        <i class="bx bx-download me-1"></i> Export Current Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Data Preview -->
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">📊 Sample Excel Format</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>name</th>
                                            <th>description</th>
                                            <th>price</th>
                                            <th>stock_qty</th>
                                            <th>sku</th>
                                            <th>category</th>
                                            <th>status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>iPhone 15 Pro</td>
                                            <td>Latest Apple smartphone</td>
                                            <td>99999</td>
                                            <td>50</td>
                                            <td>IPH-15P-T001</td>
                                            <td>Electronics</td>
                                            <td>active</td>
                                        </tr>
                                        <tr>
                                            <td>Nike Air Max</td>
                                            <td>Comfortable running shoes</td>
                                            <td>8999</td>
                                            <td>100</td>
                                            <td>NK-AMX-T001</td>
                                            <td>Fashion</td>
                                            <td>active</td>
                                        </tr>
                                        <tr>
                                            <td>Samsung Galaxy S24</td>
                                            <td>Android smartphone</td>
                                            <td>79999</td>
                                            <td>30</td>
                                            <td>SS-GS24-T001</td>
                                            <td>Electronics</td>
                                            <td>inactive</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show file name when selected
            $('#fileInput').change(function() {
                var file = this.files[0];
                if (file) {
                    $('#fileName').text(file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)');
                    $('#fileNameDisplay').show();
                } else {
                    $('#fileNameDisplay').hide();
                }
            });

            // Disable button on submit
            $('#importForm').submit(function() {
                var file = $('#fileInput')[0].files[0];
                if (!file) {
                    alert('Please select a file to import.');
                    return false;
                }
                $('#importBtn').prop('disabled', true);
                $('#importBtn').html('<span class="spinner-border spinner-border-sm me-1"></span> Importing...');
            });
        });

        // Download sample CSV
        function downloadSampleCSV() {
            var headers = ['name', 'description', 'price', 'stock_qty', 'sku', 'category', 'status'];
            var sampleData = [
                ['iPhone 15 Pro', 'Latest Apple smartphone', '99999', '50', 'IPH-15P-T001', 'Electronics', 'active'],
                ['Nike Air Max', 'Comfortable running shoes', '8999', '100', 'NK-AMX-T001', 'Fashion', 'active'],
                ['Samsung Galaxy S24', 'Android smartphone', '79999', '30', 'SS-GS24-T001', 'Electronics', 'inactive']
            ];
            
            var csvContent = headers.join(',') + '\n';
            sampleData.forEach(function(row) {
                csvContent += row.join(',') + '\n';
            });

            var blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'sample_products_import.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        }

        // Download sample Excel (using HTML table to Excel)
        function downloadSampleExcel() {
            var headers = ['name', 'description', 'price', 'stock_qty', 'sku', 'category', 'status'];
            var sampleData = [
                ['iPhone 15 Pro', 'Latest Apple smartphone', '99999', '50', 'IPH-15P-T001', 'Electronics', 'active'],
                ['Nike Air Max', 'Comfortable running shoes', '8999', '100', 'NK-AMX-T001', 'Fashion', 'active'],
                ['Samsung Galaxy S24', 'Android smartphone', '79999', '30', 'SS-GS24-T001', 'Electronics', 'inactive']
            ];

            var table = document.createElement('table');
            var thead = document.createElement('thead');
            var headerRow = document.createElement('tr');
            headers.forEach(function(header) {
                var th = document.createElement('th');
                th.textContent = header;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            table.appendChild(thead);

            var tbody = document.createElement('tbody');
            sampleData.forEach(function(rowData) {
                var tr = document.createElement('tr');
                rowData.forEach(function(cellData) {
                    var td = document.createElement('td');
                    td.textContent = cellData;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);

            var html = '<html><head><meta charset="UTF-8"><title>Sample Products</title></head><body>' + 
                       table.outerHTML + '</body></html>';
            
            var blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'sample_products_import.xls';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        }
    </script>
    @endpush

</x-layouts.app>