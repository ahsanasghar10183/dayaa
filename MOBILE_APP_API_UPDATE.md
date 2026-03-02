# Mobile App API Update - Dayaa Platform

## 🚨 IMPORTANT: API Endpoint Changes

The API endpoint structure has been updated. Please update your mobile app with the following changes:

---

## 📍 Base URL
```
Production: https://software.dayaatech.de/api
```

---

## 🔄 API Endpoint Changes

### ❌ OLD Endpoints (DO NOT USE)
```
POST /api/device/pair          ❌ WRONG
POST /api/device/heartbeat     ❌ WRONG
POST /api/device/status        ❌ WRONG
```

### ✅ NEW Endpoints (USE THESE)
```
POST /api/devices/pair         ✅ CORRECT
POST /api/devices/heartbeat    ✅ CORRECT
GET  /api/campaigns/active     ✅ CORRECT
POST /api/donations            ✅ CORRECT
POST /api/devices/unpair       ✅ CORRECT
```

**Key Change:** `device` → `devices` (plural)

---

## 📝 Complete API Documentation

### 1. Device Pairing
**Endpoint:** `POST /api/devices/pair`
**Authentication:** None (Public endpoint)
**Purpose:** Pair a device using Device ID and 6-digit PIN

**Request:**
```http
POST https://software.dayaatech.de/api/devices/pair
Content-Type: application/json

{
  "device_id": "DEVICE_123456",
  "pairing_pin": "123456"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Device paired successfully",
  "data": {
    "api_token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890",
    "device_name": "Kiosk Device 1",
    "organization": "Red Cross Germany",
    "status": "online"
  }
}
```

**Error Response (400/422):**
```json
{
  "success": false,
  "message": "Invalid pairing PIN or device not found"
}
```

**Action Required:**
- ✅ Save `api_token` securely in device storage
- ✅ Use this token for all subsequent API calls
- ✅ Store `device_id` for future requests

---

### 2. Device Heartbeat
**Endpoint:** `POST /api/devices/heartbeat`
**Authentication:** Required (Bearer Token)
**Purpose:** Keep device status as "online" (send every 60 seconds)

**Request:**
```http
POST https://software.dayaatech.de/api/devices/heartbeat
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json

{
  "device_id": "DEVICE_123456"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Heartbeat received",
  "data": {
    "status": "online",
    "last_active": "2024-03-02 15:30:45"
  }
}
```

**Action Required:**
- ✅ Send heartbeat every 60 seconds
- ✅ Include `Authorization: Bearer {token}` header
- ✅ Handle 401 errors (token expired → re-pair device)

---

### 3. Get Active Campaigns
**Endpoint:** `GET /api/campaigns/active`
**Authentication:** Required (Bearer Token)
**Purpose:** Fetch all active campaigns assigned to this device

**Request:**
```http
GET https://software.dayaatech.de/api/campaigns/active
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Emergency Relief Fund",
      "campaign_type": "quick_donation",
      "status": "active",
      "layout": {
        "template": "grid",
        "donation_amounts": [5, 10, 20, 50, 100]
      },
      "design": {
        "primary_color": "#0F69F3",
        "secondary_color": "#170AB5",
        "logo_url": "https://software.dayaatech.de/storage/logos/campaign1.png"
      },
      "thank_you_message": "Thank you for your generous donation!",
      "created_at": "2024-01-15T10:00:00.000000Z"
    }
  ]
}
```

**Action Required:**
- ✅ Fetch campaigns on app startup
- ✅ Refresh campaigns every 5 minutes
- ✅ Display campaigns based on `layout` configuration
- ✅ Apply `design` settings (colors, logo)

---

### 4. Submit Donation
**Endpoint:** `POST /api/donations`
**Authentication:** Required (Bearer Token)
**Purpose:** Submit a new donation transaction

**Request:**
```http
POST https://software.dayaatech.de/api/donations
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json

{
  "campaign_id": 1,
  "amount": 10.00,
  "payment_method": "card"
}
```

**Payment Methods:**
- `card` - Credit/Debit Card
- `cash` - Cash payment
- `paypal` - PayPal
- `bank_transfer` - Bank Transfer

