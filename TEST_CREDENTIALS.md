# Dayaa - Test Login Credentials

All accounts use the password: **`password`**

## Super Admin Account
**Access all system features, manage all organizations, approve/reject organizations**

- **Email:** `admin@dayaa.com`
- **Password:** `password`
- **Role:** Super Admin
- **Dashboard:** `/super-admin/dashboard`

### Super Admin Features:
- View system-wide statistics
- Manage all organizations (approve, reject, suspend, reactivate, delete)
- View all donations across the platform
- Monitor subscription usage
- Access activity logs

---

## Organization Admin Accounts

### Account 1: Approved & Active Organization
**Full access to create campaigns, manage devices, view donations**

- **Email:** `org1@dayaa.com`
- **Password:** `password`
- **Role:** Organization Admin
- **Organization:** Red Cross Berlin
- **Status:** Active (Approved)
- **Subscription:** Premium (€10/month)
- **Dashboard:** `/organization/dashboard`

**This account includes:**
- 2 Active Campaigns (Winter Relief 2024, Emergency Medical Fund)
- 2 Devices (1 online, 1 offline)
- 15 Sample Donations (totaling various amounts)
- Full campaign and device management access

---

### Account 2: Pending Approval
**Organization profile submitted, waiting for admin approval**

- **Email:** `org2@dayaa.com`
- **Password:** `password`
- **Role:** Organization Admin
- **Organization:** Tierheim München (Animal Shelter)
- **Status:** Pending Approval
- **Experience:** Will see "Pending Approval" page

---

### Account 3: Rejected Organization
**Organization application was rejected**

- **Email:** `org3@dayaa.com`
- **Password:** `password`
- **Role:** Organization Admin
- **Organization:** Test Organization
- **Status:** Rejected
- **Rejection Reason:** "Incomplete documentation provided. Please submit valid charity registration certificate."
- **Experience:** Will see rejection message with reason

---

## How to Login

1. Navigate to: `http://dayaa.test/login`
2. Enter one of the email addresses above
3. Enter password: `password`
4. You will be redirected to the appropriate dashboard based on your role

---

## Test Data Summary

### Organizations: 3
- 1 Active (Red Cross Berlin)
- 1 Pending (Tierheim München)
- 1 Rejected (Test Organization)

### Campaigns: 2
- Winter Relief 2024 (Active)
- Emergency Medical Fund (Active)

### Devices: 2
- Main Office Tablet (Online)
- Event Booth Tablet (Offline)

### Donations: 15
- All successful payments
- Various amounts (€5, €10, €20, €50, €100)
- Distributed over the last 30 days

### Subscriptions: 1
- Premium plan (€10/month) for Red Cross Berlin

---

## Database Access

If you need to access the database directly:
- **Database:** `dayaa`
- **Host:** `127.0.0.1`
- **Port:** `3306`
- **Username:** `root`
- **Password:** `password123`

---

## Color Scheme

The platform uses a gradient color scheme:
- **Primary:** #1163F0 (Blue)
- **Secondary:** #1707B2 (Purple)
- **Gradient:** Linear gradient from #1163F0 to #1707B2

All colors can be changed from one central location in:
- `tailwind.config.js` - for Tailwind utilities
- `resources/css/app.css` - for CSS variables

---

## Next Steps

To continue development, you can:
1. Build the dashboard views (currently controllers are ready)
2. Create campaign management UI
3. Implement device management interface
4. Add donation processing logic
5. Integrate SumUp payment gateway
6. Build reporting and analytics features
7. Implement multilingual support (EN/DE)
