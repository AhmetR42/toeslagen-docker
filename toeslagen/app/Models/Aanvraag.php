<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aanvraag extends Model
{
    protected $table = 'aanvragen';

    public const STATUSSEN = [
        'in_behandeling' => 'In behandeling',
        'goedgekeurd' => 'Goedgekeurd',
        'afgewezen' => 'Afgewezen',
    ];

    // user_id en status staan bewust niet in $fillable: die zet de controller zelf
    protected $fillable = [
        'toeslag_id', 'inkomen', 'huishouden',
        'huur_per_maand', 'aantal_kinderen', 'opvang_uren', 'zorgverzekering',
    ];

    protected function casts(): array
    {
        return [
            'inkomen' => 'decimal:2',
            'huur_per_maand' => 'decimal:2',
            'zorgverzekering' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toeslag(): BelongsTo
    {
        return $this->belongsTo(Toeslag::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSSEN[$this->status] ?? $this->status;
    }
}
