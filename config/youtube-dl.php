<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DOWNLOADER BIN PATH
    |--------------------------------------------------------------------------
    |
    | Here you may specify the path to the youtube-dl or yt-dlp binary.
    |
    */

    'bin_path' => env('YOUTUBE_DL_BIN_PATH', '/usr/local/bin/youtube-dl'),

    /*
    |--------------------------------------------------------------------------
    | PROXY
    |--------------------------------------------------------------------------
    |
    | Here you may specify your proxy per Network Options.
    | https://github.com/ytdl-org/youtube-dl#options
    |
    */

    'proxy' => env('YOUTUBE_DL_PROXY', ''),
];
