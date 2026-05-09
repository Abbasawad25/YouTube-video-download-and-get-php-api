# 🎥 YouTube Video Downloader & API

أداة آمنة وفعالة للحصول على معلومات شاملة عن مقاطع الفيديو على YouTube مثل العنوان والوصف والكلمات المفتاحية والإحصائيات.

## ✨ المميزات

✅ **التحقق الآمن من الروابط**
- التحقق من أن الرابط هو رابط YouTube حقيقي
- دعم جميع صيغ روابط YouTube (youtube.com و youtu.be)
- معالجة آمنة للبيانات المدخلة

✅ **معلومات شاملة**
- 📌 عنوان الفيديو
- 📝 الوصف الكامل
- 🏷️ الكلمات المفتاحية (Tags)
- 🖼️ صورة مصغرة بجودة عالية
- 👁️ عدد المشاهدات
- 👍 عدد الإعجابات
- ⏱️ مدة الفيديو
- 📅 تاريخ النشر
- 📺 اسم القناة

✅ **معالجة آمنة للأخطاء**
- رسائل خطأ واضحة ومفيدة
- حماية CSRF
- معالجة استثناءات شاملة
- التحقق من توفر الفيديو

✅ **واجهة مستخدم حديثة**
- تصميم responsive
- رسائل تنبيهات واضحة
- واجهة عربية كاملة

## 🔧 المتطلبات

- **PHP 5.4+** أو أحدث
- **Google YouTube Data API v3** - API Key
- **cURL** أو **allow_url_fopen** مفعلة في PHP
- اتصال بالإنترنت

## 📦 التثبيت

1. **انسخ الملفات إلى خادمك:**
```bash
git clone https://github.com/Abbasawad25/YouTube-video-download-and-get-php-api.git
cd YouTube-video-download-and-get-php-api
```

