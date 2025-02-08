<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeploymentActivity extends Model
{
    protected $guarded = [];

    protected $casts = [
        'output' => 'array'
    ];

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

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
