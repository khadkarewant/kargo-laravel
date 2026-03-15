<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNoManagerExists
{
    public function handle(Request $request, Closure $next): Response
    {
        $managerExists = User::where('role', 'manager')->exists();

        if ($managerExists) {
            abort(403, 'Initial manager setup is no longer available.');
        }

        return $next($request);
    }
}