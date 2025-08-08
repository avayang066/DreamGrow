<!-- filepath: c:\laragon\www\DreamGrow\resources\views\welcome.blade.php -->
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>歡迎頁面</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&family=Quicksand:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            font-family: 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* 導覽列簡易半透明白色樣式 */
        .dream-navbar {
            width: 100%;
            background: rgba(255, 255, 255, 0.7);
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* 左右分散 */
            font-family: 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            box-shadow: 0 2px 8px #e0e7ff;
            font-size: 1.08em;
            margin-bottom: 18px;
            border-radius: 0 0 14px 14px;
            backdrop-filter: blur(4px);
        }

        .dream-navbar .nav-home {
            color: #6c63ff;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.08em;
            padding: 4px 10px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .dream-navbar .nav-home:hover {
            background: #f7f7fa;
            text-decoration: underline;
        }

        .dream-navbar .nav-user {
            color: #333;
            font-size: 1em;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.3);
            margin-right: 32px;
            /* 右邊留空間，避免被卡掉 */
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 16px;
            box-shadow: 0 4px 24px #ccc;
            padding: 40px 32px;
            text-align: center;
        }

        h1 {
            font-size: 2.6em;
            color: #6c63ff;
            margin-bottom: 16px;
            letter-spacing: 2px;
        }

        .dream-desc {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin-bottom: 24px;
        }

        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            background: #6c63ff;
            color: #fff;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            background: #4834d4;
        }

        .dream-bg {
            font-size: 1.5em;
            color: #ffb6b9;
            margin-bottom: 12px;
        }

        .feature-list {
            text-align: left;
            margin: 0 auto 24px auto;
            max-width: 480px;
            color: #444;
            font-size: 1em;
        }

        .feature-list li {
            margin-bottom: 10px;
        }
    </style>
</head>

<nav class="dream-navbar">
    <a href="/home" class="nav-home">🏠 回首頁</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    <div class="container">
        <div class="dream-bg">🌙</div>
        <h1>🌙</h1>
        <div class="dream-desc">
            記錄你的成長，每做一件事情就能獲得經驗值，累積升等、解鎖成就，回顧每一天的努力與夢想！
        </div>
        <ul class="feature-list">
            <li>✨ 自訂類型與項目，打造夢想目標</li>
            <li>✨ 項目每輸入一次內容獲得<b>該經驗值</b></li>
            <li>✨ 累積 <b>30 經驗值</b> 即可升等</li>
            <li>✨ 可自訂項目連續幾天獲得經驗值 — 解鎖成就並獲得額外經驗</li>
            <li>✨ 點擊項目，可展開回顧每日輸入內容</li>
            <li>✨ 會員註冊、登入，專屬個人夢想成長紀錄</li>
        </ul>
        <div class="btn-group">
            <a href="{{ route('register') }}" class="btn" id="registerBtn">註冊</a>
            <a href="{{ route('login') }}" class="btn" id="loginBtn">登入</a>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display:none;">
                @csrf
                <button type="submit" class="btn">登出</button>
            </form>
            <a href="{{ route('type') }}" class="btn">進入類型管理</a>
        </div>
        <div style="color:#888; font-size:0.95em;">
            成長的足跡。<br>
            <b>123</b>，陪你一起記錄、回顧、升等！
        </div>
    </div>
</body>
<script>
    $(function () {
        if (localStorage.getItem('token')) {
            $('#registerBtn').hide();
            $('#loginBtn').hide();
            $('#logoutForm').show();
        } else {
            $('#registerBtn').show();
            $('#loginBtn').show();
            $('#logoutForm').hide();
        }

        // 取得使用者名稱
        $.ajax({
            url: '/api/user',
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
            success: function (user) {
                $('#navUserName').text(user.name);
            },
            error: function () {
                $('#navUserName').text('未登入');
            }
        });

        $('#logoutForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/logout',
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function () {
                    localStorage.removeItem('token');
                    location.reload();
                },
                error: function (xhr) {
                    // 如果是 401，也清除 token 並刷新
                    if (xhr.status === 401) {
                        localStorage.removeItem('token');
                        location.reload();
                    } else {
                        alert('登出失敗');
                    }
                }
            });
        });
    });
</script>

</html>