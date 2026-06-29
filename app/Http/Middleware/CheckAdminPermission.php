<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AdminPermissionService;

class CheckAdminPermission
{
    protected $permissionService;

    public function __construct(AdminPermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Handle an incoming request with granular permission checking.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  The permission to check (e.g., 'crud-tabungan', 'crud-pinjaman')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = auth()->user();
        $hasPermission = false;

        // Check permission based on the parameter
        switch ($permission) {
            case 'crud-tabungan':
                $hasPermission = $this->permissionService->canCrudTabunganTransaksi($user);
                break;
            case 'crud-pinjaman':
                $hasPermission = $this->permissionService->canCrudPinjamanAktif($user);
                break;
            case 'crud-biaya-transfer':
            case 'crud-master-data':
                $hasPermission = $this->permissionService->canCrudMasterData($user);
                break;
            case 'crud-item-gadai':
                $hasPermission = $this->permissionService->canCrudItemGadai($user);
                break;
            case 'manage-nasabah':
                $hasPermission = $this->permissionService->canManageNasabah($user);
                break;
            case 'verify-nasabah':
                $hasPermission = $this->permissionService->canVerifyNasabah($user);
                break;
            case 'pelunasan-dipercepat':
                $hasPermission = $this->permissionService->canPelunasanDipercepat($user);
                break;
            default:
                $hasPermission = false;
        }

        if (!$hasPermission) {
            // If it's an AJAX request, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk fitur ini. Hubungi Admin Utama untuk informasi lebih lanjut.'
                ], 403);
            }

            // For web requests, abort with 403
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hubungi Admin Utama untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
}