**Success Response (200):**
```json
{
  "success": true,
  "message": "Donation created successfully",
  "data": {
    "id": 12345,
    "campaign_id": 1,
    "amount": 10.00,
    "payment_method": "card",
    "payment_status": "pending",
    "created_at": "2024-03-02T15:35:20.000000Z"
  }
}
```

**Action Required:**
- ✅ Submit donation after payment confirmation
- ✅ Handle payment status updates
- ✅ Show thank you message from campaign data

---

### 5. Unpair Device
**Endpoint:** `POST /api/devices/unpair`
**Authentication:** Required (Bearer Token)
**Purpose:** Unpair device and revoke API token

**Request:**
```http
POST https://software.dayaatech.de/api/devices/unpair
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json

{
  "device_id": "DEVICE_123456"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Device unpaired successfully"
}
```

**Action Required:**
- ✅ Clear stored API token
- ✅ Clear device data
- ✅ Return to pairing screen

---

## 🔐 Authentication Flow

### Initial Pairing:
1. User enters Device ID and PIN in mobile app
2. App calls `POST /api/devices/pair`
3. Backend validates PIN and returns API token
4. App stores token securely (encrypted storage)

### Subsequent Requests:
```
All API calls (except pairing) require:
Authorization: Bearer {api_token}
```

### Token Expiration:
- Tokens are long-lived but can be revoked
- If you receive `401 Unauthorized`, re-pair the device

---

## 📱 Mobile App Implementation Checklist

### Required Changes:
- [ ] Update base URL to `https://software.dayaatech.de/api`
- [ ] Change `/device/` to `/devices/` in all API calls
- [ ] Add `Authorization: Bearer {token}` header to all protected endpoints
- [ ] Implement heartbeat timer (every 60 seconds)
- [ ] Add campaign refresh logic (every 5 minutes)
- [ ] Handle authentication errors (401 → force re-pair)

### Recommended Features:
- [ ] Add offline mode with queue for donations
- [ ] Implement retry logic for failed API calls
- [ ] Add network connectivity checks
- [ ] Log API errors for debugging
- [ ] Show connection status indicator

---

## 🐛 Error Handling

### Common HTTP Status Codes:

**200 - Success**
```json
{ "success": true, "data": {...} }
```

**400 - Bad Request**
```json
{ "success": false, "message": "Invalid request format" }
```

**401 - Unauthorized**
```json
{ "success": false, "message": "Unauthenticated" }
```
**Action:** Token expired or invalid → Re-pair device

**404 - Not Found**
```json
{ "success": false, "message": "Resource not found" }
```

**422 - Validation Error**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "amount": ["The amount field is required"],
    "campaign_id": ["The selected campaign is invalid"]
  }
}
```

**500 - Server Error**
```json
{ "success": false, "message": "Internal server error" }
```
**Action:** Retry after 30 seconds

---

## 🧪 Testing Guide

### Test Device Credentials:
You can get test device credentials from:
`https://software.dayaatech.de/organization/devices`

### Test Pairing:
```bash
curl -X POST https://software.dayaatech.de/api/devices/pair \
  -H "Content-Type: application/json" \
  -d '{
    "device_id": "TEST_DEVICE_123",
    "pairing_pin": "123456"
  }'
```

### Test Heartbeat:
```bash
curl -X POST https://software.dayaatech.de/api/devices/heartbeat \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "device_id": "TEST_DEVICE_123"
  }'
```

### Test Get Campaigns:
```bash
curl -X GET https://software.dayaatech.de/api/campaigns/active \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

---

## 📞 Support Contact

**Backend Developer:** [Your Name]
**Platform URL:** https://software.dayaatech.de
**API Documentation:** This document

**Questions?** Please test all endpoints and report any issues.

---

## ⚠️ Important Notes

1. **SSL Certificate:** Ensure your app accepts the SSL certificate from `dayaatech.de`
2. **Content-Type:** Always include `Content-Type: application/json` header
3. **Token Storage:** Store API tokens securely (encrypted storage)
4. **Network Timeout:** Set reasonable timeout (30 seconds recommended)
5. **Retry Logic:** Implement exponential backoff for failed requests

---

**Last Updated:** March 2, 2024
**API Version:** 1.0
**Platform Version:** Laravel 12.47.0
