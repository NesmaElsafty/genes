<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تصدير البيانات</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            direction: rtl;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px;
        }
        .data-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-right: 4px solid #667eea;
        }
        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .data-item:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }
        .value {
            color: #212529;
            text-align: left;
            flex: 1;
            margin-right: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #e9ecef;
        }
        .timestamp {
            color: #6c757d;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="data-card">
                <div class="data-item">
                    <span class="value">{{ $data['type'] }}</span>
                </div>
                <div class="data-item">
                    <span class="value" style="font-weight: bold;">{{ $data['title'] }}</span>
                </div>
                <div class="data-item">
                    <span class="value">{{ $data['description'] }}</span>
                </div>
            </div>
        </div>
        <div class="footer">
            <div>تم إنشاء هذا التقرير بواسطة نظام إدارة المزرعة</div>
            <div class="timestamp">تاريخ الإنشاء: {{ $data['created_at']->format('Y-m-d H:i:s') }}</div>
        </div>
    </div>
</body>
</html>
