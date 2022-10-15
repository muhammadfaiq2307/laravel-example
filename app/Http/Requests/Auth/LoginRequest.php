<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if(! $this->authenticateLdap()){
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }

    /**
     * Open and possibly auth LDAP Paragon Connection
     * 
     * @param string $uidPart
     * 
     * @return boolean
     */
    public function openConnectionLdapParagon($uidPart){
        $dn     = str_replace('%INSERT_UID_HERE%',$uidPart,env('LDAP_PARAGON_DN'));
        $host   = env('LDAP_PARAGON_HOST');
        $port   = env('LDAP_PARAGON_PORT');
        $isAuthenticated = false;

        $ldapConnection = ldap_connect($host, $port);
        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);

        if(@ldap_bind($ldapConnection, $dn, $this->password)){
            User::where('email',$this->email)->update(['password' => Hash::make($this->password)]);
            $isAuthenticated = true;
        }

        return $isAuthenticated;
    }

    /**
     * Open and possibly auth LDAP Parama Connection
     * 
     * @param string $uidPart
     * 
     * @return boolean
     */
    public function openConnectionLdapParama($uidPart){
        $dn     = str_replace('%INSERT_UID_HERE%',$uidPart,env('LDAP_PARAMA_DN'));
        $host   = env('LDAP_PARAMA_HOST');
        $port   = env('LDAP_PARAMA_PORT');
        $isAuthenticated = false;

        $ldapConnection = ldap_connect($host, $port);
        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);

        if(@ldap_bind($ldapConnection, $dn, $this->password)){
            User::where('email',$this->email)->update(['password' => Hash::make($this->password)]);
            $isAuthenticated = true;
        }

        return $isAuthenticated;
    }

    /**
     * Open and possibly auth LDAP Pharmacore Connection
     * 
     * @param string $uidPart
     * 
     * @return boolean
     */
    public function openConnectionLdapPharmacore($uidPart){
        $dn         = env('LDAP_PHARMACORE_DN');
        $host       = env('LDAP_PHARMACORE_HOST');
        $port       = env('LDAP_PHARMACORE_PORT');
        $password   = env('LDAP_PHARMACORE_PASSWORD');
        $isAuthenticated = false;

        $ldapConnection = ldap_connect($host, $port);
        if(@ldap_bind($ldapConnection, $dn, $password)){
            $filter = str_replace('%INSERT_UID_HERE%',$uidPart,env('LDAP_PHARMACORE_FILTER'));
            $search = ldap_search($ldapConnection, env('LDAP_PHARMACORE_BASE_DN'), $filter);

            $isAuthExist    = ldap_get_entries($ldapConnection, $search);
            $userDn         = $isAuthExist[0]['dn'];
            if(@ldap_bind($ldapConnection, $userDn, $this->password)){
                User::where('email',$this->email)->update(['password' => Hash::make($this->password)]);
                $isAuthenticated = true;
            }
        }
        return $isAuthenticated;
    }
    
    /**
     * Attempt to authenticate the request's credentials against correspondence LDAP
     * If authenticated, will update password in local User table then proceed with the usual authentication
     * 
     * @return boolean
     */
    public function authenticateLdap(){
        $filterUid  = array('$','-','_','+','*',"'","{","}","|","!","^","~","[","]","`","#","%","/","@","&","=","?"); //these chars are available through FILTER_SANITIZE_EMAIL but need to be omitted for LDAP authentication (allowed symbols on LDAP: '.')
        $uidRaw     = explode('@', $this->email);
        $uidPart    = str_replace($filterUid,"",$uidRaw[0]);
        $emailPart  = $uidRaw[1];
        $isAuthenticated    = false;

        switch ($emailPart) {
            case str_contains($emailPart, 'pti-cosmetics.com'):
                $isAuthenticated = $this->openConnectionLdapParagon($uidPart);
                break;
            case str_contains($emailPart, 'paramaglobalinspira.com'):
                $isAuthenticated = $this->openConnectionLdapParama($uidPart);
                break;
            case str_contains($emailPart, 'pharmacore-my.com'):
                $isAuthenticated = $this->openConnectionLdapPharmacore($uidPart);
                break;
            default:
                $isAuthenticated = true;
                break;
        }
        return $isAuthenticated;
    }
}