2. **احصل على API Key من Google:**
   - اذهب إلى [Google Cloud Console](https://console.cloud.google.com)
   - أنشئ مشروع جديد
   - فعّل YouTube Data API v3
   - أنشئ API Key
   - انسخ المفتاح

3. **شغّل التطبيق:**
```bash
php -S localhost:8000
```

ثم اذهب إلى: `http://localhost:8000`

## 🚀 طريقة الاستخدام

### عبر الواجهة الرسومية:

1. افتح ملف `index.php`
2. أدخل مفتاح API من Google
3. أدخل رابط الفيديو (أي من الصيغ التالية):
   - `https://www.youtube.com/watch?v=VIDEO_ID`
   - `https://youtu.be/VIDEO_ID`
4. انقر على "جلب معلومات الفيديو"

### استخدام البرمجية (Programmatically):

```php
<?php
require_once 'YouTubeAPI.php';

try {
    // إنشاء كائن من YouTubeDownloader
    $yt = new YouTubeDownloader('YOUR_API_KEY');
    
    // جلب معلومات الفيديو
    $videoData = $yt->getVideoDetails('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
    
    if ($videoData) {
        echo "العنوان: " . $videoData['title'] . "\n";
        echo "المشاهدات: " . $videoData['views'] . "\n";
        echo "الوصف: " . $videoData['description'] . "\n";
    } else {
        echo "خطأ: " . $yt->getLastError();
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage();
}
?>
```

## 📋 البيانات المرجعة

الدالة `getVideoDetails()` تُرجع مصفوفة بالبيانات التالية:

```php
[
    'title'           => 'عنوان الفيديو',
    'description'     => 'وصف الفيديو',
    'thumbnail'       => 'رابط الصورة المصغرة',
    'keywords'        => ['كلمة1', 'كلمة2', ...],
    'publishedAt'     => '2024-01-01T12:00:00Z',
    'channelTitle'    => 'اسم القناة',
    'video_id'        => 'VIDEO_ID',
    'views'           => '1000000',
    'likes'           => '50000',
    'duration'        => 'PT10M30S',
    'url'             => 'الرابط الأصلي'
]
```

## 🛡️ الأمان

### ميزات الأمان المتقدمة:

✅ **التحقق الشامل من الروابط**
- التحقق من صيغة URL
- التحقق من أن الرابط من YouTube
- استخلاص آمن لمعرف الفيديو

✅ **معالجة البيانات**
- HTML Escaping لجميع المخرجات
- معالجة الاستثناءات
- تسجيل الأخطاء آمن

✅ **حماية CSRF**
- توليد tokens عشوائية
- التحقق من الطلبات

✅ **معالجة الأخطاء**
- عدم إظهار معلومات حساسة
- رسائل خطأ آمنة للمستخدم

## 📝 أمثلة

### مثال 1: جلب معلومات فيديو بسيطة

```php
require_once 'YouTubeAPI.php';

$yt = new YouTubeDownloader('YOUR_API_KEY');
$videoData = $yt->getVideoDetails('https://youtu.be/dQw4w9WgXcQ');

if ($videoData) {
    echo "✅ " . $videoData['title'];
} else {
    echo "❌ " . $yt->getLastError();
}
```

### مثال 2: معالجة الأخطاء

```php
try {
    $yt = new YouTubeDownloader($apiKey);
    $videoData = $yt->getVideoDetails($url);
    
    if (!$videoData) {
        // رسالة خطأ من API
        echo "خطأ: " . $yt->getLastError();
    }
} catch (Exception $e) {
    // استثناء من البرنامج
    echo "خطأ في المعالجة: " . $e->getMessage();
}
```

### مثال 3: عرض الإحصائيات

```php
$yt = new YouTubeDownloader('YOUR_API_KEY');
$videoData = $yt->getVideoDetails($url);

if ($videoData) {
    echo "👁️ المشاهدات: " . number_format($videoData['views']) . "\n";
    echo "👍 الإعجابات: " . $videoData['likes'] . "\n";
    echo "📺 القناة: " . $videoData['channelTitle'] . "\n";
}
```

## 🔍 معالجة الأخطاء الشائعة

| الخطأ | السبب | الحل |
|-------|-------|------|
| "هذا ليس رابط YouTube صحيح" | الرابط من موقع آخر | استخدم رابط YouTube فقط |
| "الفيديو غير موجود أو تم حذفه" | الفيديو محذوف أو خاص | تأكد من الرابط والصلاحيات |
| "فشل الاتصال بـ API" | مشكلة في الإنترنت | تحقق من الاتصال |
| "لم يتم العثور على معرف فيديو صحيح" | صيغة الرابط خاطئة | استخدم صيغة صحيحة للرابط |
| "مفتاح API غير صحيح" | API Key خاطئ أو منتهي | تحقق من مفتاح API |

## 📊 ملفات المشروع

```
YouTube-video-download-and-get-php-api/
├── YouTubeAPI.php        # الفئة الرئيسية
├── index.php             # واجهة الويب
└── README.md             # التوثيق
```

## 🤝 المساهمة

نرحب بالمساهمات! يمكنك:
- إصلاح الأخطاء
- إضافة ميزات جديدة
- تحسين التوثيق
- اقتراح تحسينات

## 📄 الترخيص

هذا المشروع مفتوح المصدر ومتاح للاستخدام الحر.

## 👨‍💻 المطور

**Abbas Awad (عباس عوض)**

- GitHub: [@Abbasawad25](https://github.com/Abbasawad25)
- 📧 البريد الإلكتروني: 88461556+Abbasawad25@users.noreply.github.com

## ⭐ اذا أعجبك المشروع

شارك النجمة ⭐ لدعم المشروع!

## 📞 الدعم

للمساعدة أو الإبلاغ عن مشاكل:
- فتح [Issue](https://github.com/Abbasawad25/YouTube-video-download-and-get-php-api/issues)
- أو تواصل معي مباشرة

---

**تم التحديث:** 9 مايو 2026
**الإصدار:** 2.0 (محسّن الأمان)
