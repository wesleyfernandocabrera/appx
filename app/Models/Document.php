<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'status',
        'macro_id',
        'revision',
        'user_id',
    ];

    public function macro()
    {
        return $this->belongsTo(Macro::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'document_sector');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'document_company');
    }
}
