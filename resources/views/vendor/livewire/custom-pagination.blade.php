@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; direction: rtl;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #666; font-size: 0.9rem;">
                عرض <span style="font-weight: bold; color: #2c3e50;">{{ $paginator->firstItem() }}</span> إلى <span style="font-weight: bold; color: #2c3e50;">{{ $paginator->lastItem() }}</span> من <span style="font-weight: bold; color: #2c3e50;">{{ $paginator->total() }}</span> نتيجة
            </span>
        </div>

        <div style="display: flex; gap: 5px; align-items: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="padding: 8px 16px; background: #ecf0f1; color: #95a5a6; border-radius: 8px; cursor: not-allowed; font-size: 0.9rem;">
                    <i class="fas fa-chevron-right"></i> السابق
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" style="padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                    <i class="fas fa-chevron-right"></i> السابق
                </button>
            @endif

            {{-- Pagination Elements --}}
            <div style="display: flex; gap: 5px;">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span style="padding: 8px 12px; color: #95a5a6; font-size: 0.9rem;">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span wire:key="paginator-page-{{ $page }}" style="padding: 8px 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; font-weight: bold; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);">
                                    {{ $page }}
                                </span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" wire:key="paginator-page-{{ $page }}" style="padding: 8px 14px; background: white; color: #2c3e50; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#667eea'" onmouseout="this.style.background='white'; this.style.borderColor='#e0e0e0'">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" style="padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                    التالي <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <span style="padding: 8px 16px; background: #ecf0f1; color: #95a5a6; border-radius: 8px; cursor: not-allowed; font-size: 0.9rem;">
                    التالي <i class="fas fa-chevron-left"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
