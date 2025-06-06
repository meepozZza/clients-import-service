<?php

namespace App\Models;

use App\Enums\ImportFileStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => ImportFileStatusEnum::class,
    ];
}
