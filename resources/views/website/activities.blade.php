<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأنشطة - مخيم حياة النويري</title>
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

        .nav-links a:hover, .nav-links a.active {
            color: #e67e22;
        }

        /* Page Header */
        .page-header {
            max-width: 1200px;
            margin: 40px auto 30px;
            padding: 0 20px;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 1.1rem;
            color: #7f8c8d;
        }

        /* Filter Tabs */
        .filter-tabs {
            max-width: 1200px;
            margin: 0 auto 30px;
            padding: 0 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 28px;
            border: 2px solid #2c3e50;
            border-radius: 50px;
            background: transparent;
            color: #2c3e50;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .filter-tab:hover, .filter-tab.active {
            background: #2c3e50;
            color: white;
        }

        .filter-tab.food.active, .filter-tab.food:hover {
            background: #e67e22;
            border-color: #e67e22;
            color: white;
        }

        .filter-tab.voluntary.active, .filter-tab.voluntary:hover {
            background: #27ae60;
            border-color: #27ae60;
            color: white;
        }

        /* Cards Grid */
        .activities-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 60px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .activity-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .activity-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(31, 38, 135, 0.2);
        }

        .card-image {
            position: relative;
            height: 260px;
            overflow: hidden;
            background: #e8ecf1;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.5s;
        }

        .activity-card:hover .card-image img {
            transform: scale(1.08);
        }

        .card-image .type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            color: white;
            backdrop-filter: blur(10px);
        }

        .type-badge.food { background: rgba(230, 126, 34, 0.9); }
        .type-badge.voluntary_work { background: rgba(39, 174, 96, 0.9); }

        .card-image .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .card-body {
            padding: 25px;
        }

        .card-body h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .card-body h3 a {
            text-decoration: none;
            color: inherit;
            transition: color 0.3s;
        }

        .card-body h3 a:hover {
            color: #e67e22;
        }

        .card-address {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .card-address i {
            color: #e67e22;
        }

        .card-excerpt {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
        }

        .share-icons {
            display: flex;
            gap: 10px;
        }

        .share-icons a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .share-icons a:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .share-icons .facebook { background: #1877f2; }
        .share-icons .twitter { background: #1da1f2; }
        .share-icons .whatsapp { background: #25d366; }
        .share-icons .telegram { background: #0088cc; }

        .read-more {
            color: #e67e22;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .read-more:hover {
            color: #d35400;
        }

        .read-more i {
            transition: transform 0.3s;
        }

        .read-more:hover i {
            transform: translateX(-5px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #7f8c8d;
            font-size: 1.3rem;
        }

        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 20px;
            border-top: 3px solid #e67e22;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 { font-size: 1.8rem; }
            .activities-grid { grid-template-columns: 1fr; }
            .navbar .container { flex-direction: column; gap: 10px; }
            .nav-links { gap: 15px; }
            .card-image { height: 200px; }
        }

        @media (max-width: 480px) {
            .activities-grid { padding: 0 10px 40px; gap: 20px; }
            .card-body { padding: 18px; }
            .filter-tabs { gap: 8px; }
            .filter-tab { padding: 8px 18px; font-size: 0.9rem; }
        }

        /* Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .activity-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .activity-card:nth-child(2) { animation-delay: 0.1s; }
        .activity-card:nth-child(3) { animation-delay: 0.2s; }
        .activity-card:nth-child(4) { animation-delay: 0.3s; }
        .activity-card:nth-child(5) { animation-delay: 0.4s; }
        .activity-card:nth-child(6) { animation-delay: 0.5s; }
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

    <section class="page-header">
        <h1><i class="fas fa-calendar-alt" style="color: #e67e22;"></i> أنشطة المخيم</h1>
        <p>تعرف على أحدث الأنشطة والفعاليات في مخيم حياة النويري</p>
    </section>

    <div class="filter-tabs">
        <a href="{{ route('activities') }}" class="filter-tab {{ !request('type') ? 'active' : '' }}">الكل</a>
        <a href="{{ route('activities', ['type' => 'food']) }}" class="filter-tab food {{ request('type') === 'food' ? 'active' : '' }}">
            <i class="fas fa-utensils"></i> طعام
        </a>
        <a href="{{ route('activities', ['type' => 'voluntary_work']) }}" class="filter-tab voluntary {{ request('type') === 'voluntary_work' ? 'active' : '' }}">
            <i class="fas fa-hands-helping"></i> عمل تطوعي
        </a>
    </div>

    <div class="activities-grid">
        @forelse($posts as $post)
        <div class="activity-card">
            <div class="card-image">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                @else
                    <div class="no-image">
                        <i class="fas {{ $post->type === 'food' ? 'fa-utensils' : 'fa-hands-helping' }}"></i>
                    </div>
                @endif
                <span class="type-badge {{ $post->type }}">{{ $post->type_label }}</span>
            </div>
            <div class="card-body">
                <h3><a href="{{ url('/activities/' . $post->slug) }}">{{ $post->title }}</a></h3>

                @if($post->address)
                <div class="card-address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $post->address }}</span>
                </div>
                @endif

                <div class="card-excerpt">{{ $post->excerpt }}</div>

                <div class="card-footer">
                    <a href="{{ url('/activities/' . $post->slug) }}" class="read-more">
                        اقرأ المزيد <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="share-icons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->share_url) }}" target="_blank" class="facebook" title="مشاركة على فيسبوك">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->share_url) }}&text={{ urlencode($post->title) }}" target="_blank" class="twitter" title="مشاركة على تويتر">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . $post->share_url) }}" target="_blank" class="whatsapp" title="مشاركة على واتساب">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode($post->share_url) }}&text={{ urlencode($post->title) }}" target="_blank" class="telegram" title="مشاركة على تيليجرام">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>لا توجد أنشطة حالياً</h3>
        </div>
        @endforelse
    </div>

    @if($posts->hasPages())
    <div style="max-width: 1200px; margin: 0 auto 40px; padding: 0 20px; display: flex; justify-content: center;">
        {{ $posts->appends(request()->query())->links() }}
    </div>
    @endif

    <footer>
        <p>&copy; 2026 مخيم حياة النويري - النصيرات. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
