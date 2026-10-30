<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toeslag extends Model
{
    protected $table = 'toeslagen';

    protected $fillable = ['slug', 'naam', 'omschrijving', 'voorwaarden', 'max_inkomen'];

    protected function casts(): array
    {
        return ['voorwaarden' => 'array', 'max_inkomen' => 'decimal:2'];
    }

    // In de URL gebruiken we de slug: /toeslagen/huurtoeslag/aanvragen
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function aanvragen(): HasMany
    {
        return $this->hasMany(Aanvraag::class);
    }
}
