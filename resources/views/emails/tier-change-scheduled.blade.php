<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tier Change Scheduled</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .tier-comparison {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .tier-box {
            display: table-cell;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            width: 45%;
        }
        .tier-box.from {
            background: #f9fafb;
        }
        .tier-box.to {
            background: #dbeafe;
            border-color: #3b82f6;
        }
        .arrow {
            display: table-cell;
            width: 10%;
            text-align: center;
            font-size: 24px;
            vertical-align: middle;
        }
        .tier-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .tier-price {
            font-size: 28px;
            font-weight: bold;
            color: #1163F0;
        }
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #1163F0;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $isUpgrade ? '🎉 Congratulations!' : 'Subscription Update' }}</h1>
        <p>{{ $organization->name }}</p>
    </div>

    <div class="content">
        @if($isUpgrade)
            <p>Great news! Based on your outstanding fundraising performance, your subscription tier will be upgraded.</p>
        @else
            <p>We're writing to inform you about an upcoming change to your subscription tier.</p>
        @endif

        <div class="tier-comparison">
            <div class="tier-box from">
                <div class="tier-name">Current Tier</div>
                <div class="tier-price">{{ $fromTier ? $fromTier->name : 'Free' }}</div>
                <div>€{{ number_format($fromTier ? $fromTier->monthly_fee : 0, 2) }}/month</div>
            </div>
            <div class="arrow">→</div>
            <div class="tier-box to">
                <div class="tier-name">New Tier</div>
                <div class="tier-price">{{ $toTier->name }}</div>
                <div>€{{ number_format($toTier->monthly_fee, 2) }}/month</div>
            </div>
        </div>

        <div class="info-box">
            <strong>12-Month Fundraising Total:</strong> €{{ number_format($donationTotal, 2) }}<br>
            <strong>Change Date:</strong> {{ $scheduledDate->format('F j, Y') }}<br>
            <strong>Next Billing Date:</strong> {{ $scheduledDate->format('F j, Y') }}
        </div>

        @if($isUpgrade)
            <p>Your new tier includes enhanced features:</p>
            <ul>
                @foreach($toTier->features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
            <p><strong>The tier change will take effect on {{ $scheduledDate->format('F j, Y') }}.</strong></p>
        @else
            <p>This change is based on your 12-month fundraising total. Your billing will be adjusted on your next billing date.</p>
        @endif

        <center>
            <a href="{{ url('/organization/billing') }}" class="button">View Billing Details</a>
        </center>

        <p style="margin-top: 30px;">If you have any questions, please don't hesitate to contact our support team.</p>
    </div>

    <div class="footer">
        <p>This is an automated notification from Dayaa</p>
        <p>&copy; {{ date('Y') }} Dayaa. All rights reserved.</p>
    </div>
</body>
</html>
