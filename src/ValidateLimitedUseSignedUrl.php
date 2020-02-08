<?php

namespace Benxmy\limitedUseSignedUrl;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class ValidateLimitedUseSignedUrl
{
    public function handle($request, Closure $next)
    {
        $limitedUseSignedUrl = limitedUseSignedUrl::where('key', $request->limitedUseKey)
            ->whereRaw('uses_allowed < total_uses')
            ->get()
            ->first();

        if(!$limitedUseSignedUrl) {
            throw new InvalidSignatureException;
        }
        if($request->route()->getName() != $limitedUseSignedUrl->route_name) {
            throw new InvalidSignatureException;
        }
        if(get_class($request->user)) {
            if($request->user->id != $limitedUseSignedUrl->user_id) {
                throw new InvalidSignatureException;
            }
        } else {
            if($request->user != $limitedUseSignedUrl->user_id) {
                throw new InvalidSignatureException;
            }
        }

        if($limitedUseSignedUrl->expires_at && now() > $limitedUseSignedUrl->expires_at) {
            throw new InvalidSignatureException;
        }

        if(!$limitedUseSignedUrl->accessed_at) {
            $limitedUseSignedUrl->update([
                'accessed_by_ip' => $request->ip(),
                'accessed_at' => now(),
            ]);
            $limitedUseSignedUrl->increment('total_uses');
        }
        else {
            $limitedUseSignedUrl->update([
                'last_accessed_by_ip' => $request->ip(),
                'last_accessed_at' => now(),
            ]);
            $limitedUseSignedUrl->increment('total_uses');
        }

        return $next($request);
    }
}
