<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>類型管理 | DreamGrow</title>
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

        h2 {
            font-size: 2em;
            color: #6c63ff;
            margin-bottom: 18px;
        }

        .desc {
            color: #444;
            margin-bottom: 24px;
            font-size: 1.1em;
        }

        .add-form {
            display: flex;
            gap: 8px;
            margin-bottom: 18px;
            justify-content: center;
        }

        .add-form input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
        }

        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            background: #6c63ff;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            background: #4834d4;
        }

        ul.type-list li {
            background: rgba(255, 255, 255, 0.55);
            border-radius: 50%;
            box-shadow:
                0 4px 18px 0 rgba(166, 180, 255, 0.18),
                0 1.5px 8px #e0e7ff,
                0 0 40px 12px rgba(168, 237, 234, 0.18),
                /* 淡藍暈染 */
                0 0 60px 18px rgba(254, 214, 227, 0.13);
            /* 淡粉暈染 */
            margin-bottom: 22px;
            width: 200px;
            height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            transition: box-shadow 0.2s, transform 0.2s;
            margin-left: auto;
            margin-right: auto;
            padding: 0;
            transition: box-shadow 1s cubic-bezier(.22, 1, .36, 1), transform 1s cubic-bezier(.22, 1, .36, 1);
            will-change: box-shadow, transform;
        }

        ul.type-list li:hover {
            box-shadow:
                0 12px 40px 0 rgba(166, 180, 255, 0.38),
                0 4px 24px #b3c6ff,
                0 0 80px 24px rgba(168, 237, 234, 0.28),
                0 0 120px 36px rgba(254, 214, 227, 0.22);
            transform: translateY(-16px) scale(1.12);
        }

        @keyframes bubblePop {
            0% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-10px) scale(1.08);
            }

            80% {
                transform: translateY(-8px) scale(1.04);
            }

            100% {
                transform: translateY(-12px) scale(1.10);
            }
        }

        .type-name {
            font-size: 1.3em;
            color: #333;
            word-break: break-all;
            padding: 0 8px;
            margin-bottom: 8px;
        }

        .action-btns {
            position: absolute;
            bottom: 12px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        ul.type-list li:hover .action-btns {
            opacity: 1;
            pointer-events: auto;
        }

        .action-btns button {
            background: #fff;
            color: #6c63ff;
            border: 1.5px solid #d1d5fa;
            border-radius: 18px;
            box-shadow: 0 2px 8px #e0e7ff;
            padding: 4px 16px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s, color 0.2s;
        }

        .action-btns button:hover {
            background: #f7f7fa;
            color: #4834d4;
            box-shadow: 0 4px 16px #b3c6ff;
        }

        .action-btns .del {
            color: #e74c3c;
            border-color: #f7b1a1;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 18px;
            color: #6c63ff;
            text-decoration: none;
            font-size: 1em;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<nav class="dream-navbar">
    <a href="/home" class="nav-home">🏠 回首頁</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    <div class="container">
        <a href="/home" class="back-link">← 回首頁</a>
        <h2>類型管理</h2>
        <div class="desc">
            新增、編輯或刪除，打造專屬成長目標！
        </div>
        <div class="add-form">
            <input type="text" id="newTypeName" placeholder="輸入新類型名稱">
            <button id="addTypeBtn" class="btn">新增</button>
        </div>
        <ul class="type-list" id="typeList"></ul>
    </div>

    <script>
        $(function () {
            let userId = null;

            // 取得 user 資訊
            function getUser() {
                $.ajax({
                    url: '/api/user',
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    success: function (res) {
                        userId = res.id;
                        loadTypes();
                    },
                    error: function () {
                        alert('請先登入');
                        window.location.href = '/login';
                    }
                });
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

            // 取得全部 type 並顯示
            function loadTypes() {
                $.ajax({
                    url: '/api/type',
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    success: function (res) {
                        let html = '';
                        $.each(res, function (i, type) {
                            html += `<li>
                        <span class="type-name">${$('<div>').text(type.name).html()}</span>
                        <span class="action-btns">
                            <button class="edit" data-id="${type.id}" data-name="${type.name}">編輯</button>
                            <button class="del" data-id="${type.id}">刪除</button>
                        </span>
                    </li>`;
                        });
                        $('#typeList').html(html);
                    },
                    error: function () {
                        $('#typeList').html('<li>載入失敗，請重新整理。</li>');
                    }
                });
            }

            // 新增 type
            $('#addTypeBtn').click(function () {
                let name = $('#newTypeName').val().trim();
                if (!name) return alert('請輸入名稱');
                $.ajax({
                    url: '/api/type/',
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    data: { name: name },
                    success: function () {
                        $('#newTypeName').val('');
                        loadTypes();
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || '新增失敗');
                    }
                });
            });

            // 編輯 type
            $('#typeList').on('click', '.edit', function () {
                $('#typeList').on('click', '.edit', function () {
                    let id = $(this).data('id');
                    window.location.href = '/type/' + id + '/trackable-item'; // 導到子頁
                });
            });


            // 刪除 type
            $('#typeList').on('click', '.del', function () {
                if (!confirm('確定要刪除嗎？')) return;
                let id = $(this).data('id');
                $.ajax({
                    url: '/api/type/' + id,
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    success: loadTypes,
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || '刪除失敗');
                    }
                });
            });

            // 初始載入
            getUser();
        });
    </script>
</body>

</html>