<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use App\Models\TenantAppLink;
use App\Models\Application;
use App\Models\Domain;

class TenantApp extends Model
{
    protected $fillable = [
        'tenant_id',
        'application_id',
        'name',
        'slug',
        'icon'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function links()
    {
        return $this->hasMany(TenantAppLink::class);
    }

    public function domains()
    {
        // return $this->hasMany(Domain::class, 'tenant_app_id', 'id');
        return $this->hasMany(Domain::class, 'tenant_app_id', 'id');
    }

    public function domain()
    {
        // return $this->hasMany(Domain::class, 'tenant_app_id', 'id');
        return $this->hasOne(Domain::class, 'tenant_app_id', 'id');
    }
}
