<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مخيم حياة النويري - الصفحة الرئيسية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            direction: rtl;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            color: #667eea;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .hero {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        .features {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        footer {
            background: rgba(0,0,0,0.2);
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <i class="fas fa-home"></i> مخيم حياة النويري
            </div>
            <ul class="nav-links">
                <li><a href="/">الرئيسية</a></li>
                <li><a href="/login">تسجيل الدخول</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <h1>مرحباً بكم في مخيم حياة النويري</h1>
        <p>نظام إدارة شامل لبيانات العائلات والأفراد في مخيم النصيرات</p>
        <div class="cta-buttons">
            <a href="/login" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> الدخول إلى لوحة التحكم
            </a>
        </div>
    </section>

    <section class="features">
        <div class="feature-card">
            <i class="fas fa-users"></i>
            <h3>إدارة العائلات</h3>
            <p>نظام متكامل لإدارة بيانات العائلات وأفرادها بشكل منظم وسهل</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-file-excel"></i>
            <h3>استيراد وتصدير البيانات</h3>
            <p>إمكانية استيراد وتصدير البيانات من وإلى ملفات Excel بسهولة</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-search"></i>
            <h3>البحث والفلترة</h3>
            <p>أدوات بحث وفلترة متقدمة للوصول السريع إلى البيانات المطلوبة</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 مخيم حياة النويري - النصيرات. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
