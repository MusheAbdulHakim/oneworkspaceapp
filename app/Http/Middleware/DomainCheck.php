<?php

namespace App\Http\Middleware;

use App\Models\WorkSpace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(file_exists(storage_path() . "/installed"))
        {
        // custom domain code

            $local = parse_url(config('app.url'))['host'];
            // Get the request host
            $remote = request()->getHost();
            $remote = str_replace('www.', '', $remote);
            if($local != $remote)
            {
                $workSpace = WorkSpace::where('domain',$remote)->orwhere('subdomain',$remote)->first();
                if($workSpace && ($workSpace->enable_domain == 'on'))
                {
                    if( ($workSpace->domain_type == 'custom') && ( $workSpace->domain != $remote ))
                    {
                        abort('404');
                    }
                    else if(($workSpace->domain_type == 'subdomain')  && ( $workSpace->subdomain != $remote ))
                    {
                        abort('404');
                    }
                }
                else
                {
                    abort('404');
                }
            }
        }

        return $next($request);
    }
}
