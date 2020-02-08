<?php

namespace Benxmy\LimitedUseSignedUrl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LimitedUseSignedUrl extends Model
{
    protected $table = 'limited_use_signed_urls';

    protected $dates = [
        'first_accessed_at', 
        'last_reaccessed_at',
        'expires_at',
    ];

    protected $fillable = [
        'uses_allowed',
        'first_accessed_by_ip', 
        'first_accessed_at',
        'last_reaccessed_at',
        'last_reaccessed_by_ip'
    ];

    /**
     * Create a new limited-use url and save in the DB.
     *    
     * @param  Array $routeParams - includes: 'user', 'route_name', additional params 
     * @param  Integer $usesAllowed 
     * @param  Integer $expiresInMinutes 
     * @return String                  
     */
    public static function makeUrl(Array $routeParams, $usesAllowed = 1, $expiresInMinutes = null)
    {
        if(!Route::has($routeParams['route_name')) {
            throw new \Exception('route does not exist');
        }
        if(!key_exists('user', $routeParams)) {
            throw new \Exception('userId required in routeParams array');
        }
        
        $urlObj = new LimitedUseSignedUrl;
        
        // ***
        // *** Check if this works!!
        // ***
        if($old = $urlObj->exists($routeParams['user'], $routeParams['route_name'])) {
            $old->delete();
        }
        $urlObj->route_name = $routeParams['route_name'];
        $urlObj->user_id = $routeParams['user'];
        $urlObj->uses_allowed = $usesAllowed;

        if(!is_null($expiresInMinutes)) {
            $urlObj->expires_at = now()->addMinutes($expiresInMinutes);
        }

        $urlObj->created_by = Auth::user() ? Auth::user()->id : 0;
        $urlObj->key = $urlObj->generateKey();
        $urlObj->save();

        return $urlObj->generateUrl($routeParams);
    }

    public function generateKey()
    {
        return bin2hex(random_bytes(intdiv(50, 2)));
    }

    private function generateUrl(Array $routeParams)
    {
        return route($this->route_name, $routeParams) . 'limitedUseKey=' . $this->key;
    }

    private function exists($userId, $routeName)
    {
        if(!is_null($entry = self::where('user_id', $userId)
                                    ->where('route_name', $routeName)
                                    ->whereRaw('uses_allowed < total_uses')
                                    ->get()->first())) {
            return $entry;
        }
        return null;
    }
}
