<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Service;
use App\Models\Hospital;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('accountant')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        return response()->json([
            'client_count' => Client::count(),

            'draft_count' => Invoice::join(
                    'invoice_status',
                    'invoice_status.id',
                    '=',
                    'invoice.status_id'
                )
                ->where('invoice_status.name', 'Draft')
                ->count('invoice.id'),

            'invoice_count' => Invoice::join(
                    'invoice_status',
                    'invoice_status.id',
                    '=',
                    'invoice.status_id'
                )
                ->whereNotIn('invoice_status.name', ['Draft', 'Cancelled'])
                ->whereDate('invoice.invoice_date', today())
                ->count('invoice.id'),
                Invoice::whereDate(
                    'created_at',
                    today()
                )->count(),

            'total_invoice' => number_format(
                Invoice::sum('total'),
                2
            ),
        ]);
    }

    public function monthlySales()
    {
        if (!Auth::user()?->hasRole('accountant')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $sales = Invoice::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as total')
            )
            ->where('invoice_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $month = $date->format('Y-m');

            $record = $sales->firstWhere('month', $month);

            $months->push([
                'month' => $date->format('M Y'),
                'total' => $record ? (float) $record->total : 0,
            ]);
        }

        return response()->json($months);
    }
}
