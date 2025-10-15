{{-- filepath: resources/views/pdf/trackable_percent.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TrackableItem 百分比報表</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>TrackableItem 百分比報表</h2>
    <p>日期：{{ $date }}</p>
    <table>
        <thead>
            <tr>
                <th>名稱</th>
                <th>百分比 (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['percent'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>總等級：{{ $total }}</p>
</body>
</html>