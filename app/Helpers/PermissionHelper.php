<?php
if (!function_exists('userCanAccess')) {
    function userCanAccess($module_id, $action = 'IND_MODULO')
    {
        // Verificar si la sesión PHP está activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtener los permisos desde la sesión PHP
        $permisos = $_SESSION['user_permisos'] ?? [];

        return collect($permisos)
            ->where('COD_OBJETO', $module_id)
            ->where($action, 1)
            ->isNotEmpty();
    }
}

