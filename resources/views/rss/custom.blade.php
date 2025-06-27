<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
<channel>
    <title>Custom Anime Feed</title>
    <link>{{ $link }}</link>
    <description>Feed Manual via Panel</description>

    <item>
        <title>{{ $animeName }} - {{ $batchName }}</title>
        <link>{{ $link }}</link>
        <description><![CDATA[
            <img src="{{ asset('storage/' . $poster) }}" alt="poster" /><br>
            Anime: <strong>{{ $animeName }}</strong><br>
            Batch: <strong>{{ $batchName }}</strong>
        ]]></description>
        <pubDate>{{ now()->format(DateTime::RSS) }}</pubDate>
    </item>
</channel>
</rss>
