<?php

namespace App\Jobs;

use App\Models\UrlBookmark;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateBookmarkImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public UrlBookmark $bookmark)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bookmark = $this->bookmark;
        $base64_image = $this->snap($bookmark->url, env('PAGESPEED_KEY'));

        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = Str::random(10).'.png';
        Storage::disk('local')->put("bookmarks/$imageName", base64_decode($file_data));
        $bookmark->update([
            'image' => $imageName,
        ]);
    }

    /**
     * Capture web screenshot using google api.
     *
     */
    function snap($url, $key)
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            $curl_init = curl_init("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={$url}&key=$key&screenshot=true");
            curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl_init);
            curl_close($curl_init);
            $googlepsdata = json_decode($response, true);
            $snap = $googlepsdata['lighthouseResult']['fullPageScreenshot']['screenshot']['data'];
            return str_replace(['_', '-'], ['/', '+'], $snap);
        } else {
            return false;
        }
    }
}
