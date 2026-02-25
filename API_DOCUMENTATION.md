# DAYAA - Mobile App API Documentation
**Version:** 2.0  
**Last Updated:** February 25, 2026  
**Base URL:** `https://your-domain.com/api`

---

## 📋 TABLE OF CONTENTS
1. [Authentication](#authentication)
2. [Device Endpoints](#device-endpoints)
3. [Campaign Endpoints](#campaign-endpoints)
4. [Donation Endpoints](#donation-endpoints)
5. [Error Handling](#error-handling)
6. [Data Models](#data-models)

---

## 🔐 AUTHENTICATION

### Authentication Method
The API uses **Laravel Sanctum** for token-based authentication.

### How It Works:
1. **Pairing**: Device pairs using `device_id` + `PIN` → Receives API token
2. **Subsequent Requests**: Include token in `Authorization` header

### Headers Format:
```http
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json
Accept: application/json
```

---

## 📱 DEVICE ENDPOINTS

### 1. Pair Device
**Endpoint:** `POST /api/devices/pair`  
**Auth Required:** No

**Request Body:**
```json
{
  "device_id": "DEV-ABC123XYZ456",
  "pin": "3456",
  "device_type": "ipad",
  "os_version": "17.4",
  "app_version": "1.0.0"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Device paired successfully",
  "data": {
    "token": "1|abcdefgh...",
    "device": {
      "id": 1,
      "device_id": "DEV-ABC123XYZ456",
      "name": "iPad Pro - Reception",
      "organization": {
        "id": 5,
        "name": "Hope Foundation"
      },
      "status": "active"
    }
  }
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Invalid PIN",
  "errors": {}
}
```

**PIN Logic:**
- PIN = Last 4 characters of `device_id`
- Example: `DEV-ABC123XYZ456` → PIN is `3456`

---

### 2. Send Heartbeat
**Endpoint:** `POST /api/devices/heartbeat`  
**Auth Required:** Yes (Bearer Token)

**Request Body:**
```json
{
  "ip_address": "192.168.1.100",
  "battery_level": 85,
  "storage_available": 120.5
}
```

**Response:**
```json
{
  "success": true,
  "message": "Heartbeat received",
  "data": {
    "status": "online",
    "last_active": "2026-02-25T14:30:00Z"
  }
}
```

**Frequency:** Send heartbeat every **60 seconds**

---

### 3. Unpair Device
**Endpoint:** `POST /api/devices/unpair`  
**Auth Required:** Yes (Bearer Token)

**Request Body:**
```json
{}
```

**Response:**
```json
{
  "success": true,
  "message": "Device unpaired successfully"
}
```

---

## 🎯 CAMPAIGN ENDPOINTS

### 1. Get Active Campaign
**Endpoint:** `GET /api/campaigns/active`  
**Auth Required:** Yes (Bearer Token)

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 12,
    "name": "Winter Fundraiser 2026",
    "description": "Help us provide warmth to those in need",
    "type": "one-time",
    "status": "active",
    "design_settings": {
      "logo_url": "https://example.com/storage/logos/logo.png",
      "hero_image_url": "https://example.com/storage/campaigns/hero.jpg",
      "background_color": "#ffffff",
      "primary_color": "#1163F0",
      "text_color": "#000000",
      "cta_text": "Donate Now",
      "thank_you_message": "Thank you for your generosity!",
      "footer_message": "Your donation makes a difference"
    },
    "amount_settings": {
      "preset_amounts": [5, 10, 20, 50, 100],
      "min_amount": 1,
      "max_amount": 10000,
      "currency": "EUR",
      "allow_custom": true
    }
  }
}
```

**Response (No Active Campaign):**
```json
{
  "success": false,
  "message": "No active campaign found"
}
```

---

## 💰 DONATION ENDPOINTS

### 1. Create Donation
**Endpoint:** `POST /api/donations`  
**Auth Required:** Yes (Bearer Token)

**Request Body:**
```json
{
  "campaign_id": 12,
  "amount": 50.00,
  "currency": "EUR",
  "payment_method": "sumup",
  "payment_status": "pending",
  "donor_email": "john@example.com",
  "donor_name": "John Doe",
  "donor_phone": "+491234567890",
  "anonymous": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Donation created successfully",
  "data": {
    "id": 1523,
    "receipt_number": "DYA-20260225-1523",
    "amount": 50.00,
    "currency": "EUR",
    "payment_status": "pending",
    "campaign": {
      "id": 12,
      "name": "Winter Fundraiser 2026"
    },
    "created_at": "2026-02-25T14:35:00Z"
  }
}
```

---

### 2. Update Donation (Payment Success)
**Endpoint:** `PATCH /api/donations/{id}/complete`  
**Auth Required:** Yes (Bearer Token)

**Request Body:**
```json
{
  "sumup_transaction_id": "TXSUM12345678",
  "sumup_transaction_code": "TXC789456",
  "payment_status": "completed"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Donation marked as completed",
  "data": {
    "id": 1523,
    "payment_status": "completed",
    "sumup_transaction_id": "TXSUM12345678",
    "updated_at": "2026-02-25T14:36:00Z"
  }
}
```

---

### 3. Mark Donation as Failed
**Endpoint:** `PATCH /api/donations/{id}/fail`  
**Auth Required:** Yes (Bearer Token)

**Request Body:**
```json
{
  "failure_reason": "Payment declined by bank"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Donation marked as failed",
  "data": {
    "id": 1523,
    "payment_status": "failed",
    "updated_at": "2026-02-25T14:36:30Z"
  }
}
```

---

## ⚠️ ERROR HANDLING

### HTTP Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid request data |
| 401 | Unauthorized | Invalid or missing token |
| 403 | Forbidden | Token valid but action not allowed |
| 404 | Not Found | Resource not found |
| 422 | Validation Error | Request data failed validation |
| 500 | Server Error | Internal server error |

### Error Response Format

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "amount": ["The amount must be at least 1."],
    "campaign_id": ["The campaign_id field is required."]
  }
}
```

---

## 📦 DATA MODELS

### Device
```json
{
  "id": 1,
  "device_id": "DEV-ABC123XYZ456",
  "name": "iPad Pro - Reception",
  "location": "Main Entrance",
  "status": "online",
  "last_active": "2026-02-25T14:30:00Z",
  "organization_id": 5
}
```

### Campaign
```json
{
  "id": 12,
  "name": "Winter Fundraiser 2026",
  "description": "Help us provide warmth",
  "type": "one-time",
  "status": "active",
  "design_settings": {...},
  "amount_settings": {...},
  "start_date": "2026-01-01",
  "end_date": "2026-12-31"
}
```

### Donation
```json
{
  "id": 1523,
  "receipt_number": "DYA-20260225-1523",
  "campaign_id": 12,
  "device_id": 1,
  "organization_id": 5,
  "amount": 50.00,
  "currency": "EUR",
  "payment_method": "sumup",
  "payment_status": "completed",
  "sumup_transaction_id": "TXSUM12345678",
  "donor_email": "john@example.com",
  "donor_name": "John Doe",
  "anonymous": false,
  "created_at": "2026-02-25T14:35:00Z"
}
```

---

## 🔄 OFFLINE MODE HANDLING

### Strategy:
1. **Check network connectivity** before each API call
2. **If offline**: Queue donation in local storage (AsyncStorage)
3. **If online**: Send to API immediately
4. **Background sync**: When connection restored, sync queued donations

### Offline Queue Structure:
```json
{
  "queue": [
    {
      "campaign_id": 12,
      "amount": 50.00,
      "currency": "EUR",
      "payment_method": "sumup",
      "payment_status": "completed",
      "sumup_transaction_id": "TXSUM12345678",
      "timestamp": "2026-02-25T14:35:00Z"
    }
  ]
}
```

---

## 📝 IMPLEMENTATION NOTES

### For Mobile App Developer:

1. **Storage Keys:**
   - `@dayaa:auth_token` - Sanctum API token
   - `@dayaa:device` - Device object
   - `@dayaa:campaign` - Cached campaign data
   - `@dayaa:offline_queue` - Pending donations

2. **Token Management:**
   - Store token in AsyncStorage after pairing
   - Include token in all authenticated requests
   - Clear token on unpair

3. **Heartbeat Implementation:**
   ```javascript
   setInterval(async () => {
     if (isOnline) {
       await sendHeartbeat();
     }
   }, 60000); // Every 60 seconds
   ```

4. **SumUp Integration:**
   - Use native SumUp SDK (iOS/Android)
   - After successful payment, call `POST /api/donations/{id}/complete`
   - Include SumUp transaction IDs in request

5. **Error Handling:**
   - Check `success` field in all responses
   - Display user-friendly error messages
   - Retry failed requests (with exponential backoff)

---

## 🚀 TESTING ENDPOINTS

### Test Credentials:
- **Base URL:** `http://localhost:8000/api` (development)
- **Test Device ID:** `DEV-TEST12345678`
- **Test PIN:** `5678`

### Sample API Test (cURL):
```bash
# 1. Pair Device
curl -X POST http://localhost:8000/api/devices/pair \
  -H "Content-Type: application/json" \
  -d '{
    "device_id": "DEV-TEST12345678",
    "pin": "5678",
    "device_type": "ipad"
  }'

# 2. Get Active Campaign (use token from pairing response)
curl -X GET http://localhost:8000/api/campaigns/active \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# 3. Create Donation
curl -X POST http://localhost:8000/api/donations \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "campaign_id": 1,
    "amount": 25.00,
    "currency": "EUR",
    "payment_method": "sumup",
    "payment_status": "pending"
  }'
```

---

## 📞 SUPPORT

For API issues or questions:
- **Email:** dev@dayaa.com
- **Documentation:** https://docs.dayaa.com
- **Issue Tracker:** GitHub Issues

---

**Last Updated:** February 25, 2026  
**API Version:** 2.0  
**Laravel Version:** 12.x  
**Sanctum Version:** 4.x
