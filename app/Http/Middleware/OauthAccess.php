<?php

namespace App\Http\Middleware;

use App\Models\DeviceCredential;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OauthAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(empty($request->header('Client-Token')) || !$this->checkAuth($request->header('Client-Token'))){
            return new Response('Client Token Missing', 403);
        }
        return $next($request);
    }

    private function checkAuth($token)
    {
        return DeviceCredential::where('token', $token)->exists();
    }
}
