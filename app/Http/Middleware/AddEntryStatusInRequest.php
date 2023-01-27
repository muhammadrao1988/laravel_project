<?php

namespace App\Http\Middleware;

use Closure;

class AddEntryStatusInRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['entryStatus' => config('api.entry_status')]);
        return $next($request);
    }
}
