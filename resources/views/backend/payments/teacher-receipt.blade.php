<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Payment Receipt</title>
    @php $isPdf = request()->has('download'); @endphp
    @if($isPdf)
        <style>
            {!! file_get_contents(public_path('argon/css/argon.css')) !!}
        </style>
    @else
        <link rel="stylesheet" href="{{ asset('argon/css/argon.css') }}">
    @endif
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
        @if($isPdf)
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
        }
        .pdf-header {
            margin-bottom: 12px;
        }
        .pdf-section {
            margin-top: 8px;
        }
        .pdf-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #8898aa;
            letter-spacing: .05em;
        }
        .pdf-value {
            font-size: 12px;
            font-weight: 600;
            color: #32325d;
        }
        .pdf-muted {
            font-size: 10px;
            color: #8898aa;
        }
        .pdf-amount {
            font-size: 13px;
            font-weight: 700;
            color: #32325d;
        }
        .pdf-right {
            text-align: right;
        }
        @endif
    </style>
</head>
<body>
<div class="receipt-wrapper">
    @if($isPdf)
        <table class="pdf-table pdf-header">
            <tr>
                <td>
                    <img src="{{ public_path('front-assets/img/logo.png') }}" alt="Logo" class="logo">
                </td>
                <td class="pdf-right">
                    <div class="pdf-label">Receipt No :</div>
                    <div class="pdf-value">{{ $payment->paymentID }}</div>
                    <div class="pdf-muted">Date: {{ $payment->created_at->format('Y-m-d') }}</div>
                </td>
            </tr>
        </table>

        @php
            $months = [
                '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
            ];
        @endphp

        <table class="pdf-table pdf-section">
            <tr>
                <td>
                    <div class="pdf-label">Teacher</div>
                    <div class="pdf-value">{{ $payment->teacher->tFName }} {{ $payment->teacher->tLName }}</div>
                    <div class="pdf-muted">ID: {{ $payment->teacher->T_ID }}</div>
                </td>
                <td class="pdf-right">
                    <div class="pdf-label">Month</div>
                    <div class="pdf-value">{{ $months[$payment->month] ?? 'Unknown' }}</div>
                </td>
            </tr>
        </table>

        <hr class="border-top-dashed">

        <table class="pdf-table pdf-section">
            <tr>
                <td>
                    <div class="pdf-label">Description</div>
                    <div class="pdf-muted">Monthly class payment</div>
                </td>
                <td class="pdf-right">
                    <div class="pdf-label">Amount</div>
                    <div class="pdf-amount">Rs. {{ number_format($payment->amount, 2) }}</div>
                </td>
            </tr>
        </table>

        <p class="pdf-muted" style="margin-top: 16px;">
            This is a system-generated receipt for your records.
        </p>
        <p class="pdf-muted">
            Processed by CHROMA.
        </p>
    @else
        <div class="card receipt-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <img src="{{ asset('front-assets/img/logo.png') }}" alt="Logo" class="logo">
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
                    <small class="text-muted">Processed by CHROMA.</small>
                    @if(!request()->has('download'))
                        <a href="{{ route('teacher-payments.receipt', ['id' => $payment->paymentID]) }}?download=1" class="btn btn-sm btn-primary no-print">Download PDF</a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>
