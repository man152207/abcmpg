<?php

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routeName) {
        return request()->routeIs($routeName) ? 'active' : '';
    }
}

if (!function_exists('isMenuOpen')) {
    function isMenuOpen($routeNames) {
        foreach ($routeNames as $routeName) {
            if (request()->routeIs($routeName)) {
                return 'menu-open';
            }
        }
        return '';
    }
}
