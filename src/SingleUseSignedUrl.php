<?php

namespace Intellow\SingleUseSignedUrl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SingleUseSignedUrl extends Model
{
    protected $table = 'single_use_signed_urls';
    protected $dates = [
        'accessed_at', 'expires_at',
    ];
    protected $fillable = ['accessed_by_ip', 'accessed_at'];

    public function make($routeName, $userId, $expiresInMinutes = null)
    {
        if(!Route::has($routeName)) {
            throw new \Exception('route does not exist');
        }
        if($old = $this->exists($userId)) {
            $old->delete();
        }
        $this->route_name = $routeName;
        $this->user_id = $userId;
        if(!is_null($expiresInMinutes)) {
            $this->expires_at = now()->addMinutes($expiresInMinutes);
        }
        $this->created_by = Auth::user() ? Auth::user()->id : 0;
        $this->key = $this->generateKey();
        $this->save();

        return $this->generateUrl();
    }

    public function generateKey()
    {
        return bin2hex(random_bytes(intdiv(50, 2)));
    }

    private function generateUrl()
    {
        return route($this->route_name, $this->user_id) . '?singleUseKey=' . $this->key;
    }

    private function exists($userId)
    {
        if(!is_null($entry = self::where('user_id', $userId)->whereNull('accessed_at')->get()->first())) {
            return $entry;
        }
        return null;
    }
}
