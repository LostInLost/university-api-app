<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    static function scopeFilter(Builder $query, $request)
    {
        $query->when($request['q'] ?? false, function(Builder $query, $search)  {
            $query
            ->orWhere('name', 'LIKE', "%$search%")
            ->orWhere('university', 'LIKE', "%$search%");
        });

    }
}
