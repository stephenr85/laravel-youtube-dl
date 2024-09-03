<?php

namespace Tests\Unit;

use Orchestra\Testbench\Bootstrap\LoadEnvironmentVariables;
use Rushing\YouTubeDl\Services\YouTubeDlService;
use Orchestra\Testbench\TestCase;

class YouTubeDlServiceTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/../workbench');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);
    }

    protected function getPackageProviders($app)
    {
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        return [
            'Rushing\YouTubeDl\ServiceProvider',
        ];
    }

    public function testGetVideoInfoReturnsExpectedData()
    {
        $ytdl = app(YouTubeDlService::class);
        // https://www.youtube.com/watch?v=yYTSdlOdkn0
        $info = $ytdl->dlVideoInfo('yYTSdlOdkn0');
        //$this->assertStringContainsString('decaf', $text);
        $this->assertStringContainsStringIgnoringCase('decaf', $info->title, 'Expected content not found in title');
    }

    public function testGetVideoCaptionsReturnsExpectedData()
    {
        $ytdl = app(YouTubeDlService::class);
        // https://www.youtube.com/watch?v=yYTSdlOdkn0
        $captions = $ytdl->dlVideoCaptions('yYTSdlOdkn0');
        $text = $captions->content('vtt');
        //$this->assertStringContainsString('decaf', $text);
        $this->assertStringContainsStringIgnoringCase('decaf', $text, 'Expected content not found in captions');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
