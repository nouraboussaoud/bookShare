@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@elseif (trim($slot) === 'BookShare')
<div style="font-size: 28px; font-weight: bold; color: #667eea;">
    <span style="font-size: 32px;">📚</span> BookShare
</div>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
