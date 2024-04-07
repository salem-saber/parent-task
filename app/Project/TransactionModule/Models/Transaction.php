<?php

namespace Project\TransactionModule\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'double',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'amount',
        'currency',
        'email',
        'status',
        'transaction_date',
        'provider',
        'provider_id',
    ];

}
