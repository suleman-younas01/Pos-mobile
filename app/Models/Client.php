<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'firstname', 'lastname',
        'name', 'code', 'adresse', 'email', 'phone', 'cnic', 'country', 'city', 'state', 'zip', 'tax_number',
        'is_royalty_eligible', 'points', 'opening_balance', 'credit_limit', 'credit_balance',
        'due_date', 'payment_reminder_status',
        'woocommerce_id',
        'sync_issue_type', 'sync_issue_message', 'sync_issue_source', 'sync_issue_at',
    ];

    protected $casts = [
        'code' => 'integer',
        'is_royalty_eligible' => 'integer',
        'points' => 'double',
        'opening_balance' => 'double',
        'credit_limit' => 'double',
        'credit_balance' => 'double',
        'due_date' => 'date',
        'sync_issue_at' => 'datetime',
    ];

    /**
     * Get custom field values for this client
     */
    public function customFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'entity', 'entity_type', 'entity_id');
    }
}
