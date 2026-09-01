<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportExport extends Model
{
    protected $table = "report_export";
    protected $fillable = [
        'type',
        'status',
        'user_id',
        'file_path',
        'error',
    ];
}
