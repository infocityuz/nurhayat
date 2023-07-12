<?php

namespace Modules\ForTheBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealsFile extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'deals_files';

    protected $fillable = [
        'deal_id',
        'name',
        'guid',
        'guid',
        'ext',
        'size',
        'main_image'

    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
