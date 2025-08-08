<!-- filepath: c:\laragon\www\DreamGrow\resources\views\login.blade.php -->
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>登入</title>
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
            max-width: 400px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 16px;
            box-shadow: 0 4px 24px #ccc;
            padding: 36px 28px;
            text-align: center;
        }

        h2 {
            font-size: 2em;
            color: #6c63ff;
            margin-bottom: 18px;
        }

        .desc {
            color: #444;
            margin-bottom: 24px;
            font-size: 1.08em;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-size: 1em;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
        }

        .btn {
            padding: 10px 32px;
            border: none;
            border-radius: 8px;
            background: #6c63ff;
            color: #fff;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            margin-top: 8px;
        }

        .btn:hover {
            background: #4834d4;
        }

        .back-link {
            display: inline-block;
            margin-top: 18px;
            color: #6c63ff;
            text-decoration: none;
            font-size: 1em;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error-msg {
            color: #e74c3c;
            margin-bottom: 12px;
            font-size: 1em;
        }

        .success-msg {
            color: #27ae60;
            margin-bottom: 12px;
            font-size: 1em;
        }
    </style>
</head>

<nav class="dream-navbar">
    <a href="/home" class="nav-home">🏠 回首頁</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    <div class="container">
        <h2>登入</h2>
        <div class="desc">
            請輸入帳號密碼！
        </div>
        <div class="error-msg" id="errorMsg"></div>
        <div class="success-msg" id="successMsg"></div>
        <form id="loginForm" autocomplete="off">
            <div class="form-group">
                <label for="email">電子郵件</label>
                <input type="email" id="email" name="email" required maxlength="255">
            </div>
            <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            <button type="submit" class="btn">登入</button>
        </form>
        <a href="/register" class="back-link">還沒有帳號？註冊</a>
        <a href="/home" class="back-link" style="margin-left:10px;">回首頁</a>
    </div>

    <script>
        $(function () {

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

            $('#loginForm').submit(function (e) {
                e.preventDefault();
                $('#errorMsg').text('');
                $('#successMsg').text('');
                var email = $('#email').val().trim();
                var password = $('#password').val();

                if (!email || !password) {
                    $('#errorMsg').text('請填寫所有欄位');
                    return;
                }

                $.ajax({
                    url: '/api/login',
                    method: 'POST',
                    data: {
                        email: email,
                        password: password
                    },
                    success: function (res) {
                        // 儲存 token
                        if (res.token) {
                            localStorage.setItem('token', res.token);
                            $('#successMsg').text('登入成功，正在導向...');
                            setTimeout(function () {
                                window.location.href = '/type';
                            }, 1200);
                        } else {
                            $('#errorMsg').text('登入失敗，請稍後再試');
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON?.message || '登入失敗';
                        $('#errorMsg').text(msg);
                    }
                });
            });
        });
    </script>
</body>

</html>