# SRC Module Backend - Vercel Deployment

## 🚀 Quick Deploy to Vercel

### Step 1: Install Vercel CLI
```bash
npm install -g vercel
```

### Step 2: Login to Vercel
```bash
vercel login
```

### Step 3: Deploy
```bash
cd vercel-backend
vercel
```

### Step 4: Production Deploy
```bash
vercel --prod
```

---

## 📡 API Endpoints

After deployment, your URLs will be:

```
https://your-project.vercel.app/validate.php
https://your-project.vercel.app/device_log.php
```

---

## 🧪 Test Endpoints

### Test validate.php:
```bash
curl -X POST "https://your-project.vercel.app/validate.php" \
  -H "Content-Type: application/json" \
  -d '{"key":"SRC-TEST-KEY1-2026"}'
```

### Test device_log.php:
```bash
curl -X POST "https://your-project.vercel.app/device_log.php" \
  -H "Content-Type: application/json" \
  -d '{"license_key":"SRC-TEST-KEY1-2026"}'
```

---

## 📝 Valid License Keys

```
SRC-TEST-KEY1-2026
SRC-DEMO-KEY-2026
SRC-TRIAL-KEY-2026
```

Add more keys in `api/validate.php`:
```php
$validKeys = [
    'SRC-TEST-KEY1-2026',
    'YOUR-NEW-KEY-HERE'
];
```

---

## ⚙️ Configuration

### vercel.json
- Routes PHP files to serverless functions
- Enables CORS
- Maps /validate.php → /api/validate.php

### api/validate.php
- Validates license keys
- Encrypts config with AES-256-CBC
- Returns encrypted session data

### api/device_log.php
- Logs device information
- Returns device count
- (Note: File storage doesn't work on Vercel, use database for production)

---

## 🔧 Update APK URL

After deployment, update APK to use your Vercel URL:

**Old URL:**
```java
private static final String API_URL = "https://shorn-cut.shop/validate.php";
```

**New URL:**
```java
private static final String API_URL = "https://your-project.vercel.app/validate.php";
```

---

## 📊 Project Structure

```
vercel-backend/
├── api/
│   ├── validate.php       # License validation endpoint
│   └── device_log.php     # Device logging endpoint
├── vercel.json            # Vercel configuration
└── README.md              # This file
```

---

## ✅ Deployment Checklist

- [ ] Install Vercel CLI
- [ ] Login to Vercel account
- [ ] Deploy project (`vercel`)
- [ ] Test endpoints with cURL
- [ ] Update APK with new URL
- [ ] Deploy to production (`vercel --prod`)

---

## 🎯 Expected Responses

### validate.php (Success):
```json
{
  "status": "ok",
  "data": "BASE64_ENCRYPTED_CONFIG..."
}
```

### validate.php (Error):
```json
{
  "status": "error",
  "message": "Invalid license key"
}
```

### device_log.php:
```json
{
  "status": "ok",
  "device_count": 42,
  "message": "Device logged"
}
```

---

## 🔐 Security Notes

- HTTPS enabled by default on Vercel
- CORS enabled for all origins
- Add authentication for production use
- Use environment variables for sensitive data

---

## 📱 Alternative: Use File Copy Method

If you don't want to deploy backend:
1. Copy files from working device
2. Paste to target device
3. No server needed!

Files to copy:
- `/data/local/tmp/src_module_config.json`
- `/data/data/com.src.module/files/session.json`
- `/data/data/com.src.module/shared_prefs/src_login.xml`

---

Good luck! 🚀
