<?php

namespace Modules\ForTheBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStatus extends Model
{
    const NEW_STATUS = 1;
    
    use SoftDeletes;

    protected $connection = 'mysql2';
    protected $table = 'lead_status';

    protected $fillable = [
        'name',
        'order'
    ];

    public function leads() :HasMany
    {
        return $this->hasMany(Leads::class);
    }


}
