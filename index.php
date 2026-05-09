<?php
require_once 'YouTubeAPI.php';

session_start();

// Generate or validate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$videoData = null;
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Security validation failed. Please try again.';
    } else {
        $apiKey = trim($_POST['api_key'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');

        // Check if API key is provided
        if (empty($apiKey)) {
            $error = 'Please enter your YouTube API Key';
        }
        // Check if URL is provided
        else if (empty($videoUrl)) {
            $error = 'Please enter a YouTube video URL';
        }
        // Attempt to fetch video data
        else {
            $yt = new YouTubeDownloader($apiKey);
            $videoData = $yt->getVideoDetails($videoUrl);

            if ($videoData) {
                $success = true;
            } else {
                $error = $yt->getLastError();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Video Information Fetcher</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background-color: #fee;
            color: #c33;
            border-left: 4px solid #c33;
        }

        .alert-success {
            background-color: #efe;
            color: #3c3;
            border-left: 4px solid #3c3;
        }

        .video-result {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }

        .video-thumbnail {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .video-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .video-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .video-description {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
            max-height: 200px;
            overflow-y: auto;
        }

        .video-keywords {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .keyword-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .channel-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .channel-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .channel-name {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            .video-title {
                font-size: 20px;
            }

            .video-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>YouTube Video Information</h1>
        <p class="subtitle">Get detailed information about any YouTube video</p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <span>X</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <span>✓</span>
                <span>Video information loaded successfully</span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label for="api_key">YouTube API Key</label>
                <input 
                    type="password" 
                    id="api_key" 
                    name="api_key" 
                    placeholder="Enter your Google API key"
                    value="<?php echo htmlspecialchars($_POST['api_key'] ?? ''); ?>"
                >
                <div class="help-text">Get your API key from Google Cloud Console</div>
            </div>

            <div class="form-group">
                <label for="video_url">YouTube Video URL</label>
                <input 
                    type="text" 
                    id="video_url" 
                    name="video_url" 
                    placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..."
                    value="<?php echo htmlspecialchars($_POST['video_url'] ?? ''); ?>"
                >
                <div class="help-text">Paste any YouTube video URL</div>
            </div>

            <button type="submit">Fetch Video Information</button>
        </form>

        <?php if ($videoData): ?>
            <div class="video-result">
                <img src="<?php echo $videoData['thumbnail']; ?>" alt="Thumbnail" class="video-thumbnail">

                <h2 class="video-title"><?php echo $videoData['title']; ?></h2>

                <div class="video-stats">
                    <div class="stat-item">
                        <div class="stat-label">Views</div>
                        <div class="stat-value"><?php echo is_numeric($videoData['views']) ? number_format($videoData['views']) : $videoData['views']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Likes</div>
                        <div class="stat-value"><?php echo is_numeric($videoData['likes']) ? number_format($videoData['likes']) : $videoData['likes']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Duration</div>
                        <div class="stat-value"><?php echo $videoData['duration']; ?></div>
                    </div>
                </div>

                <div class="channel-info">
                    <div class="channel-label">Published on</div>
                    <div class="channel-name"><?php echo date('F d, Y', strtotime($videoData['publishedAt'])); ?></div>
                </div>

                <div class="channel-info">
                    <div class="channel-label">Channel</div>
                    <div class="channel-name"><?php echo $videoData['channelTitle']; ?></div>
                </div>

                <?php if (!empty($videoData['description'])): ?>
                    <div class="video-description">
                        <?php echo nl2br($videoData['description']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($videoData['keywords'])): ?>
                    <div style="margin-top: 20px;">
                        <strong style="color: #333;">Keywords:</strong>
                        <div class="video-keywords">
                            <?php foreach ($videoData['keywords'] as $keyword): ?>
                                <span class="keyword-tag"><?php echo htmlspecialchars($keyword); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="channel-info" style="margin-top: 20px;">
                    <div class="channel-label">Video ID</div>
                    <div class="channel-name" style="font-family: monospace; font-size: 14px;"><?php echo $videoData['video_id']; ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
