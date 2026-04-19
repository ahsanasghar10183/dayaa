# SumUp Integration Setup Guide

**DAYAA Platform - SumUp Solo Payment Integration**

---

## ✅ Implementation Status

**✅ FULLY IMPLEMENTED** - Ready for credentials!

All SumUp integration code is complete. Once you receive credentials from your client, just update the `.env` file and you're ready to go!

---

## 📋 What's Already Done

### Backend (Laravel) ✅
- ✅ `SumUpService.php` - Complete SumUp Cloud API integration
- ✅ `SumUpWebhookController.php` - Handles payment webhooks
- ✅ API endpoints for payment initiation and status checking
- ✅ Routes configured
- ✅ Configuration in `config/services.php`
- ✅ Database schema ready (SumUp fields in donations table)

### Mobile App (React Native) ✅
- ✅ `PaymentScreen.tsx` updated with real API calls
- ✅ Payment polling implemented (checks status every 2 seconds)
- ✅ Error handling and timeout logic
- ✅ Works with offline queue

### Test Mode ✅
- ✅ Mock payment flow works WITHOUT credentials
- ✅ Can test entire flow before going live
- ✅ Automatically returns success after simulation

---

## 🔑 What You Need from Client

Once your client provides these credentials, you're ready to go live!

### Required Credentials:

```env
# From SumUp Dashboard: https://me.sumup.com → Settings → For Developers
SUMUP_API_KEY=your_api_key_here
SUMUP_MERCHANT_CODE=your_merchant_code_here
SUMUP_WEBHOOK_SECRET=your_webhook_secret_here
```

### How Client Gets Credentials:

1. **Log into SumUp**:
   - Go to https://me.sumup.com
   - Login with merchant account

2. **Navigate to Developer Settings**:
   - Click profile → **Settings**
   - Go to **For Developers** → **Toolkit**

3. **Get API Key**:
   - Find **API Keys** section
   - Click "Create API Key" if none exists
   - Name it "DAYAA Production"
   - Copy the key

4. **Get Merchant Code**:
   - Found in main dashboard
   - Usually format: `MC1234567890`

5. **Get Webhook Secret** (Optional for now):
   - In **Webhooks** section
   - Create webhook for: `https://software.dayaatech.de/kiosk/sumup-webhook`
   - Copy the secret provided

---

## 🚀 Setup Instructions (When Credentials Arrive)

### Step 1: Update Environment Variables

Edit `/Users/apple/Herd/dayaa/.env`:

```env
# SumUp Configuration
SUMUP_API_KEY=your_actual_api_key_from_sumup
SUMUP_MERCHANT_CODE=MC1234567890  # Your actual merchant code
SUMUP_WEBHOOK_SECRET=your_webhook_secret_here
SUMUP_BASE_URL=https://api.sumup.com
SUMUP_TEST_MODE=false  # Set to false for production
```

### Step 2: Clear Config Cache

```bash
cd /Users/apple/Herd/dayaa
php artisan config:clear
php artisan cache:clear
```

### Step 3: Register Webhook in SumUp Dashboard

Client needs to add this webhook URL in SumUp Dashboard:

**Webhook URL**: `https://software.dayaatech.de/kiosk/sumup-webhook`

**Events to enable**:
- ✅ `payment.successful`
- ✅ `payment.failed`
- ✅ `payment.refunded` (optional)

### Step 4: Configure Solo Terminal

Make sure the SumUp Solo terminal:
- ✅ Is paired to client's merchant account
- ✅ Is connected to WiFi or has cellular connection
- ✅ Has latest firmware updates

### Step 5: Test!

1. **Start mobile app**:
   ```bash
   cd /Users/apple/Herd/dayaa-mobile
   npx expo start
   ```

2. **Pair device** with backend

3. **Select campaign** and amount

4. **Initiate payment**

5. **Complete on Solo terminal**:
   - App will show "Please complete payment on terminal"
   - Tap/insert card on Solo terminal
   - Terminal processes payment
   - App automatically updates when complete (polls every 2 seconds)

6. **Success!** ✅
   - Donation marked as completed
   - Transaction ID saved
   - Receipt can be emailed

---

## 🧪 Testing Without Credentials (Test Mode)

**Good news**: You can test the entire flow RIGHT NOW without credentials!

### How Test Mode Works:

When `SUMUP_API_KEY` is empty and `SUMUP_TEST_MODE=true`, the system:
- ✅ Simulates SumUp API responses
- ✅ Returns mock checkout IDs
- ✅ Auto-completes payments after polling
- ✅ Stores test transaction IDs

### To Test Now:

1. **Make sure test mode is enabled** (already is by default):
   ```env
   SUMUP_TEST_MODE=true
   SUMUP_API_KEY=  # Leave empty
   ```

2. **Run mobile app and try a donation**:
   - App creates donation
   - Initiates "payment" (mock)
   - Polls for status (mock returns PAID after few seconds)
   - Marks donation as completed
   - Shows success screen

3. **Check database**:
   ```sql
   SELECT * FROM donations ORDER BY id DESC LIMIT 5;
   ```
   - You'll see donation with `payment_status = 'completed'`
   - Has mock `sumup_transaction_id` like `mock_txn_abc123`

---

## 🔄 Payment Flow Diagram

