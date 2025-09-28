@props(['user', 'exchange' => null, 'size' => 'sm', 'text' => false])

@if(auth()->check() && auth()->id() !== $user->id)
    @php
        $queryParams = ['reported_user_id' => $user->id];
        if ($exchange) {
            $queryParams['exchange_id'] = $exchange->id;
        }
        $url = route('reports.create', $queryParams);
        $btnClass = 'btn btn-warning' . ($size ? ' btn-' . $size : '');
        $title = 'Signaler ' . ($exchange ? 'un problème avec cet échange' : 'cet utilisateur');
    @endphp
    
    <a href="{{ $url }}" class="{{ $btnClass }}" title="{{ $title }}">
        <i class="fas fa-flag{{ $text ? ' mr-1' : '' }}"></i>
        @if($text)
            Signaler
        @endif
    </a>
@endif