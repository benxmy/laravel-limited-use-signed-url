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
     * @param  Array $urlData - ['user_id', 'route_name', 'expires_in_minutes', 'uses_allowed'
     * @param  Array $extraParams - extra route parameters 
     * @return String                  
     */
    public static function makeUrl(Array $urlData, Array $extraParams) 
    {
        if(!Route::has($urlData['route_name'])) {
            throw new \Exception('route does not exist');
        }
        if(!key_exists('user', $urlData)) {
            throw new \Exception('user_id required in urlData array');
        }
        
        $urlObj = new LimitedUseSignedUrl;
        
        // ***
        // *** Check if this works!!
        // ***
        if($old = $urlObj->exists($urlData['user'], $urlData['route_name'])) {
            $old->delete();
        }
        $urlObj->route_name = $urlData['route_name'];
        $urlObj->user_id = $urlData['user_id'];
        $urlObj->uses_allowed = $urlData['uses_allowed'];

        if(!is_null($urlData['expires_in_minutes'])) {
            $urlObj->expires_at = now()->addMinutes($urlData['expires_in_minutes']);
        }

        $urlObj->created_by = Auth::user() ? Auth::user()->id : 0;
        $urlObj->key = $urlObj->generateKey();
        $urlObj->save();

        return $urlObj->generateUrl($urlData);
    }

    public function generateKey()
    {
        return bin2hex(random_bytes(intdiv(50, 2)));
    }

    private function generateUrl(Array $urlData)
    {
        return route($this->route_name, $extraParams) . 'limitedUseKey=' . $this->key;
    }

    private function exists($urlData['user_id'], $urlData['route_name'])
    {
        if(!is_null($entry = self::where('user_id', $urlData['user_id'])
                                    ->where('route_name', $urlData['route_name'])
                                    ->whereRaw('uses_allowed < total_uses')
                                    ->get()->first())) {
            return $entry;
        }
        return null;
    }
}
