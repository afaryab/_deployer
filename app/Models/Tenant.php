<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;
use App\Models\TenantApp;


class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'admin_email',
        'admin_name',
        'country_id',
        'currency_id'

    ];

    public function apps()
    {
        return $this->hasMany(TenantApp::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