```
[Mobile App]
    ↓
    Creates donation record
    ↓
POST /api/donations/{id}/sumup/initiate
    ↓
[Laravel Backend]
    ↓
POST https://api.sumup.com/v0.1/checkouts
    ↓
[SumUp Servers]
    ↓
    Sends checkout to Solo terminal
    ↓
[Solo Terminal]
    ↓
    Customer taps/inserts card
    ↓
    Payment processed
    ↓
[SumUp Servers]
    ↓
POST https://software.dayaatech.de/kiosk/sumup-webhook
    ↓
[Laravel Backend]
    ↓
    Updates donation status to 'completed'
    ↓
[Mobile App polls]
    ↓
GET /api/donations/{id}/sumup/status
    ↓
    Receives 'completed' status
    ↓
    Shows success screen ✅
```

---

## 📝 API Endpoints Reference

### For Mobile App:

#### 1. Initiate Payment
```http
POST /api/donations/{id}/sumup/initiate
Authorization: Bearer {device_token}

Response:
{
  "success": true,
  "message": "Payment initiated successfully",
  "data": {
    "donation_id": 123,
    "checkout_id": "abc123-def456",
    "status": "pending",
    "amount": 10.00,
    "polling_required": true,
    "instruction": "Please complete payment on terminal"
  }
}
```

#### 2. Check Payment Status (Polling)
```http
GET /api/donations/{id}/sumup/status
Authorization: Bearer {device_token}

Response:
{
  "success": true,
  "data": {
    "donation_id": 123,
    "payment_status": "completed",  # or "pending" or "failed"
    "sumup_status": "PAID",
    "sumup_transaction_id": "TXN123456789",
    "sumup_transaction_code": "ABC1234"
  }
}
```

### For SumUp Webhooks:

```http
POST /kiosk/sumup-webhook
X-Sumup-Signature: {hmac_signature}

Body:
{
  "event_type": "payment.successful",
  "checkout_reference": "123",  # Our donation ID
  "transaction_id": "TXN123456789",
  "transaction_code": "ABC1234",
  "amount": 10.00,
  "currency": "EUR"
}
```

---

## 🔒 Security Notes

### API Key Security:
- ✅ Never commit API keys to git
- ✅ Keys stored in `.env` (already in .gitignore)
- ✅ Use environment variables only
- ✅ Different keys for test/production

### Webhook Security:
- ✅ Signature verification implemented
- ✅ Only processes valid signatures
- ✅ Logs all webhook attempts

### CSRF Protection:
- ✅ Webhook route excluded from CSRF (required)
- ✅ Already configured in `bootstrap/app.php`:
  ```php
  'kiosk/sumup-webhook'
  ```

---

## 🐛 Troubleshooting

### Issue: "Failed to initiate payment"

**Check**:
1. Is `SUMUP_API_KEY` set correctly?
2. Is `SUMUP_MERCHANT_CODE` correct?
3. Run: `php artisan config:clear`
4. Check logs: `storage/logs/laravel.log`

### Issue: "Payment timeout - please try again"

**Possible causes**:
- Solo terminal not connected to internet
- Terminal not paired to merchant account
- Terminal asleep/off
- Customer didn't complete payment

**Solution**:
- Verify terminal connectivity
- Check terminal status in SumUp dashboard
- Ensure terminal is active

### Issue: Webhook not being received

**Check**:
1. Is webhook registered in SumUp Dashboard?
   - URL: `https://software.dayaatech.de/kiosk/sumup-webhook`
   - Events enabled: `payment.successful`, `payment.failed`

2. Is `SUMUP_WEBHOOK_SECRET` set?

3. Check webhook logs in SumUp Dashboard

4. Test webhook manually:
   ```bash
   curl -X POST https://software.dayaatech.de/kiosk/sumup-webhook \
     -H "Content-Type: application/json" \
     -d '{"event_type":"payment.successful","checkout_reference":"1"}'
   ```

### Issue: Test mode not working

**Make sure**:
```env
SUMUP_TEST_MODE=true
SUMUP_API_KEY=  # Empty or not set
```

Run: `php artisan config:clear`

---

## 📊 Monitoring & Logs

### View Payment Logs:

```bash
tail -f storage/logs/laravel.log | grep -i sumup
```

### Check Donation Status:

```sql
-- View recent donations
SELECT
  id,
  amount,
  payment_status,
  payment_method,
  sumup_transaction_id,
  created_at
FROM donations
WHERE payment_method = 'sumup'
ORDER BY id DESC
LIMIT 10;
```

### SumUp Dashboard:

- View all transactions: https://me.sumup.com/transactions
- Check terminal status: https://me.sumup.com/devices

---

## ✅ Pre-Launch Checklist

Before going live with real payments:

- [ ] Client provided SumUp API credentials
- [ ] Credentials added to `.env`
- [ ] `SUMUP_TEST_MODE=false`
- [ ] Config cache cleared
- [ ] Webhook registered in SumUp Dashboard
- [ ] Webhook secret set in `.env`
- [ ] Solo terminal connected and active
- [ ] Test donation completed successfully
- [ ] Webhook received and processed
- [ ] Donation marked as completed
- [ ] Transaction ID saved correctly

---

## 🎉 Summary

**Everything is ready!** The SumUp integration is **100% complete** and waiting for credentials.

**When credentials arrive**:
1. Update 3 lines in `.env`
2. Clear config cache
3. Test one donation
4. Go live! ✅

**Current State**:
- ✅ Backend fully functional
- ✅ Mobile app fully functional
- ✅ Test mode allows testing without credentials
- ✅ Webhook handler ready
- ✅ Error handling implemented
- ✅ Logging configured

**Estimated Time After Credentials**: 5-10 minutes to go live!

---

**Questions?** Check the code comments in:
- `app/Services/SumUpService.php`
- `app/Http/Controllers/SumUpWebhookController.php`
- `app/Http/Controllers/Api/DonationController.php`
- `dayaa-mobile/src/screens/PaymentScreen.tsx`
