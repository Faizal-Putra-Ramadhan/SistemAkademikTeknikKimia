@props(['active' => false])
<a {{ $attributes }} aria-current="{{ $active ? 'page' : false }}" 
          class="{{ $active ? 'bg-gray-950/50 text-base text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} block rounded-md  px-3 py-2 font-medium ">{{ $slot }}</a>
