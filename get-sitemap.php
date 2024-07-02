<?php
function getFileRowCount($filename)
{
    $file = fopen($filename, "r");
    $rowCount = 0;

    while (!feof($file)) {
        fgets($file);
        $rowCount++;
    }

    fclose($file);

    return $rowCount;
}
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$fullUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (isset($fullUrl)) {
    $parsedUrl = parse_url($fullUrl);
    $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : '';
    $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
    $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
    $baseUrl = $scheme . "://" . $host . $path;
    $urlAsli = str_replace("get-sitemap.php", "", $baseUrl);
    $robotsTxt = "User-agent: *" . PHP_EOL;
    $robotsTxt .= "Allow: /" . PHP_EOL;
    $robotsTxt .= "Sitemap: " . $urlAsli . "sitemap.xml" . PHP_EOL;
    file_put_contents('robots.txt', $robotsTxt);
    $judulFile = "google.txt";
    $jumlahBaris = getFileRowCount($judulFile);
    $sitemapFile = fopen("sitemap.xml", "w");
    fwrite($sitemapFile, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
    fwrite($sitemapFile, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);
    $fileLines = file($judulFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($fileLines as $index => $judul) {
        $sitemapLink = $urlAsli . '?google=' . urlencode($judul);
        fwrite($sitemapFile, '  <url>' . PHP_EOL);
        fwrite($sitemapFile, '    <loc>' . $sitemapLink . '</loc>' . PHP_EOL);
        date_default_timezone_set('Asia/Jakarta');
        $currentTime = date('Y-m-d\TH:i:sP');
        fwrite($sitemapFile, '    <lastmod>' . $currentTime . '</lastmod>' . PHP_EOL);
        fwrite($sitemapFile, '    <changefreq>daily</changefreq>' . PHP_EOL);
        fwrite($sitemapFile, '  </url>' . PHP_EOL);
    }
    fwrite($sitemapFile, '</urlset>' . PHP_EOL);
    fclose($sitemapFile);   
    echo "SITEMAP DONE CREATE!";
} else {
    echo "URL saat ini tidak didefinisikan.";
}

?>
