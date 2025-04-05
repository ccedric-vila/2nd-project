@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h1 class="h2">Product Import</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Import</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
            <a href="{{ asset('storage/templates/product_import_template.xlsx') }}" class="btn btn-outline-primary ml-2">
                <i class="fas fa-file-download"></i> Download Template
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-file-import mr-2"></i>Import Products</h3>
        </div>

        <div class="card-body">
            <!-- Processing Alert (shown during submission) -->
            <div class="alert alert-info alert-dismissible fade show alert-processing" style="display: none;">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Your import is being processed. This may take a few moments...
            </div>

            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x mr-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Import Summary</h5>
                        <p>{{ session('success') }}</p>
                        @if(session('stats'))
                        <div class="small">
                            <span class="badge bg-primary">Processed: {{ session('stats.processed') }}</span>
                            <span class="badge bg-success">Imported: {{ session('stats.imported') }}</span>
                            <span class="badge bg-warning text-dark">Skipped: {{ session('stats.skipped') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @endif

            {{-- Skipped Rows --}}
            @if(session('skipped_rows_details'))
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Skipped Rows Details</h5>
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-bordered">
                        <thead class="bg-warning">
                            <tr>
                                <th>Row #</th>
                                <th>Product</th>
                                <th>Issue</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('skipped_rows_details') as $row)
                            <tr>
                                <td>{{ $row['row'] }}</td>
                                <td>{{ $row['product_name'] }}</td>
                                <td>{{ $row['errors'] }}</td>
                                <td>{{ $row['category'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Validation Errors --}}
            @if(session('import_errors'))
            <div class="alert alert-danger">
                <h5><i class="fas fa-times-circle"></i> Validation Errors</h5>
                <ul class="mb-0">
                    @foreach(session('import_errors') as $row => $errors)
                    <li>
                        <strong>Row {{ $row }}:</strong>
                        <ul>
                            @foreach($errors as $error)
                            <li>{{ $error['field'] }} ({{ $error['value'] }}): {{ implode(', ', $error['errors']) }}</li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Import Errors -->
            @if (session('import_errors'))
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fa-2x mr-3 text-danger"></i>
                        <div>
                            <h5 class="alert-heading mb-2">Import Errors</h5>
                            <ul class="mb-0 pl-3">
                                @foreach (session('import_errors') as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <!-- Import Form -->
            <form id="importForm" action="{{ route('admin.products.import.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="supplier_id"><i class="fas fa-truck mr-2"></i>Supplier</label>
                        <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}" 
                                        @selected(old('supplier_id') == $supplier->supplier_id)>
                                    {{ $supplier->brand_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">All imported products will be assigned to this supplier</small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="file"><i class="fas fa-file-excel mr-2"></i>Excel File</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="file">Choose file...</label>
                        </div>
                        <small class="form-text text-muted">
                            Max file size: 5MB. Supported formats: .xlsx, .xls, .csv
                        </small>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-file-import mr-2"></i> Import Products
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </button>
                </div>
            </form>

            <!-- Instructions Section -->
            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-info-circle mr-2"></i>Import Instructions</h4>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#instructionsCollapse">
                        Toggle Instructions
                    </button>
                </div>
                
                <div class="collapse show" id="instructionsCollapse">
                    <div class="card card-body bg-light">
                        <h5 class="text-primary">File Format Requirements:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Required</th>
                                        <th>Format</th>
                                        <th>Valid Values</th>
                                        <th>Example</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>product_name</td>
                                        <td><span class="badge badge-success">Yes</span></td>
                                        <td>Text</td>
                                        <td>Max 255 chars</td>
                                        <td>Classic Cotton T-Shirt</td>
                                    </tr>
                                    <tr>
                                        <td>size</td>
                                        <td><span class="badge badge-secondary">No</span></td>
                                        <td>Text</td>
                                        <td>XS, S, M, L, XL, XXL</td>
                                        <td>M</td>
                                    </tr>
                                    <tr>
                                        <td>category</td>
                                        <td><span class="badge badge-success">Yes</span></td>
                                        <td>Text</td>
                                        <td>Mens, Womens, Kids</td>
                                        <td>Mens</td>
                                    </tr>
                                    <tr>
                                        <td>types</td>
                                        <td><span class="badge badge-secondary">No</span></td>
                                        <td>Text</td>
                                        <td>T-shirt, Polo Shirt, Sweater, etc.</td>
                                        <td>T-shirt</td>
                                    </tr>
                                    <tr>
                                        <td>description</td>
                                        <td><span class="badge badge-secondary">No</span></td>
                                        <td>Text</td>
                                        <td>Unlimited text</td>
                                        <td>100% premium cotton</td>
                                    </tr>
                                    <tr>
                                        <td>cost_price</td>
                                        <td><span class="badge badge-success">Yes</span></td>
                                        <td>Number</td>
                                        <td>Positive decimal</td>
                                        <td>25.99</td>
                                    </tr>
                                    <tr>
                                        <td>sell_price</td>
                                        <td><span class="badge badge-success">Yes</span></td>
                                        <td>Number</td>
                                        <td>≥ cost_price</td>
                                        <td>49.99</td>
                                    </tr>
                                    <tr>
                                        <td>stock</td>
                                        <td><span class="badge badge-secondary">No</span></td>
                                        <td>Integer</td>
                                        <td>≥ 0</td>
                                        <td>100</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>Important Notes:</h5>
                            <ul class="mb-0">
                                <li>The first row should contain column headers exactly as shown above</li>
                                <li>Empty cells will use default values where applicable</li>
                                <li>Duplicate product names will be treated as separate products</li>
                                <li>For best results, use the provided template file</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for supplier dropdown
        $('.select2').select2({
            placeholder: 'Select a supplier',
            theme: 'bootstrap4'
        });

        // Update file input label
        $('#file').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose file...');
        });

        // Form submission handling
        $('#importForm').on('submit', function() {
            // Show processing alert
            $('.alert-processing').fadeIn();
            
            // Disable submit button
            $('#submitBtn')
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin mr-2"></i> Importing...');
        });

        // Auto-expand error sections if they exist
        @if (session('import_errors') || $errors->any())
            $(window).on('load', function() {
                $('#instructionsCollapse').collapse('show');
            });
        @endif
    });
</script>
@endsection

@section('styles')
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }
    .card-header {
        border-bottom: none;
    }
    .table th {
        white-space: nowrap;
    }
    #instructionsCollapse {
        transition: all 0.3s ease;
    }
    .alert .fa-2x {
        margin-top: -0.25rem;
        margin-bottom: -0.25rem;
    }
    pre {
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 0;
        white-space: pre-wrap;
    }
    .alert-processing {
        border-left: 4px solid #17a2b8;
    }
</style>
@endsection