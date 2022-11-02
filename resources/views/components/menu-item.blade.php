@props(['href','title'])

<li {{ $attributes->merge(['class'=> 'shadow-sm border-gray-200'])}}>
    <a href={{$href}} class="block p-4 hover:bg-gray-50">
        {{$title}}
    </a>
</li>