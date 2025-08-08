<!-- filepath: c:\laragon\www\DreamGrow\resources\views\trackableItem.blade.php -->
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>夢境子項目管理 | DreamGrow</title>
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

        .dream-container {
            max-width: 700px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.93);
            border-radius: 20px;
            box-shadow: 0 6px 32px #b3c6ff;
            padding: 48px 36px;
            text-align: center;
        }

        h2 {
            font-size: 2em;
            color: #6c63ff;
            margin-bottom: 18px;
            letter-spacing: 2px;
        }

        .dream-add-form {
            display: flex;
            gap: 10px;
            margin-bottom: 22px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .dream-add-form input,
        .dream-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #b3c6ff;
            border-radius: 10px;
            font-size: 1em;
            background: #f7f7fa;
            margin-bottom: 6px;
        }

        .btn {
            padding: 10px 28px;
            border: none;
            border-radius: 10px;
            background: #6c63ff;
            color: #fff;
            font-size: 1.08em;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            background: #4834d4;
        }

        .dream-item-list {
            list-style: none;
            padding: 0;
            margin: 0 auto;
            max-width: 600px;
        }

        .dream-item-list li {
            background: linear-gradient(90deg, #e0e7ff 0%, #fff 100%);
            margin-bottom: 12px;
            padding: 18px 22px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 8px #e0e7ff;
            font-size: 1.08em;
        }

        .dream-item-info {
            flex: 1;
            text-align: left;
            color: #444;
        }

        .dream-action-btns button {
            margin-left: 10px;
            padding: 7px 18px;
            border: none;
            border-radius: 8px;
            background: #6c63ff;
            color: #fff;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }

        .dream-action-btns .del {
            background: #e74c3c;
        }

        .dream-action-btns button:hover {
            background: #4834d4;
        }

        .dream-back-link {
            display: inline-block;
            margin-bottom: 18px;
            color: #6c63ff;
            text-decoration: none;
            font-size: 1em;
        }

        .dream-back-link:hover {
            text-decoration: underline;
        }

        /* 彈窗 */
        #editItemModal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.18);
            z-index: 999;
        }

        #editItemModal .modal-content {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 32px;
            border-radius: 18px;
            max-width: 340px;
            margin: 80px auto;
            box-shadow: 0 4px 24px #ccc;
        }

        #editItemModal h3 {
            color: #6c63ff;
        }
    </style>
</head>

<nav class="dream-navbar">
    <a href="/home" class="nav-home">🏠 回首頁</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    <div class="dream-container">
        <a href="/type" class="dream-back-link">← 回類型管理</a>
        <h2 id="typeTitle">🌙</h2>
        <div class="dream-add-form">
            <input type="text" id="itemName" placeholder="子項目名稱">
            <input type="number" id="streakDaysRequired" placeholder="連續天數">
            <input type="number" id="streakBonusExp" placeholder="額外經驗">
            <input type="text" id="achievementText" placeholder="成就文字">
            <button id="addItemBtn" class="btn">新增</button>
        </div>
        <ul id="itemList" class="dream-item-list"></ul>
    </div>

    <!-- 編輯彈窗 -->
    <div id="editItemModal">
        <div class="modal-content">
            <h3>編輯子項目</h3>
            <input type="text" id="editItemName" placeholder="名稱" class="dream-input"><br><br>
            <input type="number" id="editStreakDaysRequired" placeholder="連續天數" class="dream-input"><br><br>
            <input type="number" id="editStreakBonusExp" placeholder="額外經驗" class="dream-input"><br><br>
            <input type="text" id="editAchievementText" placeholder="成就文字" class="dream-input"><br><br>
            <button id="saveEditBtn" class="btn">儲存</button>
            <button id="cancelEditBtn" class="btn" style="background:#ccc;color:#333;">取消</button>
        </div>
    </div>

    <script>

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

        // 請根據你的路由取得 typeId，例如：const typeId = {{ $typeId }};
        // 若用 JS 取得 typeId，可用 URL 解析：
        function getTypeIdFromUrl() {
            let match = window.location.pathname.match(/type\/(\d+)\/trackable-item/);
            return match ? match[1] : null;
        }
        const typeId = getTypeIdFromUrl();

        let editingItemId = null;

        function loadTypeName() {
            console.log('typeId:', typeId);
            $.ajax({
                url: `/api/type/${typeId}`,
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function (type) {
                    console.log('type api result:', type);
                    $('#typeTitle').html(`🌙 ${$('<div>').text(type.name).html()}`);
                }
            });
        }
        $(function () {
            loadTypeName();
            loadItems(); // 你的子項目列表載入
        });

        function loadItems() {
            $.ajax({
                url: `/api/type/${typeId}/trackable-item`,
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function (res) {
                    let items = res.types || res; // 兼容 service 回傳格式
                    let html = '';
                    items.forEach(item => {
                        html += `<li>
                        <span class="dream-item-info">
                            <b>${$('<div>').text(item.name).html()}</b>
                            <br>Level：${item.level || '-'}
                            <br>連續天數：${item.streak_days_required || '-'}
                            <br>額外經驗：${item.streak_bonus_exp || '-'}
                            <br>成就：${item.achievement_text || '-'}
                        </span>
                        <span class="dream-action-btns">
                            <button class="edit" data-id="${item.id}">編輯</button>
                            <button class="del" data-id="${item.id}">刪除</button>
                        </span>
                    </li>`;
                    });
                    $('#itemList').html(html);
                }
            });
        }

        // 新增
        $('#addItemBtn').click(function () {
            let name = $('#itemName').val().trim();
            let streak_days_required = $('#streakDaysRequired').val();
            let streak_bonus_exp = $('#streakBonusExp').val();
            let achievement_text = $('#achievementText').val();
            if (!name) return alert('請輸入名稱');
            $.ajax({
                url: `/api/type/${typeId}/trackable-item`,
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                data: { name, streak_days_required, streak_bonus_exp, achievement_text },
                success: function () {
                    $('#itemName').val('');
                    $('#streakDaysRequired').val('');
                    $('#streakBonusExp').val('');
                    $('#achievementText').val('');
                    loadItems();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || '新增失敗');
                }
            });
        });

        // 編輯（開啟彈窗）
        $('#itemList').on('click', '.edit', function () {
            let id = $(this).data('id');
            editingItemId = id;
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${id}`,
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function (item) {
                    $('#editItemName').val(item.name);
                    $('#editStreakDaysRequired').val(item.streak_days_required);
                    $('#editStreakBonusExp').val(item.streak_bonus_exp);
                    $('#editAchievementText').val(item.achievement_text);
                    $('#editItemModal').show();
                }
            });
        });

        // 編輯儲存
        $('#saveEditBtn').click(function () {
            let name = $('#editItemName').val();
            let streak_days_required = $('#editStreakDaysRequired').val();
            let streak_bonus_exp = $('#editStreakBonusExp').val();
            let achievement_text = $('#editAchievementText').val();
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${editingItemId}`,
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                data: { name, streak_days_required, streak_bonus_exp, achievement_text },
                success: function () {
                    $('#editItemModal').hide();
                    loadItems();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || '更新失敗');
                }
            });
        });
        $('#cancelEditBtn').click(function () {
            $('#editItemModal').hide();
        });

        // 刪除
        $('#itemList').on('click', '.del', function () {
            if (!confirm('確定要刪除嗎？')) return;
            let id = $(this).data('id');
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${id}`,
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: loadItems,
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || '刪除失敗');
                }
            });
        });

        // 初始載入
        $(function () {
            loadItems();
        });
    </script>
</body>

</html>