/**
 * Alternative WebSocket Listeners using Direct Polling
 * This version doesn't require Pusher for development
 * It uses periodic polling as a fallback
 */

export function initializeCashAdvanceWebSocket() {
    // For development: use polling instead of WebSocket
    console.log('Initializing cash advance listeners (polling mode)');
    
    const userRole = document.querySelector('meta[name="user-role"]')?.content;
    
    if (userRole === '3') {
        // Accounting staff - check for new requests every 5 seconds
        startAccountingListener();
    } else if (userRole === '2') {
        // Admin staff - check for decisions every 5 seconds
        startAdminListener();
    }
}

function startAccountingListener() {
    let lastCheckTime = Date.now();
    
    setInterval(async () => {
        try {
            const response = await fetch('{{ route("accounting.cash-advance.requests.index") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            const requests = data.requests || [];

            // Check for new pending requests
            const pendingRequests = requests.filter(r => r.status === 'pending');
            
            if (pendingRequests.length > 0) {
                const newestRequest = pendingRequests[0];
                
                // Show notification for new request
                if (new Date(newestRequest.submitted_at).getTime() > lastCheckTime) {
                    showCashAdvanceToast(
                        `New cash advance request: ₱${Number(newestRequest.requested_amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}`,
                        'info'
                    );
                    
                    // You can trigger UI update here
                    // window.location.reload() if needed
                }
            }

            lastCheckTime = Date.now();
        } catch (error) {
            console.log('Polling check skipped:', error.message);
        }
    }, 5000); // Check every 5 seconds
}

function startAdminListener() {
    let lastCheckTime = Date.now();
    
    setInterval(async () => {
        try {
            const route = window.CASH_ADVANCE_MY_REQUESTS_ROUTE;
            if (!route) return;

            const response = await fetch(route, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            const requests = data.requests || [];

            // Check for decisions (approved/rejected)
            const decidedRequests = requests.filter(r => 
                (r.status === 'approved' || r.status === 'rejected') &&
                new Date(r.reviewed_at).getTime() > lastCheckTime
            );
            
            if (decidedRequests.length > 0) {
                const latestDecision = decidedRequests[0];
                
                // Show notification for decision
                if (latestDecision.status === 'approved') {
                    showCashAdvanceToast(
                        `Your request approved! Approved amount: ₱${Number(latestDecision.approved_amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}`,
                        'success'
                    );
                } else {
                    showCashAdvanceToast(
                        'Your request was rejected.',
                        'error'
                    );
                }

                // Refresh UI
                if (typeof renderEmployeeRequests === 'function') {
                    renderEmployeeRequests();
                }
            }

            lastCheckTime = Date.now();
        } catch (error) {
            console.log('Polling check skipped:', error.message);
        }
    }, 5000); // Check every 5 seconds
}

export function showCashAdvanceToast(message, type = 'info') {
    // Use existing liquidationToast if available
    if (typeof showLiquidationToast === 'function') {
        showLiquidationToast(message, type);
        return;
    }

    // Fallback toast implementation
    let toast = document.getElementById('liquidationToast');
    
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'liquidationToast';
        toast.className = 'fixed top-6 right-6 z-50 p-4 rounded-xl shadow-xl flex items-start gap-3 transition-opacity duration-500';
        document.body.appendChild(toast);
    }

    const toastText = document.getElementById('liquidationToastText') || document.createElement('p');
    const toastIcon = document.getElementById('liquidationToastIcon') || document.createElement('i');
    
    if (!toastText.id) {
        toastText.id = 'liquidationToastText';
        toastText.className = 'text-sm font-medium';
        if (!toast.contains(toastIcon)) toast.appendChild(toastIcon);
        if (!toast.contains(toastText)) toast.appendChild(toastText);
    }
    
    if (!toastIcon.id) {
        toastIcon.id = 'liquidationToastIcon';
        toastIcon.setAttribute('data-feather', type === 'error' ? 'alert-circle' : 'info');
        toastIcon.className = 'w-5 h-5 mt-0.5';
    }

    toastText.textContent = message;
    
    toast.classList.remove('hidden', 'bg-green-50', 'border-green-300', 'bg-blue-50', 'border-blue-300', 'bg-red-50', 'border-red-300', 'border');
    toastText.classList.remove('text-green-800', 'text-blue-800', 'text-red-800');
    toastIcon.classList.remove('text-green-600', 'text-blue-600', 'text-red-600');

    if (type === 'error') {
        toast.classList.add('bg-red-50', 'border-red-300', 'border');
        toastText.classList.add('text-red-800');
        toastIcon.classList.add('text-red-600');
    } else if (type === 'info') {
        toast.classList.add('bg-blue-50', 'border-blue-300', 'border');
        toastText.classList.add('text-blue-800');
        toastIcon.classList.add('text-blue-600');
    } else {
        toast.classList.add('bg-green-50', 'border-green-300', 'border');
        toastText.classList.add('text-green-800');
        toastIcon.classList.add('text-green-600');
    }

    if (window.feather) {
        window.feather.replace();
    }

    toast.style.opacity = '1';
    toast.classList.remove('hidden');

    clearTimeout(window.ctoastTimeout);
    window.ctoastTimeout = setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.style.opacity = '1';
        }, 500);
    }, 5000);
}
