<?php

namespace Robiokidenis\FilamentMultiTenancy\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $filamentTenantId = Filament::getTenant()?->id;
        $spatieTeamId = $this->getPermissionsTeamId();

        if ($filamentTenantId !== null && $filamentTenantId !== $spatieTeamId) {
            $this->setPermissionsTeamId($filamentTenantId);
        }

        return $next($request);
    }

    /**
     * Get the current Permissions team ID.
     */
    protected function getPermissionsTeamId(): ?int
    {
        return app(\Spatie\Permission\PermissionRegistrar::class)->getPermissionsTeamId();
    }

    /**
     * Set the Permissions team ID.
     */
    protected function setPermissionsTeamId(int $teamId): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($teamId);
    }
}
