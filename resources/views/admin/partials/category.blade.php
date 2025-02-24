<li data-id="{{ $category->id }}" class="list-group-item">
    <div class="d-flex justify-content-between align-items-center">
        <span class="d-flex justify-content-between align-items-center">
            <i class="drag-handle cursor-move ti ti-menu-2 align-text-bottom me-2"></i>
            <span>{{ $category->name }}</span>
        </span>
        <div class="tw-flex">
            <a href="{{ route('admin.manuscript.category.edit', $category->id) }}">
                <i class="text-primary ti ti-edit" style="font-size: 20px;"></i>
            </a>
            <a href="javascript:void(0);" id="{{ $category->id }}" class="delete-record">
                <i class="text-danger ti ti-trash" style="font-size: 20px;"></i>
            </a>
        </div>
    </div>
    @if ($category->children->isNotEmpty())
        <ul class="list-group list-group-flush nested-sortable">
            @foreach ($category->children as $child)
                @include('admin.partials.category', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>

{{-- <li class="list-group-item d-flex justify-content-between align-items-center">
    <span class="d-flex justify-content-between align-items-center">
        <i class="drag-handle cursor-move ti ti-menu-2 align-text-bottom me-2"></i>
        <span>Buy products</span>
    </span>
    <img class="rounded-circle" src="../../assets/img/avatars/1.png"
        alt="avatar" height="32" width="32" />
</li> --}}