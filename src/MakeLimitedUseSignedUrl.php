<?php
namespace Benxmy\LimitedUseSignedUrl;

use LimitedUseSignedUrl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MakeLimitedUseSignedUrl extends Model
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
    	$this->validateUrl($urlData);
        
        $limitedUseUrl = new LimitedUseSignedUrl([
        	'route_name' => $urlData['route_name'],
        	'user_id' => auth()->user()->id,
        	'expires_at' => $this->getExpiration($urlData),
        	'users_allowed' => $this->getUsesAllowed($urlData),
        	'key' => $this->generateKey(),
        	'created_by' => auth()->user() ? auth()->user()->id : 0;
        ]);

        $this->checkExistingUrls($limitedUseUrl);
        
        $limitedUseUrl->save();

        return $limitedUseUrl->generateUrl($extraParams);
    }

    /**
     * Validates that the data passed is valid
     * @param  Array  $urlData 
     */
    private function validateUrl(Array $urlData) 
    {
    	if(!Route::has($urlData['route_name'])) {
            throw new \Exception('route does not exist');
        }
        if(!key_exists('user_id', $urlData)) {
            throw new \Exception('user_id required in urlData array');
        }
    }

    /**
     * Gets the appropriate expiration datetime for the url. Uses config if no data passed in $urlData
     * @param  Array  $urlData 
     * @return DateTime   
     */
    private function getExpiration(Array $urlData)
    {
    	if(array_key_exists('expires_in_minutes', $urlData)) {
    		$minutes = $urlData['expires_in_minutes'];
    	}
    	else {
    		$minutes = config(['limited-use-urls.expires_in_minutes']);
    	}
    	return now()->addMinutes($minutes);
   }

   /**
    * Gets the number of uses allowed for the generated url. Uses config if no data is passed in $urlData
    * @param  Array  $urlData 
    */
    private function getUsesAllowed(Array $urlData) 
    {
    	if(array_key_exists('uses_allowed', $urlData)) {
    		return $urlData['uses_allowed'];
    	}
    	else {
    		return config(['uses_allowed']);
    	}
    }

    /**
     * Checks if a url already exists for this user and route requested. If so, delete the old one 
     * @param  LimitedUseSignedUrl $urlObj The new url to be created
     */
    private function checkExistingUrls(LimitedUseSignedUrl $urlObj)
    {
        if(!is_null($entry = LimitedUseSignedUrl::where('user_id', $urlObj->user_id)
                                    ->where('route_name', $urlObj->route_name)
                                    ->whereRaw('uses_allowed < total_uses')
                                    ->get()->first())) {
            $entry->delete();
        }
        return null;
    }

    /**
     * Generates a key for the signed Url
     */
    private function generateKey()
    {
        return bin2hex(random_bytes(intdiv(50, 2)));
    }
}
