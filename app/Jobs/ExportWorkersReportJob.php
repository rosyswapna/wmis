<?php

namespace App\Jobs;

use App\Exports\WorkersReportExport;
use App\Models\ReportExport;
use App\Models\User;
use App\Notifications\WorkersReportExportCompleted;
use App\Services\Reports\WorkersReportService;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Maatwebsite\Excel\Facades\Excel;

class ExportWorkersReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected array $filters,
        protected int $exportId,
        protected int $userId
    ) {
    }

    public function handle(
        WorkersReportService $workersReportService
    ): void {
        $export = ReportExport::findOrFail($this->exportId);

        try {
            $export->update([
                'status' => 'processing',
            ]);

            // Same query used by the Workers Report page
            $query = $workersReportService->query($this->filters);
            $reportColumns = $workersReportService->columns();
            $reportHeaders = array_values($reportColumns);
            $reportDataKeys = array_keys($reportColumns);

            $fileName = 'workers-report-' . $this->exportId . '.xlsx';
            $filePath = 'export/workers/' . $fileName;

            // Generate Excel file
            Excel::store(
                new WorkersReportExport($query, $reportHeaders, $reportDataKeys),
                $filePath
            );

            $export->update([
                'status' => 'completed',
                'file_path' => $filePath,
                'error' => null,
            ]);

            // Notify the user
            $user = User::find($this->userId);

            if ($user) {
                $user->notify(
                    new WorkersReportExportCompleted(
                        $this->exportId
                    )
                );
            }

        } catch (\Throwable $e) {

            $export->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}