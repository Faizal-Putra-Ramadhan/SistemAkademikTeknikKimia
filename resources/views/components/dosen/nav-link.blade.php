@props(['active' => false])
<a {{ $attributes }} 
aria-current="{{ $active ? 'page' : false }}" 
class="{{ $active ? 'bg-gray-900 text-white text-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'}}
rounded-md  px-3 py-2  font-medium ">{{ $slot }}</a>