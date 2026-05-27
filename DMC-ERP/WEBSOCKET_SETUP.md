# Real-Time WebSocket Implementation Guide

## Overview
This setup enables real-time updates between Admin and Accounting windows without page refresh using Laravel Echo and Pusher.

## Architecture

### Components Created:
1. **Events** (`app/Events/`)
   - `CashAdvanceRequestSubmitted.php` - Broadcast when admin submits request
   - `CashAdvanceRequestDecisionMade.php` - Broadcast when accounting approves/rejects

2. **WebSocket Bootstrap** (`resources/js/bootstrap-echo.js`)
   - Configures Laravel Echo with Pusher

3. **Event Listeners** (`resources/js/websocket-listeners.js`)
   - Handles incoming WebSocket events
   - Updates UI in real-time

4. **Routes Updated** (`routes/web.php`)
   - POST `/cash-advance/requests` - Now broadcasts request submission
   - PATCH `/accounting/cash-advance/requests/{id}/decision` - Now broadcasts decision

## Setup Instructions

### 1. Environment Configuration
Already configured in `.env`:
```
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_APP_CLUSTER=mt1
```

### 2. For Development (Using Pusher Sandbox)

**Option A: Use Pusher Free Tier**
1. Go to https://dashboard.pusher.com/
2. Sign up for free account
3. Create new app/channel
4. Copy your credentials to `.env`:
   ```
   PUSHER_APP_ID=your_app_id
   PUSHER_APP_KEY=your_app_key
   PUSHER_APP_SECRET=your_app_secret
   PUSHER_APP_CLUSTER=your_cluster
   ```

**Option B: Use Local Socket.io (Recommended for Development)**
```bash
npm install socket.io-client --save
```

Then modify `resources/js/bootstrap-echo.js`:
```javascript
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    encrypted: false,
});
```

### 3. Install Dependencies
Already done:
```bash
npm install laravel-echo pusher-js
```

### 4. Build Frontend Assets
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

## How It Works

### Scenario 1: Admin Submits Cash Advance Request

1. **Admin clicks "Submit Request"** in Window 1
   ```
   Window 1 (Admin) → Form submission
   ```

2. **Server creates request and broadcasts event**
   ```php
   // routes/web.php (POST /cash-advance/requests)
   \App\Events\CashAdvanceRequestSubmitted::dispatch($newRequest, Auth::id());
   ```

3. **Event travels through Pusher to Accounting Window**
   ```
   Server → Pusher → WebSocket Channel: "cash-advance-requests"
   ```

4. **Window 2 (Accounting) receives event automatically**
   ```javascript
   // resources/js/websocket-listeners.js
   Echo.channel('cash-advance-requests')
       .listen('cash-advance-request-submitted', (event) => {
           // New request appears in real-time!
       });
   ```

### Scenario 2: Accounting Approves Request

1. **Accounting clicks "Approve"** in Window 2
   ```
   Window 2 (Accounting) → Decision made
   ```

2. **Server updates request and broadcasts event**
   ```php
   // routes/web.php (PATCH /accounting/cash-advance/requests/{id}/decision)
   \App\Events\CashAdvanceRequestDecisionMade::dispatch($requestId, $decision, ...);
   ```

3. **Event travels through Pusher to Admin Window**
   ```
   Server → Pusher → WebSocket Channel: "cash-advance-decisions"
   ```

4. **Window 1 (Admin) receives update automatically**
   ```
   Window 1 automatically shows: "Request Approved!"
   Request list updates in real-time
   ```

## Channel Subscriptions

### For Accounting Staff (Window 2)
Listens to:
- `cash-advance-requests` - Receives new requests from admins
- `cash-advance-decisions` - Receives its own decision broadcasts

### For Admin Staff (Window 1)
Listens to:
- `cash-advance-decisions` - Receives approval/rejection updates

## Broadcasting Channels

### Public Channels
- `cash-advance-requests` - All new requests
- `cash-advance-decisions` - All decisions

These are **public channels** (no authentication required to listen).

For more security in production, use **private channels**:
```php
return [
    new PrivateChannel('user.' . $this->requestData->requester_id),
];
```

## Testing the Implementation

### Method 1: Browser Console Debugging
1. Open **2 browser windows** (or tabs)
2. Open **DevTools Console** in both (F12)
3. One window: Login as Admin (role_id = 2)
4. Other window: Login as Accounting (role_id = 3)
5. Watch console logs for events

### Method 2: Visual Testing
1. Window 1 (Admin): Click "Request Cash Advance"
2. Fill form and submit
3. Watch Window 2 (Accounting) - Should show notification without refresh
4. Window 2: Approve the request
5. Watch Window 1 (Admin) - Should update without refresh

### Debugging Tools
Check browser console for messages like:
```
[WebSocket] Connected
WebSocket listeners initialized
New cash advance request received: {...}
Cash advance decision received: {...}
```

## Troubleshooting

### Issue: WebSocket not connecting

1. **Check BROADCAST_CONNECTION in .env**
   ```
   BROADCAST_CONNECTION=pusher  ✓ Correct
   BROADCAST_CONNECTION=log     ✗ Wrong
   ```

2. **Clear config cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check Pusher credentials** (if using real Pusher account)
   ```bash
   npm run build
   ```

### Issue: Events not broadcasting

1. **Verify route is dispatching event**
   ```php
   dd('Event dispatched'); // Add this before dispatch
   ```

2. **Check event class exists**
   ```bash
   ls app/Events/CashAdvance*
   ```

### Issue: Console shows "Broadcast driver not supported"

This means `config/broadcasting.php` isn't seeing the .env changes.

Fix:
```bash
php artisan config:clear
php artisan cache:clear
npm run build
```

## Production Considerations

1. **Use Real Pusher Account**
   - Paid plans required for production
   - Free tier limited to 20 concurrent connections

2. **Alternative: Self-Hosted Socket.io**
   - Install Node.js Socket.io server
   - Use Laravel Broadcast with Socket.io driver

3. **Security**
   - Use private channels for sensitive data
   - Implement authorization checks
   - Add rate limiting

## File Locations

```
app/
  Events/
    CashAdvanceRequestSubmitted.php
    CashAdvanceRequestDecisionMade.php

resources/
  js/
    bootstrap-echo.js
    websocket-listeners.js
  views/
    admin/
      liquidation.blade.php (updated with WebSocket init)

routes/
  web.php (updated with event broadcasting)

.env (updated with PUSHER credentials)
```

## Next Steps

1. **Test with 2 browser windows** - Verify real-time updates work
2. **Update accounting view** - Add similar WebSocket listeners to accounting dashboard
3. **Add sound notifications** - Play sound when new request arrives
4. **Add browser notifications** - Use Notification API for alerts
5. **Implement typing indicators** - Show who's viewing requests
6. **Add user presence** - Show online accounting staff

## Additional Resources

- Laravel Broadcasting: https://laravel.com/docs/broadcasting
- Laravel Echo: https://laravel.com/docs/echo
- Pusher Documentation: https://pusher.com/docs
