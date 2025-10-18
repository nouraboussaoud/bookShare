@extends('layouts.app')

@section('title', 'BookShare - Notifications')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-bell text-primary mr-2"></i>
                Mes Notifications
            </h1>
            <p class="mb-0 text-gray-600">Centre de notifications pour vos échanges de livres</p>
        </div>
        <div class="d-flex gap-2">
            @if($notifications->where('is_read', false)->count() > 0)
                <button id="markAllRead" class="btn btn-outline-primary">
                    <i class="fas fa-check-circle mr-1"></i> Tout marquer comme lu
                </button>
            @endif
        </div>
    </div>

    <!-- Notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>
                        Notifications récentes
                        @if($notifications->where('is_read', false)->count() > 0)
                            <span class="badge badge-danger ml-2">{{ $notifications->where('is_read', false)->count() }} nouveau(x)</span>
                        @endif
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ !$notification->is_read ? 'notification-unread' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                @php
                                                    $iconClass = 'fas fa-bell text-primary';
                                                    $badgeClass = 'badge-primary';
                                                    
                                                    switch($notification->type) {
                                                        case 'exchange_request':
                                                            $iconClass = 'fas fa-handshake text-warning';
                                                            $badgeClass = 'badge-warning';
                                                            break;
                                                        case 'exchange_status_change':
                                                            $iconClass = 'fas fa-sync-alt text-info';
                                                            $badgeClass = 'badge-info';
                                                            break;
                                                    }
                                                @endphp
                                                
                                                <i class="{{ $iconClass }} mr-3"></i>
                                                <div>
                                                    <h6 class="mb-1 font-weight-bold">{{ $notification->title }}</h6>
                                                    <p class="mb-1 text-gray-700">{{ $notification->message }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            @if(!$notification->is_read)
                                                <span class="badge {{ $badgeClass }} mb-2">Nouveau</span>
                                            @endif
                                            <div class="btn-group-vertical">
                                                @if($notification->type === 'exchange_request' || $notification->type === 'exchange_status_change')
                                                    @php
                                                        $exchangeId = null;
                                                        if(is_array($notification->data) && isset($notification->data['exchange_id'])) {
                                                            $exchangeId = $notification->data['exchange_id'];
                                                        } elseif(is_string($notification->data)) {
                                                            $data = json_decode($notification->data, true);
                                                            $exchangeId = $data['exchange_id'] ?? null;
                                                        }
                                                    @endphp
                                                    
                                                    @if($exchangeId)
                                                        <a href="{{ route('exchanges.show', $exchangeId) }}" 
                                                           class="btn btn-sm btn-outline-primary mb-1"
                                                           onclick="markNotificationAsRead({{ $notification->id }})">
                                                            <i class="fas fa-eye mr-1"></i> Voir l'échange
                                                        </a>
                                                    @else
                                                        <a href="{{ route('exchanges.index') }}" 
                                                           class="btn btn-sm btn-outline-primary mb-1"
                                                           onclick="markNotificationAsRead({{ $notification->id }})">
                                                            <i class="fas fa-list mr-1"></i> Voir les échanges
                                                        </a>
                                                    @endif
                                                @endif
                                                @if(!$notification->is_read)
                                                    <button class="btn btn-sm btn-outline-success mb-1 mark-read-btn" 
                                                            data-id="{{ $notification->id }}">
                                                        <i class="fas fa-check mr-1"></i> Lu
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline-danger delete-notification-btn" 
                                                        data-id="{{ $notification->id }}">
                                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($notification->data && isset($notification->data['book_title']))
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small class="text-muted">
                                                <i class="fas fa-book mr-1"></i>
                                                Livre concerné: <strong>{{ $notification->data['book_title'] }}</strong>
                                                @if(isset($notification->data['initiator_name']))
                                                    | Demandeur: <strong>{{ $notification->data['initiator_name'] }}</strong>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center p-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-gray-300 mb-3"></i>
                            <h4 class="text-gray-600 mb-3">Aucune notification</h4>
                            <p class="text-gray-500 mb-4">
                                Vous n'avez pas encore de notifications.<br>
                                Les notifications apparaîtront ici lorsque des utilisateurs feront des demandes d'échange pour vos livres.
                            </p>
                            <a href="{{ route('exchanges.index') }}" class="btn btn-primary">
                                <i class="fas fa-exchange-alt mr-2"></i>Voir mes échanges
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .notification-unread {
        background-color: #f8f9fc;
        border-left: 4px solid #4e73df;
    }
    
    .notification-unread:hover {
        background-color: #eaecf4;
    }
    
    .list-group-item-action:hover {
        background-color: #f8f9fc;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Mark all as read
        $('#markAllRead').on('click', function() {
            $.ajax({
                url: '{{ route("notifications.markAllAsRead") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('Erreur lors de la mise à jour des notifications.');
                }
            });
        });

        // Mark single notification as read (using event delegation)
        document.addEventListener('click', function(event) {
            if (event.target.closest('.mark-read-btn')) {
                const button = event.target.closest('.mark-read-btn');
                const notificationId = button.getAttribute('data-id');
                
                console.log('Marking notification as read:', notificationId);
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const listItem = button.closest('.list-group-item');
                    listItem.classList.remove('notification-unread');
                    
                    // Remove the "Nouveau" badge
                    const badge = listItem.querySelector('.badge-primary, .badge-warning, .badge-info');
                    if (badge && badge.textContent.trim() === 'Nouveau') {
                        badge.remove();
                    }
                    
                    // Remove the "Lu" button
                    button.remove();
                    
                    // Update counter if no more unread notifications
                    const unreadCount = document.querySelectorAll('.notification-unread').length;
                    if (unreadCount === 0) {
                        const markAllBtn = document.getElementById('markAllRead');
                        if (markAllBtn) markAllBtn.style.display = 'none';
                        
                        const counterBadge = document.querySelector('.badge-danger');
                        if (counterBadge) counterBadge.remove();
                    } else {
                        // Update counter
                        const counterBadge = document.querySelector('.badge-danger');
                        if (counterBadge) {
                            counterBadge.textContent = `${unreadCount} nouveau(x)`;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la mise à jour: ' + error.message);
                });
            }
        });

        // Mark single notification as read (using event delegation)
        document.addEventListener('click', function(event) {
            if (event.target.closest('.mark-read-btn')) {
                event.preventDefault();
                const button = event.target.closest('.mark-read-btn');
                const notificationId = button.getAttribute('data-id');
                
                console.log('Marking single notification as read:', notificationId);
                
                // Get CSRF token from meta tag
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token not found in meta tags');
                    alert('Erreur: Token CSRF manquant');
                    return;
                }
                const csrfToken = csrfTokenMeta.getAttribute('content');
                console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');
                
                // Disable button during request
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> En cours...';
                
                fetch(`/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Response body:', text);
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const listItem = button.closest('.list-group-item');
                    listItem.classList.remove('notification-unread');
                    
                    // Remove the "Nouveau" badge
                    const badge = listItem.querySelector('.badge-primary, .badge-warning, .badge-info');
                    if (badge && badge.textContent.trim() === 'Nouveau') {
                        badge.remove();
                    }
                    
                    // Remove the "Lu" button
                    button.remove();
                    
                    // Update counter if no more unread notifications
                    const unreadCount = document.querySelectorAll('.notification-unread').length;
                    console.log('Remaining unread notifications:', unreadCount);
                    
                    if (unreadCount === 0) {
                        const markAllBtn = document.getElementById('markAllRead');
                        if (markAllBtn) markAllBtn.style.display = 'none';
                        
                        const counterBadge = document.querySelector('.badge-danger');
                        if (counterBadge) counterBadge.remove();
                    } else {
                        // Update counter
                        const counterBadge = document.querySelector('.badge-danger');
                        if (counterBadge) {
                            counterBadge.textContent = `${unreadCount} nouveau(x)`;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la mise à jour: ' + error.message);
                    
                    // Restore button
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-check mr-1"></i> Lu';
                });
            }
        });

        // Function to mark notification as read (called when clicking "Voir l'échange")
        function markNotificationAsRead(notificationId) {
            console.log('Marking notification as read (from link):', notificationId);
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/notifications/' + notificationId + '/mark-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Notification marquée comme lue:', data);
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour de la notification:', error);
            });
        }

        // Mark all notifications as read
        const markAllReadBtn = document.getElementById('markAllRead');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                if (!confirm('Êtes-vous sûr de vouloir marquer toutes les notifications comme lues ?')) {
                    return;
                }
                
                console.log('Marking all notifications as read');
                
                // Get CSRF token from meta tag
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token not found in meta tags');
                    alert('Erreur: Token CSRF manquant');
                    return;
                }
                const csrfToken = csrfTokenMeta.getAttribute('content');
                
                // Disable button during request
                const originalText = markAllReadBtn.innerHTML;
                markAllReadBtn.disabled = true;
                markAllReadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> En cours...';
                
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Response body:', text);
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    
                    // Remove unread styling from all notifications
                    document.querySelectorAll('.notification-unread').forEach(function(item) {
                        item.classList.remove('notification-unread');
                    });
                    
                    // Remove all "Lu" buttons and "Nouveau" badges
                    document.querySelectorAll('.mark-read-btn').forEach(function(btn) {
                        btn.remove();
                    });
                    document.querySelectorAll('.badge-primary, .badge-warning, .badge-info').forEach(function(badge) {
                        if (badge.textContent.trim() === 'Nouveau') {
                            badge.remove();
                        }
                    });
                    
                    // Hide the "Mark all as read" button
                    markAllReadBtn.style.display = 'none';
                    
                    // Update the counter in header
                    const counterBadge = document.querySelector('.badge-danger');
                    if (counterBadge) {
                        counterBadge.remove();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la mise à jour: ' + error.message);
                    
                    // Restore button
                    markAllReadBtn.disabled = false;
                    markAllReadBtn.innerHTML = originalText;
                });
            });
        }

        // Delete notification (using event delegation)
        document.addEventListener('click', function(event) {
            if (event.target.closest('.delete-notification-btn')) {
                event.preventDefault();
                const button = event.target.closest('.delete-notification-btn');
                
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
                    return;
                }
                
                const notificationId = button.getAttribute('data-id');
                const listItem = button.closest('.list-group-item');
                const wasUnread = listItem.classList.contains('notification-unread');
                
                console.log('Deleting notification:', notificationId);
                
                // Get CSRF token from meta tag
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token not found in meta tags');
                    alert('Erreur: Token CSRF manquant');
                    return;
                }
                const csrfToken = csrfTokenMeta.getAttribute('content');
                
                // Disable button during request
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Suppression...';
                
                fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Response body:', text);
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    listItem.style.transition = 'opacity 0.3s';
                    listItem.style.opacity = '0';
                    setTimeout(() => {
                        listItem.remove();
                        
                        // Update counter if it was unread
                        if (wasUnread) {
                            const unreadCount = document.querySelectorAll('.notification-unread').length;
                            console.log('Remaining unread after delete:', unreadCount);
                            
                            if (unreadCount === 0) {
                                const markAllBtn = document.getElementById('markAllRead');
                                if (markAllBtn) markAllBtn.style.display = 'none';
                                
                                const counterBadge = document.querySelector('.badge-danger');
                                if (counterBadge) counterBadge.remove();
                            } else {
                                const counterBadge = document.querySelector('.badge-danger');
                                if (counterBadge) {
                                    counterBadge.textContent = `${unreadCount} nouveau(x)`;
                                }
                            }
                        }
                    }, 300);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la suppression: ' + error.message);
                    
                    // Restore button
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-trash mr-1"></i> Supprimer';
                });
            }
        });
    });
</script>
@endpush