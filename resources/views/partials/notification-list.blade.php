@php
  $notifications = $notifications ?? collect();
  $unreadCount = $unreadCount ?? 0;
  $containerTag = ($asList ?? true) ? 'ul' : 'div';
  $itemTag = ($asList ?? true) ? 'li' : 'div';
  $containerClasses = $containerClasses ?? 'dropdown-menu dropdown-menu-end p-0';
  $containerAttrs = $containerAttrs ?? [];
  $style = $style ?? 'max-height: 350px; overflow-y: auto;';
@endphp

<{{ $containerTag }}
  class="{{ $containerClasses }}"
  style="{{ $style }}"
  @foreach($containerAttrs as $attrName => $attrValue)
    {{ $attrName }}="{{ $attrValue }}"
  @endforeach
>
  <{{ $itemTag }} class="dropdown-header bg-light fw-bold text-center py-2 d-flex justify-content-between align-items-center px-3">
    <span>Thông báo</span>
    @if($unreadCount > 0)
      <button class="btn btn-sm btn-outline-secondary mark-all-btn" data-mark-all-url="{{ route('notifications.markAll') }}">Đánh dấu tất cả</button>
    @endif
  </{{ $itemTag }}>

  @if($notifications->count() > 0)
    @foreach($notifications as $notify)
      <{{ $itemTag }}>
        <a href="{{ $notify->link ?? '#' }}"
          class="dropdown-item small py-2 border-bottom d-flex align-items-start notification-item"
          data-mark-url="{{ route('notifications.markRead', $notify->id) }}">
          <i class="fas fa-circle notification-dot text-{{ $notify->read_at ? 'secondary' : 'primary' }} me-2 mt-1" style="font-size:10px;"></i>
          <div>
            <div class="fw-semibold">{{ $notify->title }}</div>
            <div class="text-muted small">{{ $notify->created_at?->diffForHumans() }}</div>
          </div>
        </a>
      </{{ $itemTag }}>
    @endforeach
  @else
    <{{ $itemTag }} class="dropdown-item text-center text-muted py-3">Chưa có thông báo nào</{{ $itemTag }}>
  @endif
</{{ $containerTag }}>
