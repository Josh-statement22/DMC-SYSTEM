# Real-Time Updates - QUICK START (Polling Version)

> Kahit simple lang, pero working! Para sa development purposes.

## TL;DR - Kung ayaw mo ng complicated setup

Ginawa ko na everything para sa iyo! Just follow these steps:

### Step 1: Build the Frontend
```bash
cd c:\xampp\htdocs\DMC-SYSTEM\DMC-ERP
npm run build
```

### Step 2: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Test It!

**Window 1 (Admin)**
- Login as Admin account
- Go to Cash Advance Liquidation page

**Window 2 (Accounting)**
- Login as Accounting account  
- Go to Accounting Dashboard (or wherever requests are shown)

**What to do:**
1. In Window 1: Click "Request Cash Advance" button
2. Fill form with:
   - Purpose: "Materials for project"
   - Amount: 5000
3. Click "Submit Request"
4. Watch Window 2 - you should get notification without refresh!

## How It Works

### Simple Polling Approach
Instead of WebSocket (complex), we use **polling** (simple):

```
Admin submits request
    ↓
Server saves to database + broadcasts event
    ↓
Window 1: Shows success message
    ↓
Window 2: Every 5 seconds checks for new requests
    ↓
Window 2 finds NEW request and shows notification
```

**Pros:**
- ✅ Simple to understand
- ✅ Works without Pusher
- ✅ Works in development
- ✅ No extra services needed

**Cons:**
- ❌ Slight 5-second delay (not instant)
- ❌ Uses more server resources
- ❌ Not ideal for production

## Implementation Details

### Files Created:
1. ✅ `app/Events/CashAdvanceRequestSubmitted.php` - Event class
2. ✅ `app/Events/CashAdvanceRequestDecisionMade.php` - Decision event
3. ✅ `resources/js/websocket-listeners-polling.js` - Polling logic
4. ✅ `resources/js/bootstrap-echo.js` - Echo setup (optional)
5. ✅ `resources/views/admin/liquidation.blade.php` - Updated view

### Files Modified:
1. ✅ `routes/web.php` - Added event broadcasting
2. ✅ `vite.config.js` - Added path alias
3. ✅ `.env` - Added Pusher config
4. ✅ `package.json` - Added dependencies

## What's Happening in the Code

### When Admin Submits Request:
```javascript
// liquidation.blade.php - Form submission
document.getElementById('requestAdvanceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    // ... validation ...
    const response = await fetch(CASH_ADVANCE_STORE_ROUTE, {
        method: 'POST',
        body: JSON.stringify({ purpose, requested_amount, date_needed })
    });
    // Success! Request saved to database
});
```

### In Server (routes/web.php):
```php
Route::post('/cash-advance/requests', function (Request $request) {
    // ... validate ...
    $requestId = DB::table('cash_advance_requests')->insertGetId([...]);
    
    // Broadcast event (for future WebSocket)
    CashAdvanceRequestSubmitted::dispatch($newRequest, Auth::id());
    
    return response()->json(['message' => 'Success!']);
});
```

### Accounting Window Checks Every 5 Seconds:
```javascript
// websocket-listeners-polling.js
setInterval(async () => {
    const response = await fetch('/accounting/cash-advance/requests');
    const requests = response.json().requests;
    
    if (newRequest detected) {
        showNotification('New request received!');
    }
}, 5000); // Every 5 seconds
```

## Troubleshooting

### Issue: Window 2 not showing notifications

**Check:**
1. Both windows logged in? ✓
2. Ran `npm run build`? ✓
3. Opened DevTools console (F12)? What errors?
4. Check browser console for: "Initializing cash advance listeners"

**Fix:**
```bash
npm run build
php artisan config:clear
# Refresh both windows
```

### Issue: Notification appears but says "undefined"

**Cause:** Toast function not found

**Fix:** The liquidation.blade.php already has `showLiquidationToast()` function, so it should work.

If not in Accounting view, add to your accounting view:
```blade
<div id="liquidationToast" class="hidden fixed top-6 right-6 z-50 p-4 rounded-xl shadow-xl flex items-start gap-3 transition-opacity duration-500">
    <i id="liquidationToastIcon" data-feather="check-circle" class="w-5 h-5 mt-0.5"></i>
    <p id="liquidationToastText" class="text-sm font-medium">Notification</p>
</div>
```

## Upgrade to Real WebSocket Later

When you want **instant updates** (no 5-second delay):

### Option 1: Pusher Free Tier
```bash
# Already installed!
npm install pusher-js laravel-echo
```

1. Sign up at https://pusher.com/
2. Get free credentials
3. Update `.env` with your credentials
4. Change to `websocket-listeners.js` instead of polling version
5. Done! Real-time now.

### Option 2: Self-Hosted Socket.io
```bash
npm install socket.io-client
```

1. Install Node.js Socket.io server
2. Update `resources/js/bootstrap-echo.js`
3. Change to WebSocket mode
4. Done!

## Testing Checklist

- [ ] `npm run build` successful?
- [ ] No console errors in DevTools?
- [ ] Window 1: Admin logged in?
- [ ] Window 2: Accounting logged in?
- [ ] Window 1: "Request Cash Advance" button visible?
- [ ] Window 1: Submit form works?
- [ ] Window 2: Shows notification within 5 seconds?
- [ ] Window 2: Without page refresh?

## Next Features (Optional)

### Add Sound Notification
```javascript
// In websocket-listeners-polling.js
const audio = new Audio('/sounds/notification.mp3');
audio.play();
```

### Browser Notification
```javascript
if (Notification.permission === 'granted') {
    new Notification('New Cash Advance Request', {
        body: 'New request of ₱5,000 received'
    });
}
```

### Auto-Refresh Request List
```javascript
// In polling function
if (typeof renderEmployeeRequests === 'function') {
    renderEmployeeRequests();
}
```

### Change Polling Interval
In `websocket-listeners-polling.js`, find:
```javascript
}, 5000); // Every 5 seconds
```

Change to:
```javascript
}, 3000); // Every 3 seconds (faster)
}, 10000); // Every 10 seconds (slower)
```

## Files Reference

### Check these if something breaks:

1. **Events not broadcasting?**
   - Check: `app/Events/CashAdvanceRequest*.php` exist
   - Check: `routes/web.php` has dispatch calls

2. **JS not loading?**
   - Check: `npm run build` ran?
   - Check: `resources/js/websocket-listeners-polling.js` exists
   - Check: Browser network tab shows JS file

3. **Notification not showing?**
   - Check: `liquidationToast` div exists in HTML
   - Check: `showLiquidationToast()` function defined
   - Check: Toast element IDs match

## Support

If something doesn't work:
1. Check browser console (F12) for errors
2. Check PHP error logs: `storage/logs/laravel.log`
3. Clear cache: `php artisan cache:clear`
4. Rebuild frontend: `npm run build`

## Summary

✅ You now have working real-time notifications!
- Admin submits request
- Accounting window checks every 5 seconds
- When new request found, notification appears
- No page refresh needed!

Later, when you want to upgrade to instant WebSocket, it's just a config change. Easy!

Enjoy! 🚀
