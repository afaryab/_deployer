<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'tenant_id',
        'application_id',
        'tenant_app_id',
        'domain',

    ];

    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function tenant_app()
    {
        return $this->belongsTo(TenantApp::class);
    }
}
