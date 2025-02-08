<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    // protected $fillable = [
    //     'name',
    //     'slug',
    //     'description',
    //     'icon',
    //     'provider',
    // ];

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
    ];

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }

    // public function links()
    // {
    //     return $this->hasMany(TenantAppLink::class);
    // }

    public function tenant_apps()
    {
        return $this->hasMany(TenantApp::class);
    }

    public function deployment_activities()
    {
        return $this->hasMany(DeploymentActivity::class);
    }
}
