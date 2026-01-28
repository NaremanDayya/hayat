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

        .nav-links a:hover {
            color: #e67e22;
        }

        .hero {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            text-align: center;
            color: #2c3e50;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            color: #34495e;
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
            background: #e67e22;
            color: white;
        }

        .btn-primary:hover {
            background: #d35400;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(230, 126, 34, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #2c3e50;
            border: 2px solid #2c3e50;
        }

        .btn-secondary:hover {
            background: #2c3e50;
            color: white;
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
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            transition: transform 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(230, 126, 34, 0.15);
        }

        .feature-card i {
            font-size: 3rem;
            color: #e67e22;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #34495e;
            line-height: 1.6;
        }

        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 80px;
            border-top: 3px solid #e67e22;
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
