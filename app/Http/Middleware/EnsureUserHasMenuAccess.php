<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemMainMenu;
use App\Models\SystemRoleMainMenu;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CustomInvalidException;
class EnsureUserHasMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $mainMenuId primary key for checked menu
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $mainMenuId)
    {
        $mains = SystemMainMenu::find($mainMenuId)->roleMainMenu->toArray();
        $mns   = array_column($mains,'auth_item_id');
        $roles = SystemRoleMainMenu::getRolesByMainMenus($mns)->toArray();
        $rls   = array_column($roles, 'auth_item_id');
        $usersroles = Auth::user()->userRole;
        $isAuthenticated = false;
        foreach($usersroles as $usersrole){
            if(in_array($usersrole->auth_item_id, $rls)){
                $isAuthenticated = true;
                break;
            }
        }
        if(!$isAuthenticated){
            throw new CustomInvalidException('Unauthenticated');
        }
        return $next($request);
    }
}
