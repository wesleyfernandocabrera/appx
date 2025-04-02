<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Macro extends Model
{

    protected $fillable = ['name', 'description'];

    // Relacionamento: Uma macro pode pertencer a vários usuários

}
