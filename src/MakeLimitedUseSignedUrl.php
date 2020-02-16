<?php
namespace Mindlarkdev\LimitedUseSignedUrl;

use Mindlarkdev\LimitedUseSignedUrl\LimitedUseSignedUrl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use LimitedUseSignedUrlModel;

class MakeLimitedUseSignedUrl
{
	/**
     * Create a new limited-use url and save in the DB.
     *    
     * @param  Array $urlData - ['user_id', 'route_name', 'expires_in_minutes' (optional), 'uses_allowed' (optional)]
     * @param  Array $extraParams - extra route parameters 
     * @return String                  
     */
    public static function makeUrl(Array $urlData, Array $extraParams = []) 
    {        
        $limitedUseUrl = new LimitedUseSignedUrl([
        	'route_name' => $urlData['route_name'],
        	'user_id' => auth()->user()->id,
        ]);

        if(!$limitedUseUrl->setUpData($urlData)) {
            throw new \Exception('Something went wrong. I could not create url record in model.');
        }
        
        $limitedUseUrl->save();

        return $limitedUseUrl->generateUrl($extraParams);
    }
}
