<?php

namespace App\Http\Middleware;

use App\Models\LogAcesso;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAcessoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //LogAcesso::created(['log' => 'IP xyz requisitou a rota ABCD']);

        $user = Auth::user();

        $usuario = $user->id;
        $ip = $request->server->get('REMOTE_ADDR');
        $rota = $request->getRequestUri();

        $logAcesso = new LogAcesso();
        $logAcesso->usuario = $usuario;
        $logAcesso->ip = $ip;
        $logAcesso->rota = $rota;
        $logAcesso->save();

        //return response('chegamos até o middware');
        return $next($request);
    }
}
