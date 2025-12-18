<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .form-type {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .info-value {
            color: #555;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #667eea;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #999;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 New Form Submission</h1>
        <p>You have received a new form submission</p>
    </div>

    <div class="content">
        <span class="form-type">{{ $form->type->label() }}</span>

        @if($form->name)
        <div class="info-row">
            <div class="info-label">👤 Name:</div>
            <div class="info-value">{{ $form->name }}</div>
        </div>
        @endif

        @if($form->email)
        <div class="info-row">
            <div class="info-label">✉️ Email:</div>
            <div class="info-value">
                <a href="mailto:{{ $form->email }}">{{ $form->email }}</a>
            </div>
        </div>
        @endif

        @if($form->phone)
        <div class="info-row">
            <div class="info-label">📞 Phone:</div>
            <div class="info-value">{{ $form->phone }}</div>
        </div>
        @endif

        @if($form->subject)
        <div class="info-row">
            <div class="info-label">📋 Subject:</div>
            <div class="info-value">{{ $form->subject }}</div>
        </div>
        @endif

        @if($form->message)
        <div class="message-box">
            <div class="info-label">💬 Message:</div>
            <div class="info-value">{{ nl2br(e($form->message)) }}</div>
        </div>
        @endif

        @if($form->data && count($form->data) > 0)
        <div style="margin-top: 20px;">
            <div class="info-label">📎 Additional Data:</div>
            @foreach($form->data as $key => $value)
                @if(!in_array($key, ['name', 'email', 'phone', 'subject', 'message']))
                <div class="info-row">
                    <div class="info-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</div>
                    <div class="info-value">{{ is_array($value) ? implode(', ', $value) : $value }}</div>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        <div class="info-row" style="margin-top: 20px;">
            <div class="info-label">⏰ Submitted At:</div>
            <div class="info-value">{{ $form->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">🌐 IP Address:</div>
            <div class="info-value">{{ $form->ip_address ?? 'N/A' }}</div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('dashboard.forms.show', $form->id) }}" class="button">
                View in Dashboard
            </a>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>You are receiving this because you are configured to receive {{ $form->type->label() }} form notifications.</p>
    </div>
</body>
</html>

