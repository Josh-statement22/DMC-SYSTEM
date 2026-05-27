/**
 * WebSocket Event Listeners for Real-time Cash Advance Updates
 */

import Echo from '../bootstrap-echo.js';

export function initializeCashAdvanceWebSocket() {
    // Listen for new cash advance requests submitted
    Echo.channel('cash-advance-requests')
        .listen('cash-advance-request-submitted', (event) => {
            console.log('New cash advance request received:', event);
            
            // Check if current user is accounting (role_id = 3)
            const userRole = document.querySelector('meta[name="user-role"]')?.content;
            if (userRole === '3') {
                // Update accounting dashboard
                onNewCashAdvanceRequest(event);
            }
        });

    // Listen for cash advance decisions (approvals/rejections)
    Echo.channel('cash-advance-decisions')
        .listen('cash-advance-decision-made', (event) => {
            console.log('Cash advance decision received:', event);
            
            // Update employee dashboard
            onCashAdvanceDecisionMade(event);
        });
}

/**
 * Handle new cash advance request submitted by admin
 * This will update the accounting view in real-time
 */
function onNewCashAdvanceRequest(event) {
    try {
        const { request, requester_id, timestamp } = event;
        
        console.log('Processing new cash advance request from requester:', requester_id);
        
        // Toast notification for accounting staff
        showCashAdvanceToast(
            `New request of ₱${Number(request.requested_amount).toLocaleString('en-US', { minimumFractionDigits: 2 })} from employee`,
            'info'
        );
        
        // You can trigger a refresh or real-time update here
        // For now, we'll just log it
    } catch (error) {
        console.error('Error processing cash advance request:', error);
    }
}

/**
 * Handle cash advance decisions (approvals/rejections)
 * This will update the employee's liquidation view
 */
function onCashAdvanceDecisionMade(event) {
    try {
        const { request_id, decision, request, decided_by, timestamp } = event;
        
        console.log(`Cash advance ${decision}:`, request);
        
        // If the decision is for the current user's request
        if (request && window.currentUserId === request.requester_id) {
            // Refresh the cash advance requests list
            if (typeof fetchMyCashAdvanceRequests === 'function') {
                fetchMyCashAdvanceRequests().then(() => {
                    renderEmployeeRequests();
                    
                    // Show notification
                    const message = decision === 'approved' 
                        ? `Your request for ₱${Number(request.approved_amount || request.requested_amount).toLocaleString('en-US', { minimumFractionDigits: 2 })} has been approved!`
                        : 'Your cash advance request has been rejected.';
                    
                    showLiquidationToast(message, decision === 'approved' ? 'success' : 'error');
                });
            }
        }
    } catch (error) {
        console.error('Error processing decision:', error);
    }
}

/**
 * Toast notification for accounting staff
 */
function showCashAdvanceToast(message, type = 'info') {
    // Create a temporary toast if liquidationToast doesn't exist
    let toast = document.getElementById('liquidationToast');
    
    if (!toast) {
        // Create toast element if it doesn't exist
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
        toast.appendChild(toastIcon);
        toast.appendChild(toastText);
    }
    
    if (!toastIcon.id) {
        toastIcon.id = 'liquidationToastIcon';
        toastIcon.setAttribute('data-feather', type === 'error' ? 'alert-circle' : 'info');
        toastIcon.className = 'w-5 h-5 mt-0.5';
    }

    toastText.textContent = message;
    
    // Update styles based on type
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

    // Refresh feather icons if available
    if (window.feather) {
        window.feather.replace();
    }

    toast.style.opacity = '1';
    toast.classList.remove('hidden');

    // Auto-hide after 5 seconds
    const timeout = setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.style.opacity = '1';
        }, 500);
    }, 5000);

    return timeout;
}

export { showCashAdvanceToast };
