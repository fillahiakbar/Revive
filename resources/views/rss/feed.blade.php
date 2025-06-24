<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
<channel>
    <title>{{ $anime->title }}</title>
    <link>{{ url('/anime/' . $anime->slug) }}</link>
    <image>
        <url>{{ $anime->poster ?? asset('images/default-poster.jpg') }}</url>
        <title>{{ $anime->title }}</title>
        <link>{{ url('/anime/' . $anime->slug) }}</link>
    </image>

    @foreach ($anime->batches as $batch)
        @foreach ($batch->batchLinks as $link)
            <item>
                <title>{{ $anime->title }} - {{ $batch->name ?? 'Batch' }} ({{ $link->resolution ?? 'Unknown' }})</title>
                <link>{{ $link->url_gdrive ?? $link->url_mega ?? $link->url_torrent ?? '#' }}</link>
                <guid>{{ $link->url_gdrive ?? $link->url_mega ?? $link->url_torrent ?? '#' }}</guid>
                <description><![CDATA[
                    <strong>Quality:</strong> {{ $link->resolution ?? 'N/A' }}<br>
                    <strong>Torrent:</strong> {{ $link->url_torrent ?? '-' }}<br>
                    <strong>Mega:</strong> {{ $link->url_mega ?? '-' }}<br>
                    <strong>GDrive:</strong> {{ $link->url_gdrive ?? '-' }}<br>
                ]]></description>
                <pubDate>{{ $batch->created_at->toRssString() }}</pubDate>
            </item>
        @endforeach
    @endforeach
</channel>
</rss>
