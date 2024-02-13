<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasUlids, HasApiTokens;

    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Students::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
