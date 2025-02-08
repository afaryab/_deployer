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
        'admin_name'

    ];

    public function apps()
    {
        return $this->hasMany(TenantApp::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

}
