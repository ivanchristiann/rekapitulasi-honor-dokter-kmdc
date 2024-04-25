<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    public function pasien()
    {
        return $this->belongsToMany(Employees::class, 'TindakanPasien');
    }
}
