<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankingData extends Model
{
    protected $table = 'banking_data';

    protected $fillable = [
        'tanggal',
        'osl_kca',
        'osl_mikro',
        'osl_emas',
        'gte',
        'nasabah_baru',
        'nasabah_baru_agen',
        'nasabah_existing',
        'nasabah_tabungan_emas',
        'deposito',
        'tabungan_emas',
        'g24',
        'nasabah_tring',
        'osl_tring',
        'frekuensi_trx_tring',
        'disbursement_bri',
        'osl_sinergi_holding',
        'te_sinergi_holding',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'osl_kca' => 'decimal:2',
        'osl_mikro' => 'decimal:2',
        'osl_emas' => 'decimal:2',
        'gte' => 'decimal:2',
        'deposito' => 'decimal:3',
        'tabungan_emas' => 'decimal:3',
        'g24' => 'decimal:3',
        'osl_tring' => 'decimal:2',
        'disbursement_bri' => 'decimal:2',
        'osl_sinergi_holding' => 'decimal:2',
        'te_sinergi_holding' => 'decimal:3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
