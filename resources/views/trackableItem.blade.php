<!-- filepath: c:\laragon\www\DreamGrow\resources\views\trackableItem.blade.php -->
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>子項目管理 | DreamGrow</title>
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            font-size: 2em;
            color: #6c63ff;
            margin-bottom: 18px;
            letter-spacing: 2px;
        }

        .dream-add-form {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            display: flex;
            gap: 10px;
            margin-bottom: 22px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .dream-add-form input,
        .dream-input {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            flex: 1;
            padding: 10px;
            border: 1px solid #b3c6ff;
            border-radius: 10px;
            font-size: 1em;
            background: #f7f7fa;
            margin-bottom: 6px;
        }

        .btn {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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

        .dream-action-btns .del, .del-log {
            background: #e74c3c;
        }

        .dream-action-btns button:hover {
            background: #4834d4;
        }

        .dream-back-link {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
        /* #editItemModal {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
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
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            background: white;
            padding: 32px;
            border-radius: 18px;
            max-width: 340px;
            margin: 80px auto;
            box-shadow: 0 4px 24px #ccc;
        }

        #editItemModal h3 {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            color: #6c63ff;
        } */

        .add-log-btn {
            font-family: 'VT323', 'Press Start 2P', 'Noto Sans TC', 'Quicksand', '微軟正黑體', Arial, sans-serif;
            font-size: 1.2em;
        }

        #typeTitle {
            font-family: 'Zpix', 'VT323', 'Press Start 2P', 'Noto Sans TC', Arial, sans-serif;
        }

        .dream-item-achievement,
        .dream-item-name,
        .calendar-log-content {
            font-family: 'Zpix', 'VT323', 'Press Start 2P', 'Noto Sans TC', Arial, sans-serif;
        }

        @media (max-width: 470px) {

            .dream-item-name,
            .dream-item-achievement,
            {
            font-size: 1.2em !important;
        }

        }
    </style>
</head>

<nav class="dream-navbar">
    <a href="/home" class="nav-home">Home</a>
    <span class="nav-user"><span id="navUserName">載入中...</span></span>
</nav>

<body>
    <div class="dream-container">
        <a href="/type" class="dream-back-link" style="font-size: 1.5em;">← Back to Type Management</a>
        <h2 id="typeTitle">🌙</h2>
        <div class="dream-add-form" style="font-size: 1.2em;">
            <input type="text" id="itemName" placeholder="Sub-item Name">
            <input type="number" id="streakDaysRequired" placeholder="Streak Days Required">
            <input type="number" id="streakBonusExp" placeholder="Streak Bonus EXP">
            <input type="text" id="achievementText" placeholder="Achievement Text">
            <button id="addItemBtn" class="btn">Add</button>
        </div>
        <ul id="itemList" class="dream-item-list"></ul>
    </div>

    <!-- 編輯彈窗 -->
    {{-- <div id="editItemModal">
        <div class="modal-content">
            <h3 style="font-size: 1.2em;">Edit Sub-item</h3>
            Name: <input type="text" id="editItemName" placeholder="Name" class="dream-input"><br><br>
            Streak Days: <input type="number" id="editStreakDaysRequired" placeholder="Streak Days"
                class="dream-input"><br><br>
            Bonus EXP: <input type="number" id="editStreakBonusExp" placeholder="Bonus EXP" class="dream-input"><br><br>
            Achievement Text: <input type="text" id="editAchievementText" placeholder="Achievement Text"
                class="dream-input"><br><br>
            <button id="saveEditBtn" class="btn">Save</button>
            <button id="cancelEditBtn" class="btn" style="background:#ccc;color:#333;">Cancel</button>
        </div>
    </div> --}}

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

        // let editingItemId = null;

        function loadTypeName() {
            $.ajax({
                url: `/api/type/${typeId}`,
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function (type) {
                    $('#typeTitle').html(`${$('<div>').text(type.name).html()}`);
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
                    let items = res.types || res;
                    let html = '';
                    items.forEach(item => {
                        html += `<li style="position:relative;">
                    <span class="dream-item-info" style="font-size: 1.2em;">
                        <b><span class="dream-item-name">${$('<div>').text(item.name).html()} | Lv:${item.level || '-'}</span></b>
                            <br>
                            <span class="dream-item-exp">Exp:${item.exp}</span>
                        <br>Streak Days:${item.streak_days_required || '-'} / Bonus EXP:${item.streak_bonus_exp || '-'}
                        <br>Achievement:<span class="dream-item-achievement">${item.achievement_text || '-'}${item.ifachieved == 1 ? ' 🏆' : ''}</span>
                    </span>
                    <span class="dream-action-btns">
                        <button class="del" data-id="${item.id}">Delete</button>
                    </span>
                    <span class="calendar-icon" data-id="${item.id}" style="margin-left:12px;cursor:pointer;font-size:1.6em;vertical-align:middle;">📅</span>
                    <div class="calendar-popup" style="display:none;position:absolute;z-index:10;top:48px;right:0;width:340px;background:#f7f7fa;border-radius:14px;box-shadow:0 2px 18px #e0e7ff;padding:18px;">
                        <div class="calendar-full" style="margin-bottom:10px;"></div>
                        <div class="calendar-logs"></div>
                        <div class="calendar-add-log" style="margin-top:12px;">
                            <input type="date" class="add-log-date" style="border-radius:6px;border:1px solid #b3c6ff;padding:2px 8px;">
                            <input type="text" class="add-log-content" placeholder="Content" style="border-radius:6px;border:1px solid #b3c6ff;padding:2px 8px;width:90px;">
                            <input type="number" class="add-log-exp" placeholder="EXP" style="border-radius:6px;border:1px solid #b3c6ff;padding:2px 8px;width:50px;">
                            <button class="add-log-btn" data-item="${item.id}" style="background:#6c63ff;color:#fff;border:none;border-radius:6px;padding:3px 10px;cursor:pointer; margin-top: 1em;">Add</button>
                        </div>
                    </div>
                </li>`;
                    });
                    $('#itemList').html(html);
                }
            });
        }

        // 新增 TrackLog
        $('#itemList').on('click', '.add-log-btn', function () {
            let $popup = $(this).closest('.calendar-popup');
            let itemId = $(this).data('item');
            let date = $popup.find('.add-log-date').val();
            let content = $popup.find('.add-log-content').val();
            let exp = $popup.find('.add-log-exp').val();
            if (!date || !content || !exp) return alert('請填寫完整');
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${itemId}/track-log`,
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                data: { date, content, exp },
                success: function (res) {
                    $popup.find(`.calendar-date-btn[data-date="${date}"]`).click();

                    // 顯示成就訊息在 calendar-popup 的最上方
                    if (res.achievement_message) {
                        $popup.prepend(
                            `<div class="achievement-toast" style="color:#e67e22;font-size:1.5em;margin-bottom:8px;">
                            🏆 ${res.achievement_message.message}
                             </div>`
                        );
                        // 可選：幾秒後自動消失
                        setTimeout(function () {
                            $popup.find('.achievement-toast').fadeOut(500, function () { $(this).remove(); });
                        }, 3000);
                    }

                    $popup.find('.add-log-content').val('');
                    $popup.find('.add-log-exp').val('');
                },
                error: function () {
                    alert('新增失敗');
                }
            });
        });

        // 刪除 TrackLog
        $('#itemList').on('click', '.remove-log-btn', function () {
            let itemId = $(this).data('id');
            let logId = $(this).data('logid');
            let $btn = $(this);
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${itemId}/track-log/${logId}`,
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function () {
                    $btn.parent().remove();
                },
                error: function () {
                    alert('Delete failed');
                }
            });
        });

        // 點外部關閉日曆
        $(document).on('mousedown', function (e) {
            $('.calendar-popup').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
                    $(this).hide();
                }
            });
        });

        // 取得該 trackableItem 所有 tracklog日期（只要日期，不要全部log）
        function getTracklogDates(itemId, callback) {
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${itemId}/track-log`,
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function (logs) {
                    // 只取 created_at 的日期部分
                    const dates = logs.map(log => log.created_at.substr(0, 10));
                    callback(dates);
                }
            });
        }

        // 修改 renderCalendar，標紅有 tracklog 的日期
        function renderCalendar(year, month, itemId, $calendar) {
            getTracklogDates(itemId, function (tracklogDates) {
                let firstDay = new Date(year, month - 1, 1).getDay();
                let daysInMonth = new Date(year, month, 0).getDate();
                let calendarHtml = `<div style="display:flex;justify-content:space-between;align-items:center;">
            <button class="cal-prev" data-item="${itemId}" style="background:none;border:none;font-size:1.2em;cursor:pointer;">◀</button>
            <span style="font-weight:bold;">${year}-${month.toString().padStart(2, '0')}</span>
            <button class="cal-next" data-item="${itemId}" style="background:none;border:none;font-size:1.2em;cursor:pointer;">▶</button>
        </div>
        <table style="width:100%;margin-top:8px;text-align:center;font-size:1em;">
            <tr>
                <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
            </tr><tr>`;
                let dayCount = 0;
                for (let i = 0; i < firstDay; i++) {
                    calendarHtml += `<td></td>`;
                    dayCount++;
                }
                for (let d = 1; d <= daysInMonth; d++) {
                    let dateStr = `${year}-${month.toString().padStart(2, '0')}-${d.toString().padStart(2, '0')}`;
                    let isRed = tracklogDates.includes(dateStr);
                    calendarHtml += `<td>
                <button class="calendar-date-btn" data-date="${dateStr}" data-item="${itemId}" style="background:${isRed ? '#ffb3b3' : '#e3f6fd'};color:${isRed ? '#c0392b' : '#333'};border:none;border-radius:8px;padding:4px 8px;margin:2px;cursor:pointer;">
                    ${d}
                </button>
            </td>`;
                    dayCount++;
                    if (dayCount % 7 === 0 && d !== daysInMonth) calendarHtml += `</tr><tr>`;
                }
                while (dayCount % 7 !== 0) {
                    calendarHtml += `<td></td>`;
                    dayCount++;
                }
                calendarHtml += `</tr></table>`;
                $calendar.find('.calendar-full').html(calendarHtml);
                $calendar.find('.calendar-logs').html('');
            });


        }

        // 點擊日曆圖示，顯示完整日曆彈窗
        $('#itemList').on('click', '.calendar-icon', function () {
            let $calendar = $(this).siblings('.calendar-popup');
            $('.calendar-popup').not($calendar).hide(); // 關閉其他
            $calendar.toggle();
            let itemId = $(this).data('id');
            let today = new Date();
            let year = today.getFullYear();
            let month = today.getMonth() + 1;

            // 使用新的 renderCalendar
            renderCalendar(year, month, itemId, $calendar);

            // 切換月份
            $calendar.off('click', '.cal-prev').on('click', '.cal-prev', function () {
                let itemId = $(this).data('item');
                month--;
                if (month < 1) { month = 12; year--; }
                renderCalendar(year, month, itemId, $calendar);
            });
            $calendar.off('click', '.cal-next').on('click', '.cal-next', function () {
                let itemId = $(this).data('item');
                month++;
                if (month > 12) { month = 1; year++; }
                renderCalendar(year, month, itemId, $calendar);
            });
        });

        // 點擊日期，顯示該日所有 TrackLog（只顯示該日）
        $('#itemList').on('click', '.calendar-date-btn', function () {
            let itemId = $(this).data('item');
            let date = $(this).data('date');
            let $popup = $(this).closest('.calendar-popup');
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${itemId}/track-log?date=${date}`,
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: function (logs) {
                    let logsHtml = `<div style="font-weight:bold;margin-bottom:6px;">${date} Logs</div>`;
                    // 只顯示該日的 log
                    logs.filter(log => log.created_at.substr(0, 10) === date).forEach(log => {
                        logsHtml += `<div style="background:#fff;border-radius:8px;padding:6px 10px;margin-bottom:6px;box-shadow:0 1px 6px #e0e7ff;display:flex;justify-content:space-between;align-items:center;">
                    <span class="calendar-log-content">content: ${log.content} / exp: ${log.exp_gained}</span>
                    <button class="del-log" data-logid="${log.id}" data-id="${log.trackable_item_id}">Delete</button>
                </div>`;
                    });
                    $popup.find('.calendar-logs').html(logsHtml);
                }
            });
        });

        // 新增
        $('#addItemBtn').click(function () {
            let name = $('#itemName').val().trim();
            let streak_days_required = $('#streakDaysRequired').val();
            let streak_bonus_exp = $('#streakBonusExp').val();
            let achievement_text = $('#achievementText').val();
            if (!name) return alert('Please enter a name');
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
                    alert(xhr.responseJSON?.message || 'Add failed');
                }
            });
        })

        // 刪除 trackableItem
        $('#itemList').on('click', '.del', function () {
            if (!confirm('Are you sure to delete this item?')) return;
            let id = $(this).data('id');
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${id}`,
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: loadItems,
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Delete failed');
                }
            });
        });

        // 刪除 log
        $('#itemList').on('click', '.del-log', function () {
            if (!confirm('Are you sure to delete this log?')) return;
            let itemId = $(this).data('id');
            let logId = $(this).data('logid');
            console.log('itemId:', itemId, 'logId:', logId);
            $.ajax({
                url: `/api/type/${typeId}/trackable-item/${itemId}/track-log/${logId}`,
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                success: loadItems,
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Delete failed');
                }
            });
        });

    </script>
</body>

</html>