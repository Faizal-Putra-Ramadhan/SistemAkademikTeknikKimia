@props(['active' => false])
<a {{ $attributes }} 
aria-current="{{ $active ? 'page' : false }}" 
class="{{ $active ? 'bg-gray-900 text-white text-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'}}
rounded-md  px-3 py-2  font-medium ">{{ $slot }}</a>