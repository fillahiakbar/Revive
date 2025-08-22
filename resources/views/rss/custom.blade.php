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
            <a href="{{ $link }}">
                <img src="{{ asset('storage/' . $poster) }}" alt="{{ $animeName }}" width="600" /><br>
            </a>
            <strong>{{ $animeName }}</strong> - <em>{{ $batchName }}</em><br>
            <a href="{{ $link }}">{{ $link }}</a>
        ]]></description>
        <pubDate>{{ now()->format(DateTime::RSS) }}</pubDate>
    </item>
</channel>
</rss>
