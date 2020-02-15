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
        'user_id',
        'route_name',
        'first_accessed_by_ip', 
        'first_accessed_at',
        'last_reaccessed_at',
        'last_reaccessed_by_ip'
    ];

    /**
     * Build up the data from the info passed
     * 
     * @param Array $urlData 
     */
    public function setUpData(Array $urlData) 
    {
        $this->validateUrl($urlData);

        $this->setExpiration($urlData);

        $this->setUsesAllowed($urlData);

        $this->key = $this->generateKey();

        $this->created_by = auth()->user() ? auth()->user()->id : 0;

        $this->checkExistingUrls();

        return true;
    }

    /**
     * Generates and returns the route needed
     * @param  Array|array $params 
     */
    public function generateUrl(Array $params = [])
    {
        $params['user'] = $this->user_id;

        return route($this->route_name, $params) . '?limitedUseKey=' . $this->key;
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
    private function setExpiration(Array $urlData)
    {
        if(array_key_exists('expires_in_minutes', $urlData)) {
            $minutes = $urlData['expires_in_minutes'];
        }
        else {
            $minutes = config('limited-use-urls.expires_in_minutes');
        }
        $this->expires_at = now()->addMinutes($minutes);
   }

   /**
    * Gets the number of uses allowed for the generated url. Uses config if no data is passed in $urlData
    * @param  Array  $urlData 
    */
    private function setUsesAllowed(Array $urlData) 
    {
        if(array_key_exists('uses_allowed', $urlData)) {
            $uses = $urlData['uses_allowed'];
        }
        else {
            $uses = config('limited-use-urls.uses_allowed');
        }
        $this->uses_allowed = $uses;
    }

    /**
     * Checks if a url already exists for this user and route requested. If so, delete the old one 
     * @param  LimitedUseSignedUrl $urlObj The new url to be created
     */
    private function checkExistingUrls()
    {
        if(!is_null($entry = LimitedUseSignedUrl::where('user_id', $this->user_id)
                                    ->where('route_name', $this->route_name)
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
