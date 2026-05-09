<?php
require_once 'YouTubeAPI.php';

$apiKey = "AIzaSyA1FL-x1S3HOYfSP_8IK1rdjUXsdRDT5nk";
$yt = new YouTubeDownloader($apiKey);

$videoUrl = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
$videoData = $yt->getVideoDetails($videoUrl);

if ($videoData) {
    echo "<h1>" . htmlspecialchars($videoData['title']) . "</h1>";
    echo "<img src='" . $videoData['thumbnail'] . "' width='300'>";
    echo "<p>" . nl2br(htmlspecialchars($videoData['description'])) . "</p>";
    
    // عرض الكلمات المفتاحية
    echo "<strong>Keywords:</strong> " . implode(', ', $videoData['keywords']);
} else {
    echo "تعذر جلب بيانات الفيديو. تأكد من الرابط أو مفتاح الـ API.";
}