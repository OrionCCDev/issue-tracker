import './bootstrap';// Only run this code if the user is authenticated
if (window.userId) {
    // Listen for issue creation in projects the user is a member of
    // You'll need to set up the 'window.userProjects' array in your Blade templates
    // This would contain IDs of projects the user is a member of
    if (window.userProjects && window.userProjects.length > 0) {
        window.userProjects.forEach(projectId => {
            window.Echo.private(`project.${projectId}`)
                .listen('.issue.created', (e) => {
                    showNotification(e);
                });
        });
    }
}

function showNotification(issue) {
    // Create notification element
    const notificationContainer = document.getElementById('notifications-container');
    if (!notificationContainer) return;

    const notification = document.createElement('div');
    notification.classList.add('notification', `priority-${issue.priority}`);

    // Format the notification content
    notification.innerHTML = `
        <div class="notification-header">
            <strong>${issue.title}</strong>
            <span class="notification-close">&times;</span>
        </div>
        <div class="notification-body">
            <p>New issue created by ${issue.creator.name}</p>
            <p class="priority">Priority: ${issue.priority}</p>
        </div>
    `;

    // Add to the container
    notificationContainer.appendChild(notification);

    // Add event listener to close button
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 5000);

    // Play a sound notification
    const notificationSound = new Audio('/sounds/notification.mp3');
    notificationSound.play();
}