<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Payment Receipt</title>
    <link rel="stylesheet" href="{{ asset('argon/css/argon.css') }}">
    <style>
        @page {
            size: A5 portrait;
            margin: 10mm;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none !important; border: none !important; }
        }
        body {
            background: #f7f7f7;
        }
        .receipt-wrapper {
            max-width: 148mm;
            margin: 10px auto;
        }
        .logo {
            height: 40px;
        }
        .border-top-dashed {
            border-top: 1px dashed #ccc;
        }
    </style>
</head>
<body>
<div class="receipt-wrapper">
    <div class="card receipt-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <img src="{{ asset('front-assets/img/logo.png') }}" alt="Logo" class="logo">
                    <h4 class="mb-0 mt-2">Chroma by BPC</h4>
                    <small class="text-muted">Teacher Payment Receipt</small>
                </div>
                <div class="text-right">
                    <h5 class="mb-0">Receipt #{{ $payment->paymentID }}</h5>
                    <small class="text-muted">Date: {{ $payment->created_at->format('Y-m-d') }}</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-8">
                    <h6 class="text-uppercase text-muted mb-1">Teacher</h6>
                    <p class="mb-0 font-weight-bold">{{ $payment->teacher->tFName }} {{ $payment->teacher->tLName }}</p>
                    <small class="text-muted">ID: {{ $payment->teacher->T_ID }}</small>
                </div>
                <div class="col-4 text-right">
                    @php
                        $months = [
                            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                        ];
                    @endphp
                    <h6 class="text-uppercase text-muted mb-1">Month</h6>
                    <p class="mb-0">{{ $months[$payment->month] ?? 'Unknown' }}</p>
                </div>
            </div>

            <hr class="border-top-dashed">

            <div class="row mb-3">
                <div class="col-8">
                    <p class="mb-1">Description</p>
                    <small class="text-muted">Monthly class payment</small>
                </div>
                <div class="col-4 text-right">
                    <p class="mb-1">Amount</p>
                    <h4 class="mb-0">Rs. {{ number_format($payment->amount, 2) }}</h4>
                </div>
            </div>

            <p class="text-muted mb-4" style="font-size: 0.8rem;">
                This is a system-generated receipt for your records.
            </p>

            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Processed by Chroma by BPC.</small>
                <button class="btn btn-sm btn-primary no-print" onclick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
