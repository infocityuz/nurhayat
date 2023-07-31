<?php

namespace Modules\ForTheBuilder\Entities;

use App\Models\ObjectDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PriceType extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'price_type';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    

    protected $fillable = [
        'name',
        'status',
        'updated_at',
        'created_at',
        'name_ru',
        'name_en',
    ];

    
}
