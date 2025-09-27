@extends('layouts.layout')

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
                                                    <a href="{{ route('notifications.markAsRead', $notification->id) }}" 
                                                       class="btn btn-sm btn-outline-primary mb-1">
                                                        <i class="fas fa-eye mr-1"></i> Voir l'échange
                                                    </a>
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

        // Mark single notification as read
        $('.mark-read-btn').on('click', function() {
            const notificationId = $(this).data('id');
            const button = $(this);
            
            $.ajax({
                url: `/notifications/${notificationId}/mark-read`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    button.closest('.list-group-item').removeClass('notification-unread');
                    button.remove();
                },
                error: function() {
                    alert('Erreur lors de la mise à jour.');
                }
            });
        });

        // Delete notification
        $('.delete-notification-btn').on('click', function() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
                return;
            }
            
            const notificationId = $(this).data('id');
            const listItem = $(this).closest('.list-group-item');
            
            $.ajax({
                url: `/notifications/${notificationId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    listItem.fadeOut(300, function() {
                        $(this).remove();
                    });
                },
                error: function() {
                    alert('Erreur lors de la suppression.');
                }
            });
        });
    });
</script>
@endpush