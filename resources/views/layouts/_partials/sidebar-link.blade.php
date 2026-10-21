<a href="{{ route($item['route']) }}"
   class="sb-link flex items-center gap-3 p-3 rounded-xl transition-all group relative
          {{ $activo ? 'bg-[#1B7D8F]/10 text-[#1B7D8F] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1B7D8F]' }}"
   title="{{ $item['title'] }}"
   @if($activo) aria-current="page" @endif>
    @if($activo)
        <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-[#1B7D8F]"></span>
    @endif
    <i data-lucide="{{ $item['icon'] }}" class="w-6 h-6 flex-shrink-0"></i>
    <span class="link-text text-[13px] leading-tight font-medium hidden opacity-0 transition-opacity duration-300">{{ $item['title'] }}</span>
</a>
