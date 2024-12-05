<?php
namespace App\Http\Middleware;

use Closure;

class FilterMenuPermissions
{
    public function handle($request, Closure $next)
    {
        // Obtener el menú configurado en config/adminlte.php
        $menu = config('adminlte.menu');

        // Filtrar el menú completo
        $filteredMenu = $this->filterMenu($menu);

        // Reemplazar el menú en la configuración
        config(['adminlte.menu' => $filteredMenu]);

        return $next($request);
    }

    private function filterMenu($menu)
    {
        return array_filter(array_map(function ($item) {
            // Si el ítem tiene un `module_id` y no tiene permisos, eliminarlo
            if (isset($item['module_id']) && !userCanAccess($item['module_id'], 'IND_MODULO')) {
                return null;
            }

            // Si el ítem tiene submenús, procesarlos recursivamente
            if (isset($item['submenu'])) {
                $item['submenu'] = $this->filterMenu($item['submenu']);
                
                // Retener el ítem solo si hay submenús visibles
                return !empty($item['submenu']) ? $item : null;
            }

            return $item; // Retener ítem si pasa las validaciones
        }, $menu));
    }
}
