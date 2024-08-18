<?php

namespace Robiokidenis\FilamentMultiTenancy\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Robiokidenis\FilamentMultiTenancy\Models\Tenant;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $tenant = $this->resolveTenant($request);

        if (!$tenant) {
            abort(404, 'Tenant not found.');
        }

        if (!$user->tenants->contains($tenant)) {
            abort(403, 'You do not have access to this tenant.');
        }

        $user->setCurrentTenant($tenant);
        
        $request->merge(['tenant' => $tenant]);
        
        return $next($request);
    }

    protected function resolveTenant(Request $request): ?Tenant
    {
        // First, try to resolve from the route parameter
        if ($request->route('tenant')) {
            return Tenant::where('id', $request->route('tenant'))
                ->orWhere('slug', $request->route('tenant'))
                ->first();
        }

        // If not found in route, try to resolve from the subdomain
        $subdomain = explode('.', $request->getHost())[0];
        return Tenant::where('slug', $subdomain)->first();
    }
}