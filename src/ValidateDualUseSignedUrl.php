<?php

namespace Benxmy\DualUseSignedUrl;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class ValidateDualUseSignedUrl
{
    public function handle($request, Closure $next)
    {
        $dualUseSignedUrl = DualUseSignedUrl::where('key', $request->dualUseKey)
            ->whereNull('reaccessed_at')
            ->get()
            ->first();

        if(!$dualUseSignedUrl) {
            throw new InvalidSignatureException;
        }
        if($request->route()->getName() != $dualUseSignedUrl->route_name) {
            throw new InvalidSignatureException;
        }
        if(get_class($request->user)) {
            if($request->user->id != $dualUseSignedUrl->user_id) {
                throw new InvalidSignatureException;
            }
        } else {
            if($request->user != $dualUseSignedUrl->user_id) {
                throw new InvalidSignatureException;
            }
        }

        if($dualUseSignedUrl->expires_at && now() > $dualUseSignedUrl->expires_at) {
            throw new InvalidSignatureException;
        }

        if(!$dualUseSignedUrl->accessed_at) {
            $dualUseSignedUrl->update([
                'accessed_by_ip' => $request->ip(),
                'accessed_at' => now(),
            ]);
        }
        else {
            $dualUseSignedUrl->update([
                'reaccessed_by_ip' => $request->ip(),
                'reaccessed_at' => now(),
            ]);            
        }

        return $next($request);
    }
}
