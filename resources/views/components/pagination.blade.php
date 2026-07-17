@if($items->hasPages())
    <div class="d-flex justify-content-between align-items-center">
        <div>
            Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} 
            of {{ $items->total() }} entries
        </div>
        <div>
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>
@endif