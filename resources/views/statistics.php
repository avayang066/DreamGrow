<!-- filepath: c:\laragon\www\DreamGrow\resources\views\welcome.blade.php -->
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>統計</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&family=Quicksand:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Zpix';
            src: url('/fonts/zpix.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            /* font-family: 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif; */
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
            font-family: 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            box-shadow: 0 2px 8px #e0e7ff;
            font-size: 1.08em;
            margin-bottom: 18px;
            border-radius: 0 0 14px 14px;
            backdrop-filter: blur(4px);
        }

        .dream-navbar .nav-home {
            font-family: 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            color: #6c63ff;
            text-decoration: none;
            font-size: 0.8em;
            padding: 4px 10px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .dream-navbar .nav-home:hover {
            background: #acacc4;
        }

        .dream-navbar .nav-user {
            font-family: 'Zpix', 'VT323', 'Press Start 2P', 'Noto Sans TC', Arial, sans-serif;
            color: #333;
            font-size: 1em;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.3);
            margin-right: 32px;
            /* 右邊留空間，避免被卡掉 */
        }
    </style>
</head>

<nav class="dream-navbar">
    <a href="/home" class="nav-home">Home</a>
    <a href="/type" class="nav-home">Type</a>
    <a href="/statistics" class="nav-home">Statistics</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    
</script>

</html>