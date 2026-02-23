<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - مخيم حياة النويري</title>
    <meta name="description" content="{{ $post->excerpt }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt }}">
    <meta property="og:url" content="{{ $post->share_url }}">
    @if($post->featured_image)
    <meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            direction: rtl;
        }

        .navbar {
            background: #2c3e50;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 3px solid #e67e22;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover, .nav-links a.active { color: #e67e22; }

        /* Article Container */
        .article-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #7f8c8d;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color 0.3s;
        }

        .back-link:hover { color: #e67e22; }

        .article-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(31,38,135,0.1);
            border: 1px solid rgba(255,255,255,0.18);
        }

        .article-hero {
            position: relative;
            height: 400px;
            overflow: hidden;
        }

        .article-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-hero .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 5rem;
        }

        .article-hero .type-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            backdrop-filter: blur(10px);
        }

        .type-badge.food { background: rgba(230,126,34,0.9); }
        .type-badge.voluntary_work { background: rgba(39,174,96,0.9); }

        .article-body {
            padding: 40px;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .article-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .article-meta i { color: #e67e22; }

        .article-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .article-content {
            color: #444;
            font-size: 1.05rem;
            line-height: 2;
        }

        .article-content p { margin-bottom: 15px; }
        .article-content img { max-width: 100%; border-radius: 10px; margin: 15px 0; }
        .article-content h2, .article-content h3 { color: #2c3e50; margin: 20px 0 10px; }
        .article-content ul, .article-content ol { padding-right: 25px; margin-bottom: 15px; }
        .article-content blockquote {
            border-right: 4px solid #e67e22;
            padding: 15px 20px;
            background: #fef9f3;
            border-radius: 0 10px 10px 0;
            margin: 15px 0;
            font-style: italic;
        }

        /* Gallery */
        .gallery-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
        }

        .gallery-section h3 {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            aspect-ratio: 1;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item::after {
            content: '\f00e';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gallery-item:hover::after { opacity: 1; }

        /* Share Section */
        .share-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .share-section span {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .share-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 1.1rem;
        }

        .share-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .share-btn.facebook { background: #1877f2; }
        .share-btn.twitter { background: #1da1f2; }
        .share-btn.whatsapp { background: #25d366; }
        .share-btn.telegram { background: #0088cc; }
        .share-btn.copy-link { background: #95a5a6; }

        /* Lightbox */
        .lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.92);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lightbox-overlay.active { display: flex; }

        .lightbox-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 85vh;
            border-radius: 12px;
            object-fit: contain;
        }

        .lightbox-close {
            position: absolute;
            top: -40px;
            left: 0;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .lightbox-close:hover { color: #e67e22; }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-nav:hover { background: rgba(255,255,255,0.3); }
        .lightbox-prev { right: -70px; }
        .lightbox-next { left: -70px; }

        .lightbox-counter {
            text-align: center;
            color: white;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 20px;
            border-top: 3px solid #e67e22;
            margin-top: 60px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .article-hero { height: 250px; }
            .article-body { padding: 25px; }
            .article-title { font-size: 1.5rem; }
            .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
            .navbar .container { flex-direction: column; gap: 10px; }
            .lightbox-prev { right: -45px; }
            .lightbox-next { left: -45px; }
            .lightbox-nav { width: 40px; height: 40px; font-size: 1.2rem; }
        }

        @media (max-width: 480px) {
            .article-body { padding: 18px; }
            .article-meta { font-size: 0.85rem; gap: 12px; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .article-card { animation: fadeIn 0.6s ease; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">
                <i class="fas fa-home"></i> مخيم حياة النويري
            </a>
            <ul class="nav-links">
                <li><a href="/">الرئيسية</a></li>
                <li><a href="/activities" class="active">الأنشطة</a></li>
                <li><a href="/login">تسجيل الدخول</a></li>
            </ul>
        </div>
    </nav>

    <div class="article-container">
        <a href="{{ route('activities') }}" class="back-link">
            <i class="fas fa-arrow-right"></i> العودة إلى الأنشطة
        </a>

        <div class="article-card">
            <div class="article-hero">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                @else
                    <div class="no-image">
                        <i class="fas {{ $post->type === 'food' ? 'fa-utensils' : 'fa-hands-helping' }}"></i>
                    </div>
                @endif
                <span class="type-badge {{ $post->type }}">{{ $post->type_label }}</span>
            </div>

            <div class="article-body">
                <div class="article-meta">
                    @if($post->address)
                    <span><i class="fas fa-map-marker-alt"></i> {{ $post->address }}</span>
                    @endif
                    <span><i class="fas fa-calendar"></i> {{ $post->created_at->format('Y-m-d') }}</span>
                    @if($post->images->count() > 0)
                    <span><i class="fas fa-images"></i> {{ $post->images->count() }} صورة</span>
                    @endif
                </div>

                <h1 class="article-title">{{ $post->title }}</h1>

                <div class="article-content">
                    {!! $post->content !!}
                </div>

                {{-- Gallery --}}
                @if($post->images->count() > 0)
                <div class="gallery-section">
                    <h3><i class="fas fa-images" style="color: #e67e22;"></i> معرض الصور</h3>
                    <div class="gallery-grid">
                        @foreach($post->images as $index => $image)
                        <div class="gallery-item" onclick="openLightbox({{ $index }})">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="صورة {{ $index + 1 }}">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Share --}}
                <div class="share-section">
                    <span><i class="fas fa-share-alt"></i> مشاركة:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->share_url) }}" target="_blank" class="share-btn facebook" title="فيسبوك">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->share_url) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn twitter" title="تويتر">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . $post->share_url) }}" target="_blank" class="share-btn whatsapp" title="واتساب">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode($post->share_url) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn telegram" title="تيليجرام">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <button onclick="copyLink()" class="share-btn copy-link" title="نسخ الرابط" style="border: none; cursor: pointer;">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    @if($post->images->count() > 0)
    <div class="lightbox-overlay" id="lightbox">
        <div class="lightbox-content">
            <button class="lightbox-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
            <button class="lightbox-nav lightbox-prev" onclick="prevImage()"><i class="fas fa-chevron-right"></i></button>
            <img id="lightbox-img" src="" alt="">
            <button class="lightbox-nav lightbox-next" onclick="nextImage()"><i class="fas fa-chevron-left"></i></button>
            <div class="lightbox-counter" id="lightbox-counter"></div>
        </div>
    </div>
    @endif

    <footer>
        <p>&copy; 2026 مخيم حياة النويري - النصيرات. جميع الحقوق محفوظة.</p>
    </footer>

    <script>
        // Gallery Lightbox
        const galleryImages = @json($post->images->pluck('image_path')->map(fn($p) => asset('storage/' . $p)));
        let currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            document.getElementById('lightbox').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = '';
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % galleryImages.length;
            updateLightbox();
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
            updateLightbox();
        }

        function updateLightbox() {
            document.getElementById('lightbox-img').src = galleryImages[currentIndex];
            document.getElementById('lightbox-counter').textContent = (currentIndex + 1) + ' / ' + galleryImages.length;
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('lightbox').classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') nextImage();
            if (e.key === 'ArrowRight') prevImage();
        });

        // Click outside to close
        document.getElementById('lightbox')?.addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });

        // Copy link
        function copyLink() {
            navigator.clipboard.writeText('{{ $post->share_url }}').then(() => {
                alert('تم نسخ الرابط!');
            });
        }
    </script>
</body>
</html>
