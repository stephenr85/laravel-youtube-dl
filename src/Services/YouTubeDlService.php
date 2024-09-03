<?php

namespace Rushing\YouTubeDl\Services;

use Done\Subtitles\Subtitles;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use YoutubeDl\Entity\Video;
use YoutubeDl\Options as DownloadOptions;
use YoutubeDl\YoutubeDl;

// https://github.com/norkunas/youtube-dl-php
class YouTubeDlService
{
    public function __construct(public YoutubeDl $dl)
    {
        $this->dl->setBinPath(config('youtube-dl.bin_path'));
    }

    public function deleteVideoStorage(string $videoId)
    {
        $path = $this->getVideoStoragePath($videoId);
        if(file_exists($path)) {
            Storage::deleteDirectory($path);
        }
    }

    public function getVideoStoragePath(string $videoId, string $subPath = null) : string
    {
        $base = storage_path("app/youtube/$videoId");
        return rtrim( $base, '/' ) . '/' . $subPath;
    }

    public function getVideoUrl(string $videoId) : string
    {
        return "https://www.youtube.com/watch?v=$videoId";
    }

    public function getVideoIdFromUrl(string $videoUrl) : string
    {
        // https://youtu.be/IszQ2JR3Olc
        // Check if it's a youtu.be URL
        if(strpos($videoUrl, 'youtu.be') !== false) {
            $urlParts = parse_url($videoUrl);
            return Arr::last(explode('/', $urlParts['path']));
        }
        // Default youtube.com/watch?v=IszQ2JR3Olc
        else {
            $urlParts = parse_url($videoUrl);
            parse_str($urlParts['query'], $query);
            return $query['v'];
        }
    }

    public function getDownloadOptions(string $videoId) : DownloadOptions
    {
        return DownloadOptions::create()
            ->url(strpos($videoId, 'http') === 0 ? $videoId : $this->getVideoUrl($videoId))
            // ->allSubs(true)
            ->proxy(config('youtube-dl.proxy'))
            ->writeAutoSub(true)
            ->writeSub(true)
            ->subLang(['en'])
            ->subFormat('vtt/best')
            ->downloadPath($this->getVideoStoragePath($videoId))
            ->output('%(id)s.%(ext)s');
    }

    public function dlVideo(string $videoId) : Video
    {
        $options = $this->getDownloadOptions($videoId);
        $dl = $this->dl->download($options);
        $video = Arr::first($dl->getVideos())?->toJson();
        return $video;
    }

    public function dlVideoInfo(string $videoId) : Video
    {
        // look for cached video info
        // if(Cache::has("youtube-video-$videoId")) {
        //     return Cache::get("youtube-video-$videoId");
        // }
        // NOTE: youtube-dl has its own caching mechanism, and trying to cache the Video model throws an error

        $options = $this->getDownloadOptions($videoId);
        $options->skipDownload(true);

        $dl = $this->dl->download($options);
        $video = json_decode(Arr::first($dl->getVideos())?->toJson());

        // Cache::put("youtube-video-$videoId", $video, now()->addDays(30));

        return $video;
    }

    public function dlVideoCaptions($videoId) : ?Subtitles
    {
        $subtitlesFilePath = $this->getVideoStoragePath($videoId, "$videoId.en.vtt");
        if(!file_exists($subtitlesFilePath)) {
            $info = $this->dlVideoInfo($videoId);
        }
        if(file_exists($subtitlesFilePath)) {
            return Subtitles::loadFromFile($subtitlesFilePath);
        }
        return null;
    }

    public static function make()
    {
        return app(self::class);
    }
}
