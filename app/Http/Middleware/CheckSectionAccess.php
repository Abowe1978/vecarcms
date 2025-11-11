<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Section;

class CheckSectionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Se l'utente è developer, super-admin o admin, ha accesso completo
        if ($user->hasRole(['developer', 'super-admin', 'admin'])) {
            return $next($request);
        }

        // Se l'utente è moderatore, verifichiamo l'accesso alla sezione
        if ($user->hasRole('moderator')) {
            $sectionId = $request->route('section');
            
            // Se non c'è un ID sezione nella route, permette l'accesso alla lista
            if (!$sectionId) {
                return $next($request);
            }

            // Verifica se il moderatore è assegnato a questa sezione
            $section = Section::findOrFail($sectionId);
            if ($user->section_id === $section->id) {
                return $next($request);
            }

            abort(403, 'Non hai i permessi per accedere a questa sezione.');
        }

        // Se l'utente è leadership di sezione (chair, secretary, treasurer)
        $userSections = Section::where(function($query) use ($user) {
            $query->where('chair_id', $user->id)
                  ->orWhere('secretary_id', $user->id)
                  ->orWhere('treasurer_id', $user->id);
        })->pluck('id')->toArray();
        
        if (!empty($userSections)) {
            $sectionId = $request->route('section');
            
            // Se non c'è un ID sezione nella route, permette l'accesso alla lista
            if (!$sectionId) {
                return $next($request);
            }

            // Verifica se l'utente è leadership di questa sezione specifica
            if (in_array($sectionId, $userSections)) {
                return $next($request);
            }

            abort(403, 'Non hai i permessi per accedere a questa sezione.');
        }

        abort(403, 'Non hai i permessi per accedere a questa risorsa.');
    }
} 