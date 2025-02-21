<?php

namespace App\Http\Resources;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GatsByCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tenants = Tenant::with('apps')->get();

        return [
            'title' => 'Laravel',
            'version' => app()->version(),
            'count' => $tenants->count(),
            'tenants' => new TenantCollection($tenants),
        ];
    }
}
