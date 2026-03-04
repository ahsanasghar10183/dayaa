# 🚨 URGENT: Mobile App Fix Required

## Issue Found & Fixed ✅

The web API has been **FIXED**. The mobile app needs ONE small update to work.

---

## ✅ What Was Fixed in Web API:

1. ✅ Changed field name from `pin` to `pairing_pin`
2. ✅ Changed PIN validation from 4 digits to 6 digits
3. ✅ Fixed PIN validation to use database field (not device_id)
4. ✅ Added proper PIN expiration check
5. ✅ Added "already paired" check

**Status:** Web API is now working correctly! ✅ (Tested and confirmed)

---

## 📱 What Mobile App Needs to Change:

### ❌ Current Mobile App Code (WRONG):
```json
{
  "device_id": "DEV-TABLET-001",
  "pin": "1234"           // ❌ WRONG field name, WRONG length
}
```

### ✅ Correct Mobile App Code (REQUIRED):
```json
{
  "device_id": "DEV-TABLET-001",
  "pairing_pin": "123456"  // ✅ CORRECT: 6-digit PIN
}
```

---

## 🔧 Mobile App Code Changes

### Change 1: Update Field Name
```swift
// OLD (iOS):
let params = [
    "device_id": deviceId,
    "pin": pin  // ❌ REMOVE THIS
]

// NEW (iOS):
let params = [
    "device_id": deviceId,
    "pairing_pin": pairingPin  // ✅ USE THIS
]
```

```kotlin
// OLD (Android):
val params = JSONObject().apply {
    put("device_id", deviceId)
    put("pin", pin)  // ❌ REMOVE THIS
}

// NEW (Android):
val params = JSONObject().apply {
    put("device_id", deviceId)
    put("pairing_pin", pairingPin)  // ✅ USE THIS
}
```

### Change 2: Update PIN Input Length
```swift
// OLD (iOS):
TextField("Enter 4-digit PIN", text: $pin)
    .keyboardType(.numberPad)
    .onChange(of: pin) { newValue in
        if newValue.count > 4 {  // ❌ WRONG LENGTH
            pin = String(newValue.prefix(4))
        }
    }

// NEW (iOS):
TextField("Enter 6-digit PIN", text: $pairing_pin)
    .keyboardType(.numberPad)
    .onChange(of: pairing_pin) { newValue in
        if newValue.count > 6 {  // ✅ CORRECT LENGTH
            pairing_pin = String(newValue.prefix(6))
        }
    }
```

```kotlin
// OLD (Android):
etPin.filters = arrayOf(InputFilter.LengthFilter(4))  // ❌ WRONG

// NEW (Android):
etPin.filters = arrayOf(InputFilter.LengthFilter(6))  // ✅ CORRECT
```

---

## 🧪 Testing After Changes

### Test Request (cURL):
```bash
curl -X POST https://software.dayaatech.de/api/devices/pair \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "device_id": "YOUR_DEVICE_ID",
    "pairing_pin": "123456"
  }'
```

### Expected Success Response:
```json
{
  "success": true,
  "message": "Device paired successfully",
  "data": {
    "api_token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ...",
    "device_name": "Main Office Tablet",
    "device_id": "DEV-TABLET-001",
    "organization": "Red Cross Berlin",
    "status": "online",
    "device": {
      "id": 1,
      "device_id": "DEV-TABLET-001",
      "name": "Main Office Tablet",
      "location": null,
      "status": "online",
      "organization": {
        "id": 1,
        "name": "Red Cross Berlin"
      }
    }
  }
}
```

### Possible Error Responses:

**1. Invalid PIN:**
```json
{
  "success": false,
  "message": "Invalid pairing PIN. Please check your PIN and try again."
}
```
**Action:** User entered wrong PIN - ask them to re-enter

**2. PIN Expired:**
```json
{
  "success": false,
  "message": "Pairing PIN has expired. Please generate a new PIN from the dashboard."
}
```
**Action:** Show message: "PIN expired. Please get a new PIN from your administrator."

**3. Already Paired:**
```json
{
  "success": false,
  "message": "Device is already paired. Please unpair first or generate a new PIN."
}
```
**Action:** Show message: "Device already paired. Contact administrator to unpair."

**4. Validation Error (wrong field name):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "pairing_pin": ["The pairing pin field is required."]
  }
}
```
**Action:** This means mobile app is still using old field name `pin` - update to `pairing_pin`

---

## 📋 Mobile App Developer Checklist

- [ ] Change field name from `pin` to `pairing_pin`
- [ ] Change PIN input length from 4 to 6 digits
- [ ] Update UI text to say "6-digit PIN" (not 4-digit)
- [ ] Test pairing with valid 6-digit PIN
- [ ] Handle all error responses properly
- [ ] Save `api_token` from response
- [ ] Use `api_token` in Authorization header for all subsequent requests

---

## 📞 Need Help?

**Get Test Credentials:**
1. Go to: https://software.dayaatech.de/organization/devices
2. Click on any device
3. Copy the Device ID and 6-digit PIN shown

**Test Device Available:**
- Device ID: `DEV-TABLET-001`
- You'll need to generate a fresh PIN from the dashboard

---

## ⚡ Summary

**Mobile app needs ONLY 2 changes:**
1. Change `"pin"` → `"pairing_pin"` in API request
2. Change PIN length from 4 → 6 digits

**That's it!** The API will now work correctly.

---

**Last Updated:** March 2, 2024 (17:40 UTC)
**Status:** Web API Fixed ✅ | Mobile App Update Required ⏳
