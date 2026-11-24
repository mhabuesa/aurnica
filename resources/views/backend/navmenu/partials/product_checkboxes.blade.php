@forelse ($products as $product)
    @php
        $isChecked =
            ($assignedProductIds ?? collect())->contains((int) $product->id) ||
            in_array((int) $product->id, old('products', []));
    @endphp
    <div class="border rounded px-2 py-1 my-2 bg-white">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="product_{{ $product->id }}" name="products[]"
                value="{{ $product->id }}" {{ $isChecked ? 'checked' : '' }}>
            <label class="form-check-label mb-0 w-100" for="product_{{ $product->id }}">
                {{ $product->name }}
            </label>
        </div>
    </div>
@empty
    <div class="text-center text-muted py-3">
        No products found.
    </div>
@endforelse
