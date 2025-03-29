<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Dispenser;

class VerifyDispenserToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization'); // Token comes as Bearer

        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return response()->json(['error' => 'Missing or invalid token'], 401);
        }

        $token = substr($token, 7); // remove "Bearer "

        $dispenser = Dispenser::where('token', $token)->first();

        if (!$dispenser) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Attach to request so controller can access it
        $request->merge(['dispenser' => $dispenser]);

        return $next($request);
    }
}
