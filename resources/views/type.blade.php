<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>類型管理 | DreamGrow</title>
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
            font-family: 'Zpix', 'VT323', 'Press Start 2P', 'Noto Sans TC', Arial, sans-serif;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.1em;
        }

        .btn {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            font-size: 0.1em;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            background: #6c63ff;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            background: #4834d4;
        }

        .add-form input[type="text"] {
            background: rgba(255, 255, 255, 0.45);
            border: 1.5px solid #d1d5fa;
            border-radius: 14px;
            box-shadow: 0 2px 12px #e0e7ff, 0 0 16px 2px #a8edea44;
            font-size: 1.08em;
            padding: 10px 16px;
            color: #4834d4;
            outline: none;
            transition: box-shadow 0.2s, border-color 0.2s;
            backdrop-filter: blur(2px);
        }

        .add-form input[type="text"]:focus {
            border-color: #6c63ff;
            box-shadow: 0 4px 24px #b3c6ff, 0 0 24px 6px #fed6e344;
            background: rgba(255, 255, 255, 0.65);
        }

        ul.type-list li {
            background: #f7f7fa;
            border: 1px solid #7a75dd;
            border-radius: 8px;
            box-shadow:
                0 0 0 2px #333,
                4px 4px 0 0 #b3c6ff,
                8px 8px 0 0 #fed6e3;
            margin-bottom: 18px;
            width: 260px;
            height: 80px;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', Arial, sans-serif;
            font-size: 1.3em;
            /* 像素感外框 */
            image-rendering: pixelated;
            /* 像素感 hover 效果 */
        }

        ul.type-list li:hover {
            box-shadow:
                0 0 0 2px #333,
                8px 8px 0 0 #6c63ff,
                12px 12px 0 0 #fed6e3;
            transform: translateY(-4px) scale(1.04);
        }

        ul.type-list li::before {
            content: "";
            position: absolute;
            top: 18px;
            left: 40px;
            width: 60%;
            height: 38%;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0.08) 100%);
            opacity: 0.7;
            transform: skew(-18deg, -8deg);
            pointer-events: none;
            border-radius: 24px;
            filter: blur(2px);
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
            font-family: 'Zpix', 'VT323', 'Press Start 2P', 'Noto Sans TC', Arial, sans-serif;
            font-size: 1.3em;
            color: #333;
            word-break: break-all;
            padding: 0 8px;
            margin-bottom: 8px;
        }

        .action-btns {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            opacity: 1;
            pointer-events: auto;
        }

        .action-btns button {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
    <a href="/home" class="nav-home">Home</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    <div class="container">
        <a href="/home" class="back-link" style="font-size: 1.5em;">← back</a>
        {{-- <h2>DreamTree</h2> --}}
        <div class="desc" style="font-size: 1em;">
            Create, edit or delete your personal growth goals!
        </div>
        <div style="font-size: 0.3em;">

        </div>
        <div class="add-form">
            <input type="text" id="newTypeName" placeholder="New skills..." style="font-size: 1.5em;">
            <button id="addTypeBtn" class="btn" style="font-size: 1.5em;">create</button>
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
                            <button class="edit" data-id="${type.id}" data-name="${type.name}">Edit</button>
                            <button class="del" data-id="${type.id}">Delete</button>
                        </span>
                    </li>`;
                        });
                        $('#typeList').html(html);
                    },
                    error: function () {
                        $('#typeList').html('<li>Load failed, please refresh.</li>');
                    }
                });
            }

            // 新增 type
            $('#addTypeBtn').click(function () {
                let name = $('#newTypeName').val().trim();
                if (!name) return alert('Please enter a name');
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
                        alert(xhr.responseJSON?.message || 'create failed');
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
                if (!confirm('Are you sure you want to delete this?')) return;
                let id = $(this).data('id');
                $.ajax({
                    url: '/api/type/' + id,
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    success: loadTypes,
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Delete failed');
                    }
                });
            });

            // 初始載入
            getUser();
        });
    </script>
</body>

</html>