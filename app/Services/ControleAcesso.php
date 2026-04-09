<?php

namespace App\Http\Middleware;

use App\Models\Solicitacao;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControleAcesso
{
    /**
     * Handle an incoming request.
     *
     * Este middleware verifica se o usuário tem permissão para acessar uma solicitação específica.
     * - Superadmins podem ver tudo.
     * - Responsáveis de área só podem ver solicitações de sua própria área.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Superadmin tem acesso a tudo
        if ($user->perfil === 'superadmin') {
            return $next($request);
        }

        // Responsável de área só pode acessar solicitações da sua área
        if ($user->perfil === 'responsavel') {
            // Pega a solicitação da rota (via route model binding)
            $solicitacao = $request->route('solicitacao');

            // Garante que o parâmetro é uma instância de Solicitação e que pertence à área do usuário
            if (!$solicitacao instanceof Solicitacao || $user->area_id !== $solicitacao->area_id) {
                abort(403, 'Você não tem permissão para acessar esta solicitação.');
            }

            return $next($request);
        }

        // Se não for superadmin nem responsável, nega o acesso por padrão
        abort(403, 'Acesso não autorizado.');
    }
}