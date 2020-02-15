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
        'uses_allowed',
        'key',
        'first_accessed_by_ip', 
        'first_accessed_at',
        'last_reaccessed_at',
        'last_reaccessed_by_ip'
    ];

    /**
     * Generates and returns the route needed
     * @param  Array|array $params 
     */
    public function generateUrl(Array $params = [])
    {
        $params['user_id'] = $this->user_id;

        return route($this->route_name, $params) . 'limitedUseKey=' . $this->key;
    }

}
