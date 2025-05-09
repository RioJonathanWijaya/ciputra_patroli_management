// Initialize Firebase Realtime Database references
let notificationsRef;
let kejadianRef;

// Initialize notification variables
let unreadCount = 0;
const notificationsList = document.getElementById('notification-list');
const notificationBadge = document.getElementById('notification-badge');

// Initialize Firebase references
function initializeFirebaseRefs(database) {
    notificationsRef = database.ref('notifications');
    kejadianRef = database.ref('kejadian');
    
    // Listen for notification changes
    listenToNotifications();
}

// Listen to notifications
function listenToNotifications() {
    notificationsRef.on('value', (snapshot) => {
        const notifications = snapshot.val() || {};
        updateNotificationsList(notifications);
        updateUnreadCount(notifications);
    });
}

// Update notifications list in the dropdown
function updateNotificationsList(notifications) {
    notificationsList.innerHTML = '';
    
    const notificationArray = Object.entries(notifications)
        .map(([id, notification]) => ({ id, ...notification }))
        .sort((a, b) => b.timestamp - a.timestamp);

    if (notificationArray.length === 0) {
        notificationsList.innerHTML = `
            <div class="p-4 text-center text-gray-500">
                No notifications
            </div>
        `;
        return;
    }

    notificationArray.forEach(notification => {
        const notificationElement = createNotificationElement(notification);
        notificationsList.appendChild(notificationElement);
    });
}

// Create notification element
function createNotificationElement(notification) {
    const div = document.createElement('div');
    div.className = `p-4 ${notification.read ? 'bg-white' : 'bg-blue-50'} hover:bg-gray-50 border-b border-gray-100`;
    
    // Create the notification content
    div.innerHTML = `
        <div class="flex items-start cursor-pointer" data-notification-id="${notification.id}" data-kejadian-id="${notification.kejadian_id}">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 ${notification.read ? 'text-gray-400' : 'text-blue-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-900">
                    ${notification.title}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    ${notification.message}
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    ${formatTimestamp(notification.timestamp)}
                </p>
            </div>
            ${!notification.read ? `
                <span class="ml-3 flex-shrink-0">
                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                </span>
            ` : ''}
        </div>
    `;

    // Add click event listener
    const notificationContent = div.querySelector('[data-notification-id]');
    notificationContent.addEventListener('click', async () => {
        const notificationId = notificationContent.dataset.notificationId;
        const kejadianId = notificationContent.dataset.kejadianId;
        
        try {
            // Remove the notification from Firebase
            await removeNotification(notificationId);
            
            // Redirect to the kejadian detail page
            window.location.href = `/admin/kejadian/${kejadianId}`;
        } catch (error) {
            console.error('Error handling notification click:', error);
        }
    });

    return div;
}

// Remove notification
async function removeNotification(notificationId) {
    try {
        await notificationsRef.child(notificationId).remove();
    } catch (error) {
        console.error('Error removing notification:', error);
        throw error;
    }
}

// Update unread count and badge
function updateUnreadCount(notifications) {
    unreadCount = Object.values(notifications).filter(n => !n.read).length;
    
    if (unreadCount > 0) {
        notificationBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
        notificationBadge.classList.remove('hidden');
    } else {
        notificationBadge.classList.add('hidden');
    }
}

// Format timestamp
function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    // Less than 1 minute
    if (diff < 60000) {
        return 'Just now';
    }
    
    // Less than 1 hour
    if (diff < 3600000) {
        const minutes = Math.floor(diff / 60000);
        return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
    }
    
    // Less than 1 day
    if (diff < 86400000) {
        const hours = Math.floor(diff / 3600000);
        return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    }
    
    // Less than 7 days
    if (diff < 604800000) {
        const days = Math.floor(diff / 86400000);
        return `${days} day${days > 1 ? 's' : ''} ago`;
    }
    
    // Format as date
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Export functions
window.initializeFirebaseRefs = initializeFirebaseRefs; 