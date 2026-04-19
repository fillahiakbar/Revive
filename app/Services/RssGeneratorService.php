<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class RssGeneratorService
{
    private static string $rssFileName = 'custom-feeds.xml';

    /**
     * Load existing feeds from index file
     */
    public static function loadExistingFeeds(): array
    {
        $indexPath = public_path('rss/index.json');
        
        if (!File::exists($indexPath)) {
            return [];
        }

        $feedsData = json_decode(File::get($indexPath), true) ?? [];
        
        // Convert back to objects with Carbon dates and ensure 'id' exists
        return collect($feedsData)->map(function ($feed) {
            $feed['created_at'] = Carbon::parse($feed['created_at']);
            // Add 'id' if it doesn't exist (for backward compatibility)
            if (!isset($feed['id'])) {
                $feed['id'] = uniqid('feed_');
            }
            return $feed;
        })->toArray();
    }

    /**
     * Update index file with all feeds data and regenerate XML
     */
    public static function updateFromFeeds(array $feeds): void
    {
        $indexPath = public_path('rss/index.json');
        
        File::ensureDirectoryExists(public_path('rss'));

        // Convert feeds to storable format
        $feedsData = collect($feeds)->map(function ($feed) {
            return [
                'anime_name' => collect($feed)->get('anime_name') ?? '',
                'batch_name' => collect($feed)->get('batch_name') ?? '',
                'link' => collect($feed)->get('link') ?? '',
                'poster' => collect($feed)->get('poster') ?? '',
                'created_at' => ($feed['created_at'] ?? now()) instanceof Carbon 
                                ? $feed['created_at']->toISOString() 
                                : Carbon::parse($feed['created_at'])->toISOString(),
                'id' => collect($feed)->get('id') ?? uniqid('feed_'),
            ];
        })->toArray();

        File::put($indexPath, json_encode($feedsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Generate complete RSS XML
        $rssContent = self::generateRssXml($feeds);

        // Save RSS file
        File::put(public_path('rss/' . self::$rssFileName), $rssContent);
    }

    /**
     * Generate complete RSS XML with all feed items
     */
    public static function generateRssXml(array $feeds): string
    {
        $rssItems = '';
        
        foreach ($feeds as $feed) {
            $createdAt = $feed['created_at'] ?? now();
            $pubDate = ($createdAt instanceof Carbon ? $createdAt : Carbon::parse($createdAt))->format('D, d M Y H:i:s T');
            $posterUrl = !empty($feed['poster']) ? asset('storage/' . $feed['poster']) : '';
            
            // Ensure 'id' exists for GUID
            $guid = !empty($feed['id']) ? $feed['id'] : uniqid('feed_');
            $animeName = $feed['anime_name'] ?? '';
            $batchName = $feed['batch_name'] ?? '';
            $link = $feed['link'] ?? '';
            
            $rssItems .= "
        <item>
            <title><![CDATA[{$animeName} - {$batchName}]]></title>
            <link>{$link}</link>
            <description><![CDATA[
                " . ($posterUrl ? "<img src=\"{$posterUrl}\" alt=\"{$animeName}\" style=\"max-width: 300px; height: auto;\"><br>" : "") . "
                {$batchName}<br>
                <a href=\"{$link}\" target=\"_blank\">{$link}</a>
            ]]></description>
            <pubDate>{$pubDate}</pubDate>
            <guid isPermaLink=\"false\">{$guid}</guid>
            " . ($posterUrl ? "<enclosure url=\"{$posterUrl}\" type=\"image/jpeg\" length=\"0\" />" : "") . "
        </item>";
        }

        $channelTitle = 'Custom Anime RSS Feed';
        $channelDescription = 'RSS Feed for latest anime batch updates';
        $channelLink = url('/');
        $lastBuildDate = now()->format('D, d M Y H:i:s T');

        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
    <channel>
        <title><![CDATA[{$channelTitle}]]></title>
        <description><![CDATA[{$channelDescription}]]></description>
        <link>{$channelLink}</link>
        <atom:link href=\"" . url('rss/' . self::$rssFileName) . "\" rel=\"self\" type=\"application/rss+xml\" />
        <language>en-US</language>
        <lastBuildDate>{$lastBuildDate}</lastBuildDate>
        <generator>Laravel Filament Custom RSS Generator</generator>
        {$rssItems}
    </channel>
</rss>";
    }
}
