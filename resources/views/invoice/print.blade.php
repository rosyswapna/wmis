```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>
        Invoice -
        {{ collect([
            $invoice->invoice_number_prefix,
            $invoice->invoice_number,
            $invoice->invoice_number_suffix
        ])->filter(fn($value) => filled($value))->implode('-') }}
    </title>

    <style>
        @page {
            margin: 120px 35px 100px 35px;
        }

        body {
            font-family: "Roboto";
            font-size: 12px;
            color: #222;
            margin: 0;
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

        /* =========================
           CONTENT
        ========================= */

        .invoice-title {
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            margin: 0 0 15px 0;
           
        }
        .invoice-title span {
             border-bottom: 1px solid #000000;
        }

        .hospital-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .hospital-info td {
            vertical-align: top;
        }

        .name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .details-table td {
            width: 50%;
            vertical-align: top;
            padding: 4px 0;
        }

        .right {
            text-align: right;
        }

        .label {
            font-weight: bold;
        }

        /* =========================
           ITEMS
        ========================= */

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .items-table th {
            background: #eeeeee;
            border: 1px solid #555;
            padding: 7px 5px;
            font-weight: bold;
            text-align: center;
        }

        .items-table td {
            border: 1px solid #777;
            padding: 6px 5px;
            vertical-align: top;
        }

        .items-table .sl {
            width:8%;
            text-align: center;
        }

        .items-table .description {
            width: 35%;
        }

        .items-table .qty {
            width: 9%;
            text-align: center;
        }

        .items-table .rate {
            width: 12%;
            text-align: right;
        }

        .items-table .net {
            width: 13%;
            text-align: right;
        }

        .items-table .vat {
            width: 11%;
            text-align: right;
        }

        .items-table .total {
            width: 14%;
            text-align: right;
        }
        .workers{
            padding-left: 15px;
        }

        .client-info{
            width:80%;
        }
        .inv-info{
            width:20%
        }

        /* =========================
           SUMMARY
        ========================= */

        .summary-wrapper {
            width: 100%;
            margin-top: 12px;
        }

        .summary-table {
            width: 100%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px;
            border: 1px solid #777;
        }

        .summary-label {
            font-weight: bold;
            width: 86%;
        }

        .grand-total td {
            font-size: 11px;
            font-weight: bold;
            background: #eeeeee;
        }

        /* =========================
           SIGNATURE SECTION
        ========================= */

        .signature-section {
            width: 100%;
            margin-top: 45px;
            border-collapse: collapse;
        }

        .signature-section td {
            width: 50%;
            vertical-align: top;
        }

        .signature-left {
            text-align: left;
        }

        .signature-right {
            text-align: right;
        }

        .seal {
            width: 90px;
            height: auto;
            margin: 8px 0;
        }

        .prepared {
            margin-top: 5px;
        }

        .received {
            margin-top: 5px;
        }

        /* Prevent important blocks from splitting */
        .no-break {
            page-break-inside: avoid;
        }

        .page-number:after {
            content: counter(page);
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


    {{-- Hospital Information --}}

    <div>
        <span class="name">{{ $hospital->hospital_name }} -  {{ $hospital->ownership_type }}</span> <br>
        {{ $hospital->address }} <br>
        {{ $hospital->state->name }}, {{ $hospital->country->code }}<br>
        Tel: {{ $hospital->telephone_number }}<br>
        TRN: {{ $hospital->tax_registration_number }}
    </div>  

     {{-- Invoice Heading --}}

    <div class="invoice-title">
        <span>TAX INVOICE</span>
    </div>


    {{-- Client + Invoice Details --}}

    <table class="details-table">

        <tr>

            <td class="client-info">

                <span class="name">{{ $invoice->client->name ?? '' }}</span><br>               
                {{ $invoice->client->address ?? '' }}<br>  
                {{ $invoice->client->state->name ?? '' }}, {{ $invoice->client->country->code ?? '' }}<br>  
                TRN No: {{ $invoice->client->tax_registration_number ?? '' }}
            </td>

            <td  class="inv-info left">

                Invoice No:
                {{
                    collect([
                        $invoice->invoice_number_prefix,
                        $invoice->invoice_number,
                        $invoice->invoice_number_suffix
                    ])
                    ->filter(fn($value) => filled($value))
                    ->implode('-')
                }}
                <br>

                Invoice Date:
                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                <br>

                Due Date:
                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                <br>
                PO No/Date:<br>

                Payment Status: PAID<br>

            </td>

        </tr>

    </table>

   


    {{-- =====================================================
         ITEMS TABLE
    ====================================================== --}}

    <table class="items-table">

        <thead>
            <tr>
                <th class="sl">Sl NO</th>
                <th class="description">DESCRIPTION</th>
                <th class="qty">QTY</th>
                <th class="rate">RATE</th>
                <th class="net">NET</th>
                <th class="vat">VAT</th>
                <th class="total">TOTAL</th>
            </tr>
        </thead>
        <tbody>     
            <tr>
                <td class="sl">1</td>
                <td class="description">
                    {{ $invoice->service->name }}<br>
                    <div class="workers">
                    @foreach($invoice->items as $index => $item)<br>                    
                    {{$index+1}}.{{ $item->worker_name }}<br>
                    @endforeach
                    </div>
                </td>
                <td class="qty">
                    {{ number_format($invoice->quantity,0) }}
                </td>
                <td class="rate">
                    {{ number_format($invoice->unit_price, 2) }}
                </td>
                <td class="net">
                    {{ number_format($invoice->net_amount ?? $invoice->net_amount, 2) }}
                </td>
                <td class="vat">
                    {{ number_format($invoice->vat ?? 0, 2) }}
                </td>
                <td class="total">
                    {{ number_format($invoice->total, 2) }}
                </td>
            </tr>                    

        </tbody>

    </table>

    <div class="no-break">

        {{-- =====================================================
            SUMAMRY TABLE
        ====================================================== --}}
        <table class="summary-table">
            <tbody>
                <tr>
                    <td colspan="6" class="right summary-label">TOTAL</td>
                    <td>{{ number_format($invoice->net_amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="right summary-label">VAT</td>
                    <td>{{ number_format($invoice->vat, 2) }}</td>
                </tr>
                @if ($invoice->discount > 0)
                <tr>
                    <td colspan="6" class="right summary-label">DISCOUNT</td>
                    <td>{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="6" class="right summary-label">GRAND TOTAL</td>
                    <td>{{ number_format($invoice->total, 2) }}</td>
                </tr> 
            </tbody>
        </table>

        {{-- =====================================================
            SIGNATURE / APPROVAL
        ====================================================== --}}
        
        <table class="signature-section">
            <tr>
                {{-- LEFT --}}
                <td class="signature-left">
                    <strong>Prepared By,</strong>
                    <br>
                    <img
                        src="{{ public_path('storage/' . $hospital->company_seal) }}"
                        class="seal"
                        alt="Company Seal">
                    <br>
                    <strong>Accounts Department</strong><br>
                    {{ $hospital->hospital_name }}
                </td>

                {{-- RIGHT --}}
                <td class="signature-right">                 
                    <br><br><br><br><br><br><br>
                    <strong>Received By</strong>: ______________________
                </td>
            </tr>
        </table>
    </div>

</main>

</body>
</html>
