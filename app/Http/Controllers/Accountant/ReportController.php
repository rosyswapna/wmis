<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;

use App\Models\InvoiceItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\DatabaseNotification;

use App\Services\Reports\WorkersReportService;
use App\Services\Reports\InvoicePaymentReportService;

use App\Models\ReportExport;
use App\Jobs\ExportWorkersReportJob;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CommonReportExport;


class ReportController extends Controller
{
    protected WorkersReportService $workersReportService;
    protected InvoicePaymentReportService $invoicePaymentReportService;

    public function __construct(
        WorkersReportService $workersReportService, 
        InvoicePaymentReportService $invoicePaymentReportService)
    {
        $this->workersReportService = $workersReportService;
        $this->invoicePaymentReportService = $invoicePaymentReportService;
    }

    /**
     * Display workers report.
     */
    public function workers(Request  $request)
    {

        $filters = $request->only([
                'worker_name',
                'date_from',
                'date_to',
            ]);
        $reportColumns = $this->workersReportService->columns();
        $reportData = $this->workersReportService
            ->query($filters)
            ->paginate(20)
            ->withQueryString();        
        
        return view('report.workers', compact('reportColumns','reportData'));
    }

    /**
     * Export workers report.
     */
    public function exportWorkers(Request $request)
    {
        $filters = $request->only([
            'worker_name',
            'date_from',
            'date_to',
        ]);        

        // Same query used by the Workers Report page
        $query = $this->workersReportService->query($filters);
        $reportColumns = $this->workersReportService->columns();
        $reportHeaders = array_values($reportColumns);
        $reportDataKeys = array_keys($reportColumns);

        $fileName = 'workers-report-' . now()->format('Ymd-His') . '.xlsx';
        $filePath = 'export/workers/' . $fileName;

        // Generate Excel file
        Excel::store(
            new CommonReportExport($query, $reportHeaders, $reportDataKeys),
            $filePath
        );

        $export = ReportExport::create([ 
            'user_id' => auth()->id(), 
            'type' => 'workers',
            'status' => 'completed',
            'file_path' => $filePath,
            'error' => null,
        ]);

        return Storage::download(
            $filePath,
            $fileName
        );
    }

    /**
     * Export workers report.
     */
    public function exportWorkersByJob(Request $request)
    {
        $filters = $request->only([
            'worker_name',
            'date_from',
            'date_to',
        ]);

        $export = ReportExport::create([ 'user_id' => auth()->id(), 'type' => 'workers', 'status' => 'queued', ]);

        ExportWorkersReportJob::dispatch(
            $filters,
            $export->id,
            auth()->id()
        );

        return back()->with(
            'success',
            'Workers report export has been queued.'
        );
    }


    /**
     * Download exported workers report.
     */
    public function downloadWorkersExport($id, $notification)
    {
        $export = ReportExport::findOrFail($id);
  
        // Make sure the export belongs to the logged-in user
        abort_unless(
            $export->user_id === auth()->id(),
            403
        );

        // Make sure the file exists
        abort_unless(
            Storage::exists($export->file_path),
            404
        );

        // Mark notification as read
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notification->markAsRead();

        // Download private file
        return Storage::download(
            $export->file_path,
            basename($export->file_path)
        );
    }

    /**
     * Display workers report.
     */
    public function invoicePayments(Request  $request)
    {

        $filters = $request->only([
                'invoice_number',
                'date_from',
                'date_to',
            ]);
        $reportColumns = $this->invoicePaymentReportService->columns();
        $reportData = $this->invoicePaymentReportService
            ->query($filters)
            ->paginate(20)
            ->withQueryString();        
        
        return view('report.invoicePayments', compact('reportColumns','reportData'));
    }
    /**
     * Export workers report.
     */
    public function exportInvoicePayments(Request $request)
    {
        $filters = $request->only([
            'invoice_number',
            'date_from',
            'date_to',
        ]);        

        // Same query used by the Workers Report page
        $query = $this->invoicePaymentReportService->query($filters);
        $reportColumns = $this->invoicePaymentReportService->columns();
        $reportHeaders = array_values($reportColumns);
        $reportDataKeys = array_keys($reportColumns);

        $fileName = 'payments-report-' . now()->format('Ymd-His') . '.xlsx';
        $filePath = 'export/invoicePayments/' . $fileName;

        // Generate Excel file
        Excel::store(
            new CommonReportExport($query, $reportHeaders, $reportDataKeys),
            $filePath
        );

        $export = ReportExport::create([ 
            'user_id' => auth()->id(), 
            'type' => 'invoicePayments',
            'status' => 'completed',
            'file_path' => $filePath,
            'error' => null,
        ]);

        return Storage::download(
            $filePath,
            $fileName
        );
    }
}
