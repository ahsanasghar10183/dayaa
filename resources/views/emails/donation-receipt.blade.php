<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1163F0 0%, #1707B2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .receipt-box {
            background: #f9fafb;
            border: 2px solid #1163F0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .receipt-number {
            font-size: 20px;
            font-weight: bold;
            color: #1163F0;
            text-align: center;
            margin-bottom: 20px;
        }
        .amount {
            font-size: 36px;
            font-weight: bold;
            color: #1163F0;
            text-align: center;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
        }
        .thank-you {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }
        .thank-you h2 {
            color: #1163F0;
            margin-top: 0;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .check-icon {
            width: 60px;
            height: 60px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🙏 Thank You for Your Donation</h1>
    </div>

    <div class="content">
        <div class="check-icon">✓</div>

        <p style="text-align: center; font-size: 18px; color: #6b7280;">
            Your donation has been successfully received
        </p>

        <div class="receipt-box">
            <div class="receipt-number">
                Receipt #{{ $donation->receipt_number }}
            </div>

            <div class="amount">
                €{{ number_format($donation->amount, 2) }}
            </div>

            <div class="detail-row">
                <span class="detail-label">Organization:</span>
                <span class="detail-value">{{ $organization->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Campaign:</span>
                <span class="detail-value">{{ $campaign->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value">{{ $donation->created_at->format('F d, Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span class="detail-value">{{ $donation->created_at->format('h:i A') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ ucfirst($donation->payment_method) }}</span>
            </div>

            @if($donation->sumup_transaction_id)
            <div class="detail-row">
                <span class="detail-label">Transaction ID:</span>
                <span class="detail-value">{{ $donation->sumup_transaction_id }}</span>
            </div>
            @endif
        </div>

        <div class="thank-you">
            <h2>Your Impact Matters</h2>
            <p>
                Your generous contribution of <strong>€{{ number_format($donation->amount, 2) }}</strong>
                helps {{ $organization->name }} continue their important work.
            </p>
            <p style="margin-bottom: 0;">
                {{ $campaign->description ?? 'Thank you for supporting our mission!' }}
            </p>
        </div>

        <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
            This email serves as your official donation receipt. Please keep it for your records.
            @if($organization->charity_number)
            <br><br>
            <strong>Charity Registration Number:</strong> {{ $organization->charity_number }}
            @endif
        </p>
    </div>

    <div class="footer">
        <p>
            <strong>{{ $organization->name }}</strong><br>
            @if($organization->address)
                {{ $organization->address }}<br>
            @endif
            @if($organization->email)
                {{ $organization->email }}<br>
            @endif
            @if($organization->phone)
                {{ $organization->phone }}
            @endif
        </p>
        <p style="margin-top: 20px;">
            Powered by <strong>DAYAA</strong> - Digital Donation Platform
        </p>
    </div>
</body>
</html>
