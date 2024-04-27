<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanPasien extends Model
{
    use HasFactory;
    public function jenisTindakan()
    {
        return $this->belongsTo(JenisTindakan::class);
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class)->withPivot('tanggal', 'diskon', 'grand_total');
    }
    public function pasiens()
    {
        return $this->belongsToMany(Pasien::class)->withPivot('tanggal', 'diskon', 'grand_total');
    }
}
