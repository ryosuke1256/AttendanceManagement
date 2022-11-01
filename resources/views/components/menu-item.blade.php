@props(['id','title','href'])

{{-- TODO data → role? --}}
<div id={{$id}} data="menu-item" class="shadow-sm border-gray-200">
    <a href={{$href}} class="block p-4 hover:bg-gray-50">
        {{$title}}
    </a>
</div>