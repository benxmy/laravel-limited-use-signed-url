<?php

namespace Benxmy\SingleUseSignedUrl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SingleUseSignedUrl extends Model
{
    protected $table = 'single_use_signed_urls';

    protected $dates = [
        'accessed_at', 
        'reaccessed_at',
        'expires_at',
    ];

    protected $fillable = [
        'accessed_by_ip', 
        'accessed_at',
        'reaccessed_at',
        'reaccessed_by_ip'
    ];

    /**
     * Create a new single-use url and save in the DB.
     * 
     * @param  String $routeName        
     * @param  String $userId           
     * @param  Integer $expiresInMinutes 
     * @return String                  
     */
    public static function makeUrl($routeName, Array $routeParams, $expiresInMinutes = null)
    {
        if(!Route::has($routeName)) {
            throw new \Exception('route does not exist');
        }
        if(!key_exists('user', $routeParams)) {
            throw new \Exception('userId required in routeParams array');
        }
        
        $urlObj = new SingleUseSignedUrl;
        if($old = $urlObj->exists($routeParams['user'])) {
            $old->delete();
        }
        $urlObj->route_name = $routeName;
        $urlObj->user_id = $routeParams['user'];

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
        return route($this->route_name, $routeParams) . '?singleUseKey=' . $this->key;
    }

    private function exists($userId)
    {
        if(!is_null($entry = self::where('user_id', $userId)->whereNull('accessed_at')->get()->first())) {
            return $entry;
        }
        return null;
    }
}
