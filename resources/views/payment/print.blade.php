<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Payment Receipt - {{ $payment->reference_number }}</title>

    <style>
        @page {
            size: A4;
            margin: 120px 35px 100px 35px;
        }

        /* =========================
           HEADER
        ========================= */

        header {
            position: fixed;
            top: -105px;
            left: 0;
            right: 0;
            height: 95px;
            border-bottom: 1px solid #868c97;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo {
            width: 75px;
            height: auto;
        }

        .header-hospital {
            font-size: 18px;
            font-weight: bold;
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            position: fixed;
            bottom: -75px;
            left: 0;
            right: 0;
            height: 65px;
            border-top: 1px solid #868c97;
            font-size: 12px;
        }

        .footer-table {
            margin-top:10px;
            width: 100%;
            border-collapse: collapse;
            font-style: italic;
            color:#374151;
        }

        .footer-table td {
            vertical-align: top;
            padding-top: 8px;
        }

        .footer-right {
            text-align: right;

        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 0;
        }

        .receipt {
            width: 100%;
        }

       

        .hospital-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .hospital-details {
            font-size: 12px;
            line-height: 1.5;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 6px 4px;
        }

        .label {
            font-weight: bold;
            width: 20%;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #999;
            padding: 8px;
        }

        .invoice-table th {
            background: #f3f3f3;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
        }

        .payment-info {
            margin-top: 20px;
        }

        .amount-box {
            margin-top: 20px;
            border: 1px solid #999;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 60px;
            font-size: 11px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

{{-- =========================================================
     HEADER
========================================================= --}}

<header>

    <table class="header-table">
        <tr>

            <td width="20%">
                <img
                    src="{{ public_path('storage/' . $hospital->logo) }}"
                    class="logo"
                    alt="{{ $hospital->hospital_name }}"
                >
            </td>           
        </tr>
    </table>

</header>


{{-- =========================================================
     FOOTER
========================================================= --}}

<footer>
    <table class="footer-table">
        <tr>
            <td width="55%">
                <strong>
                    {{ $hospital->hospital_name }}                    
                </strong>
                <br>
                {{ $hospital->address }}
            </td>
            <td width="45%" class="footer-right">
                Call: {{ $hospital->telephone_number }}<br>
                Support: {{ $hospital->email }}
            </td>
        </tr>
    </table>
</footer>

{{-- =========================================================
     MAIN CONTENT
========================================================= --}}
<main>    
    <div class="receipt">

        {{-- Header --}}
        <div class="header">
            <div class="hospital-name">
                {{ $hospital->hospital_name }}
            </div>

            <div class="hospital-details">
                {{ $hospital->address }}<br>
                {{ $hospital->state->name }}, {{ $hospital->country->code }}<br>
                Tel: {{ $hospital->telephone_number }} | Email: {{ $hospital->email }}
            </div>
        </div>

        <div class="title">
            Receipt
        </div>

        {{-- Payment Details --}}
        <table class="details-table">
            <tr>            

                <td class="label">Client:</td>
                <td>
                    {{ $payment->client?->name }} <br>
                </td>

                <td class="label">Payment Method:</td>
                <td>
                    {{ $payment->payment_method ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Receipt No:</td>
                <td>{{ $payment->reference_number }}</td>

                <td class="label">Payment Date:</td>
                <td>{{ $payment->payment_date?->format('d-m-Y') }}</td>
            </tr>

            
        </table>

        {{-- Invoice Allocations --}}
        <table class="invoice-table">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th class="text-right">Invoice Total</th>
                    <th class="text-right">Amount Paid</th>
                </tr>
            </thead>

            <tbody>
                @foreach($payment->invoices as $index => $invoice)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ $invoice->invoice_number_full() }}
                        </td>

                        <td>
                            {{ $invoice->invoice_date?->format('d-m-Y') }}
                        </td>

                        <td class="text-right">
                            {{ number_format($invoice->total, 2) }}
                        </td>

                        <td class="text-right">
                            {{ number_format($invoice->pivot->amount, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="4" class="text-right">
                        Total Paid
                    </td>

                    <td class="text-right">
                        {{ number_format($payment->total_paid, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Payment Amount --}}
        <div class="amount-box">
            Amount Received:
            {{ number_format($payment->total_paid, 2) }} AED
        </div>

        {{-- Notes --}}
        @if($payment->notes)
            <div class="payment-info">
                <strong>Notes:</strong><br>
                {{ $payment->notes }}
            </div>
        @endif

        {{-- Created By --}}
        <div class="footer">
            Received by:
            {{ $payment->createdBy?->name ?? '-' }}
        </div>

        <div class="signature">
            ___________________________<br>
            Authorized Signature
        </div>

    </div>
</main>    

</body>
</html>