<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_YouTube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YouTubeUploadController extends Controller
{
    public function uploadVideo(Request $request)
    {
        $client = $this->configureGoogleClient();

        if (!$this->authenticateClient($client)) {
            return redirect($client->createAuthUrl());
        }

        try {
            $videoId = $this->uploadToYouTube($client, $request);
            Log::info("YouTube video uploaded: " . $videoId);
            return response()->json(['success' => true, 'video_id' => $videoId]);
        } catch (\Exception $e) {
            Log::error('YouTube Upload Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function configureGoogleClient()
    {
        return tap(new Google_Client(), function ($client) {
            $client->setAuthConfig(storage_path('app/yt_client_secrets.json'));
            $client->addScope(Google_Service_YouTube::YOUTUBE_UPLOAD);
            $client->setRedirectUri(route('oauth2callback'));
        });
    }

    private function authenticateClient(Google_Client $client)
    {
        if (session()->has('access_token')) {
            $client->setAccessToken(session('access_token'));

            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                session(['access_token' => $client->getAccessToken()]);
            }

            return true;
        }

        return false;
    }

    private function uploadToYouTube(Google_Client $client, Request $request)
    {
        $youtube = new Google_Service_YouTube($client);
        $video = $this->createVideoResource($request);

        $client->setDefer(true);
        $insertRequest = $youtube->videos->insert('status,snippet', $video);

        $media = new \Google_Http_MediaFileUpload($client, $insertRequest, 'video/*', null, true, 1 * 1024 * 1024);
        $media->setFileSize(filesize($request->file('video')->path()));

        $status = false;
        $handle = fopen($request->file('video')->path(), "rb");

        while (!$status && !feof($handle)) {
            $status = $media->nextChunk(fread($handle, 1 * 1024 * 1024));
        }

        fclose($handle);
        $client->setDefer(false);

        return $status['id'];
    }

    private function createVideoResource(Request $request)
    {
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($request->input('title'));
        $snippet->setDescription($request->input('description'));
        $snippet->setTags(explode(',', $request->input('tags', '')));
        $snippet->setCategoryId('22'); // Example: Category 22 is for People & Blogs

        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus('public'); // Options: 'public', 'private', 'unlisted'

        // Mark the video as a YouTube Short
        if ($request->input('is_short')) {
            $status->setSelfDeclaredMadeForKids(true);
            $snippet->setShortsCategory('shorts'); // Indicate this is a YouTube Short
        }

        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        return $video;
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->configureGoogleClient();
        $client->authenticate($request->input('code'));
        session(['access_token' => $client->getAccessToken()]);

        return redirect()->route('upload.video');
    }
}
