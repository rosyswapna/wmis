<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;

use App\Models\InvoiceItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\DatabaseNotification;

use App\Services\Reports\WorkersReportService;
use App\Models\ReportExport;
use App\Jobs\ExportWorkersReportJob;

class ReportController extends Controller
{
    protected WorkersReportService $workersReportService;
    public function __construct(WorkersReportService $workersReportService)
    {
        $this->workersReportService = $workersReportService;
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

    public function styles(Worksheet $sheet) { 
        return [ 
            1 => [ 
                'font' => [ 
                    'bold' => true, 
                ], 
                ], 
        ]; 
    }
}
