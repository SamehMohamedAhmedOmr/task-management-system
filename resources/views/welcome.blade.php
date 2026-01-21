<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskFlow - Modern Task Management System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success: #06d77b;
            --dark: #0f1419;
            --dark-glass: rgba(15, 20, 25, 0.4);
            --light-glass: rgba(255, 255, 255, 0.1);
            --text-light: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.7);
            --card-bg: rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: var(--text-light);
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 20s infinite ease-in-out;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            top: -250px;
            right: -250px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            bottom: -200px;
            left: -200px;
            animation-delay: -10s;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #06d77b 0%, #0ec2b8 100%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(50px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-50px, 50px) scale(0.9);
            }
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* Header */
        header {
            padding: 30px 0;
            position: relative;
            z-index: 10;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            font-weight: 700;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: var(--card-bg);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            padding: 100px 0 150px;
            text-align: center;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 72px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero .highlight {
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .hero p {
            font-size: 20px;
            color: var(--text-muted);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-large {
            padding: 18px 40px;
            font-size: 16px;
            border-radius: 14px;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .section-title p {
            font-size: 18px;
            color: var(--text-muted);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .feature-card {
            padding: 40px;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .feature-card:hover::before {
            opacity: 0.1;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .feature-card>* {
            position: relative;
            z-index: 1;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .stat-item h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 56px;
            font-weight: 800;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-item p {
            color: var(--text-muted);
            font-size: 16px;
        }

        /* CTA Section */
        .cta {
            padding: 100px 0 120px;
            text-align: center;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
            padding: 60px;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .cta-content::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .cta-content>* {
            position: relative;
            z-index: 1;
        }

        .cta h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta p {
            font-size: 18px;
            color: var(--text-muted);
            margin-bottom: 40px;
        }

        /* Footer */
        footer {
            padding: 40px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 42px;
            }

            .hero p {
                font-size: 16px;
            }

            .section-title h2 {
                font-size: 36px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .cta h2 {
                font-size: 32px;
            }

            .cta-content {
                padding: 40px 30px;
            }

            .nav-links {
                gap: 10px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Scroll Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <div class="logo">TaskFlow</div>
                <div class="nav-links">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Log in</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Organize Your Work with <span class="highlight">TaskFlow</span></h1>
                <p>The modern task management system that helps teams collaborate, prioritize, and achieve their goals with powerful features and beautiful design.</p>
                <div class="hero-buttons">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-large">Go to Dashboard</a>
                    @else
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary btn-large">Start Free Trial</a>
                    @endif
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-large">Sign In</a>
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats fade-in">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>10K+</h3>
                    <p>Active Users</p>
                </div>
                <div class="stat-item">
                    <h3>50K+</h3>
                    <p>Tasks Completed</p>
                </div>
                <div class="stat-item">
                    <h3>99.9%</h3>
                    <p>Uptime</p>
                </div>
                <div class="stat-item">
                    <h3>24/7</h3>
                    <p>Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features fade-in">
        <div class="container">
            <div class="section-title">
                <h2>Powerful Features</h2>
                <p>Everything you need to manage tasks efficiently</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Task Management</h3>
                    <p>Create, assign, and track tasks with ease. Set priorities, deadlines, and dependencies to keep your team on track.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Team Collaboration</h3>
                    <p>Work together seamlessly with real-time updates, comments, and file sharing. Keep everyone in sync.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📈</div>
                    <h3>Progress Tracking</h3>
                    <p>Visualize your progress with intuitive dashboards and reports. Make data-driven decisions with confidence.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔔</div>
                    <h3>Smart Notifications</h3>
                    <p>Stay informed with intelligent notifications. Never miss a deadline or important update again.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Secure & Private</h3>
                    <p>Your data is protected with enterprise-grade security. GDPR compliant with regular backups.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Fast & Reliable</h3>
                    <p>Built for speed and performance. Experience lightning-fast response times and 99.9% uptime.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta fade-in">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Get Started?</h2>
                <p>Join thousands of teams already using TaskFlow to manage their projects and boost productivity.</p>
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-large">Open Dashboard</a>
                @else
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary btn-large">Create Free Account</a>
                @endif
                @endauth
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} TaskFlow. All rights reserved. Built with Laravel.</p>
        </div>
    </footer>

    <script>
        // Scroll Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    </script>
</body>

</html>