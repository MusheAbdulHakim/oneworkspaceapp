<?php

namespace Modules\LandingPage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Pixel extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'pixel_id',
    ];

    protected $table = 'landingpage_pixels';

    protected static function newFactory()
    {
        return \Modules\LandingPage\Database\factories\PixelFactory::new();
    }

    public static function getPixelScript()
    {
        $pixelScript = [];

        if(Schema::hasTable('landingpage_pixels'))
        {
            $pixels = Pixel::get();
            foreach ($pixels as $pixel) {


                if (!$pixel->disabled) {
                    $pixelScript[] = Pixel::pixelSourceCode($pixel['platform'], $pixel['pixel_id']);
                }
            }
        }

        return $pixelScript;
    }

    public static function  pixelSourceCode($platform, $pixelId)
    {
    	// Facebook Pixel script
    	if ($platform === 'facebook') {
			$script = "
				<script>
					!function(f,b,e,v,n,t,s)
					{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
					n.callMethod.apply(n,arguments):n.queue.push(arguments)};
					if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
					n.queue=[];t=b.createElement(e);t.async=!0;
					t.src=v;s=b.getElementsByTagName(e)[0];
					s.parentNode.insertBefore(t,s)}(window, document,'script',
					'https://connect.facebook.net/en_US/fbevents.js');
					fbq('init', '%s');
					fbq('track', 'PageView');
				</script>

				<noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=%d&ev=PageView&noscript=1'/></noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Twitter Pixel script
    	if ($platform === 'twitter') {
			$script = "
            <script>
            !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
            },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
            a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
            twq('config','%s');
            </script>
			";

			return sprintf($script, $pixelId);
		}


		// Linkedin Pixel script
    	if ($platform === 'linkedin') {
			$script = "
				<script type='text/javascript'>
                    _linkedin_data_partner_id = %d;
                </script>
                <script type='text/javascript'>
                    (function () {
                        var s = document.getElementsByTagName('script')[0];
                        var b = document.createElement('script');
                        b.type = 'text/javascript';
                        b.async = true;
                        b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
                        s.parentNode.insertBefore(b, s);
                    })();
                </script>
                <noscript><img height='1' width='1' style='display:none;' alt='' src='https://dc.ads.linkedin.com/collect/?pid=%d&fmt=gif'/></noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Pinterest Pixel script
    	if ($platform === 'pinterest') {
			$script = "
            <!-- Pinterest Tag -->
            <script>
            !function(e){if(!window.pintrk){window.pintrk = function () {
            window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
              n=window.pintrk;n.queue=[],n.version='3.0';var
              t=document.createElement('script');t.async=!0,t.src=e;var
              r=document.getElementsByTagName('script')[0];
              r.parentNode.insertBefore(t,r)}}('https://s.pinimg.com/ct/core.js');
            pintrk('load', '%s');
            pintrk('page');
            </script>
            <noscript>
            <img height='1' width='1' style='display:none;' alt=''
              src='https://ct.pinterest.com/v3/?event=init&tid=2613174167631&pd[em]=<hashed_email_address>&noscript=1' />
            </noscript>
            <!-- end Pinterest Tag -->

			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Quora Pixel script
    	if ($platform === 'quora') {
			$script = "
               <script>
                    !function (q, e, v, n, t, s) {
                        if (q.qp) return;
                        n = q.qp = function () {
                            n.qp ? n.qp.apply(n, arguments) : n.queue.push(arguments);
                        };
                        n.queue = [];
                        t = document.createElement(e);
                        t.async = !0;
                        t.src = v;
                        s = document.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t, s);
                    }(window, 'script', 'https://a.quora.com/qevents.js');
                    qp('init', %s);
                    qp('track', 'ViewContent');
                </script>

                <noscript><img height='1' width='1' style='display:none' src='https://q.quora.com/_/ad/%d/pixel?tag=ViewContent&noscript=1'/></noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}



		// Bing Pixel script
    	if ($platform === 'bing') {
			$script = '
				<script>
				(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[] ,f=function(){var o={ti:"%d"}; o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")} ,n=d.createElement(t),n.src=r,n.async=1,n.onload=n .onreadystatechange=function() {var s=this.readyState;s &&s!=="loaded"&& s!=="complete"||(f(),n.onload=n. onreadystatechange=null)},i= d.getElementsByTagName(t)[0],i. parentNode.insertBefore(n,i)})(window,document,"script"," //bat.bing.com/bat.js","uetq");
				</script>
				<noscript><img src="//bat.bing.com/action/0?ti=%d&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
			';

			return sprintf($script, $pixelId, $pixelId);
		}



		// Google adwords Pixel script
    	if ($platform === 'google-adwords') {
			$script = "
				<script type='text/javascript'>

				var google_conversion_id = '%s';
				var google_custom_params = window.google_tag_params;
				var google_remarketing_only = true;

				</script>
				<script type='text/javascript' src='//www.googleadservices.com/pagead/conversion.js'>
				</script>
				<noscript>
				<div style='display:inline;'>
				<img height='1' width='1' style='border-style:none;' alt='' src='//googleads.g.doubleclick.net/pagead/viewthroughconversion/%s/?guid=ON&amp;script=0'/>
				</div>
				</noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Google tag manager Pixel script
    	if ($platform === 'google-analytics') {
			$script = "
				<script async src='https://www.googletagmanager.com/gtag/js?id=%s'></script>
				<script>

				  window.dataLayer = window.dataLayer || [];

				  function gtag(){dataLayer.push(arguments);}

				  gtag('js', new Date());

				  gtag('config', '%s');

				</script>
			";

			return sprintf($script, $pixelId, $pixelId);
		}

        //snapchat
        if ($platform === 'snapchat') {
			$script = " <script type='text/javascript'>
            (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
            {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
            a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
            r.src=n;var u=t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r,u);})(window,document,
            'https://sc-static.net/scevent.min.js');

            snaptr('init', '%s', {
            'user_email': '__INSERT_USER_EMAIL__'
            });

            snaptr('track', 'PAGE_VIEW');

            </script>";
			return sprintf($script, $pixelId, $pixelId);
		}

        //tiktok
        if ($platform === 'tiktok') {
			$script = " <script>
            !function (w, d, t) {
              w.TiktokAnalyticsObject=t;
              var ttq=w[t]=w[t]||[];
              ttq.methods=['page','track','identify','instances','debug','on','off','once','ready','alias','group','enableCookie','disableCookie'],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
              for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;
             n++)ttq.setAndDefer(e,ttq.methods[n]);
             return e},ttq.load=function(e,n){var i='https://analytics.tiktok.com/i18n/pixel/events.js';
            ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};
            var o=document.createElement('script');
            o.type='text/javascript',o.async=!0,o.src=i+'?sdkid='+e+'&lib='+t;
            var a=document.getElementsByTagName('script')[0];
            a.parentNode.insertBefore(o,a)};

              ttq.load('%s');
              ttq.page();
            }(window, document, 'ttq');
            </script>";

			return sprintf($script, $pixelId, $pixelId);
		}




    }
}
