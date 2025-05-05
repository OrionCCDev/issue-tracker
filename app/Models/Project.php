<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Project extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'duration',
        'commencement_date',
        'completion_date',
        'project_value',
        'total_billed',
        'remaining_unbilled',
        'planned_percentage',
        'actual_percentage',
        'variance_percentage',
        'time_elapsed',
        'time_balance',
        'variation_status',
        'variation_amount',
        'eot_status',
        'ncrs_status',
        'current_invoice_status',
        'current_invoice_value',
        'current_invoice_month',
        'previous_invoice_status',
        'previous_invoice_value',
        'previous_invoice_month',
        'expected_invoice_date',
        'expected_invoice_month',
        'expected_invoice'
    ];

    protected $casts = [
        'commencement_date' => 'date',
        'completion_date' => 'date',
        'expected_invoice_date' => 'date',

        'project_value' => 'decimal:2',
        'total_billed' => 'decimal:2',
        'remaining_unbilled' => 'decimal:2',
        'planned_percentage' => 'decimal:2',
        'actual_percentage' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
        'variation_amount' => 'decimal:2',
        'current_invoice_value' => 'decimal:2',
        'previous_invoice_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessor for current invoice month name
    public function getCurrentInvoiceMonthNameAttribute()
    {
        if (!$this->current_invoice_month) {
            return null;
        }
        return Carbon::createFromFormat('m', $this->current_invoice_month)->format('F');
    }

    // Accessor for previous invoice month name
    public function getPreviousInvoiceMonthNameAttribute()
    {
        if (!$this->previous_invoice_month) {
            return null;
        }
        return Carbon::createFromFormat('m', $this->previous_invoice_month)->format('F');
    }

    // Accessor for expected invoice month name
    public function getExpectedInvoiceMonthNameAttribute()
    {
        if (!$this->expected_invoice_month) {
            return null;
        }
        return Carbon::createFromFormat('m', $this->expected_invoice_month)->format('F');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
