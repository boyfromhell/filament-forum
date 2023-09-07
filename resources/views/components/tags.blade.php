@foreach($tags as $t)
    <div class="w-full flex items-center text-slate-500 {{ $t->id == $tag ? 'font-medium' : '' }}">
        <span class="w-[25px] mr-2" style="color: {{ $t->color }}">
            {{ svg($t->icon) }}
        </span>
        <span style="{{ $t->id == $tag ? ('color: ' . $t->color) : '' }}">{{ $t->name }}</span>
    </div>
    @foreach($t->tags as $tt)
    <a href="{{ route('forum.tag', ['tag' => $tt, 'slug' => Str::slug($tt->name)]) }}" class="ml-4 w-full flex items-center hover:text-blue-500 text-slate-500 {{ $tt->id == $tag ? 'font-medium' : '' }}">
        <span class="w-[25px] mr-2" style="color: {{ $tt->color }}">
            {{ svg($tt->icon) }}
        </span>
        <span style="{{ $tt->id == $tag ? ('color: ' . $tt->color) : '' }}">{{ $tt->name }}</span>
    </a>
    @endforeach
@endforeach
