<?php

class YouTubeDownloader {
    private $apiKey;

    public function __construct($key) {
        $this->apiKey = $key;
    }

    // دالة لاستخراج ID الفيديو من الرابط
    private function getVideoId($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $vars);
        if (isset($vars['v'])) return $vars['v'];
        
        // التعامل مع روابط youtube.be المختصرة
        $path = explode('/', parse_url($url, PHP_URL_PATH));
        return end($path);
    }

    public function getVideoDetails($url) {
        $videoId = $this->getVideoId($url);
        if (!$videoId) return null;

        // رابط الطلب من جوجل
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . $videoId; // مثال توضيحي
        // الرابط الصحيح لليوتيوب:
        $apiUrl = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$videoId}&key={$this->apiKey}";

        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        if (isset($data['items'][0]['snippet'])) {
            $snippet = $data['items'][0]['snippet'];

            return [
                'title'       => $snippet['title'],
                 'video'       => $snippet['video'],
                'description' => $snippet['description'],
                'thumbnail'   => $snippet['thumbnails']['high']['url'], // الصورة بجودة عالية
                'keywords'    => isset($snippet['tags']) ? $snippet['tags'] : [], // الكلمات المفتاحية
                'publishedAt' => $snippet['publishedAt'],
                'video_id'    => $videoId
            ];
        }

        return null;
    }
}