@extends('layouts.admin')

@section('title', 'Edit Item')
@section('header', 'Edit: ' . $item->name)
@section('subheader', 'Modify product details and pricing options')

@section('content')
    <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        @if ($errors->any())
            <div style="background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.2); color: #ff6b6b; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px;">
            <!-- Left Side: Basic Info -->
            <div class="stat-card">
                <h3 style="margin-bottom: 25px; font-weight: 700;">General Information</h3>
                
                <div class="form-group">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" style="height: 100px; resize: none;">{{ $item->description }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Store</label>
                        <select name="store_id" class="form-control" required>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $item->store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category / Sub-Category</label>
                        <select name="sub_category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->subCategories as $sub)
                                        <option value="{{ $sub->id }}" {{ $item->sub_category_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Base Price (BHD)</label>
                    <input type="number" name="base_price" step="0.001" class="form-control" value="{{ $item->base_price }}" required>
                </div>
            </div>

            <!-- Right Side: Media & Status -->
            <div>
                <div class="stat-card" style="margin-bottom: 30px;">
                    <h3 style="margin-bottom: 25px; font-weight: 700;">Product Media</h3>
                    <div id="image-preview" style="width: 100%; height: 200px; background: var(--dark-alt); border-radius: 20px; border: 2px dashed var(--border); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        @if($item->image_path)
                            <img src="{{ asset($item->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted);">add_a_photo</span>
                        @endif
                    </div>
                    <input type="file" name="image" id="image-input" style="display: none;" accept="image/*">
                    <button type="button" onclick="document.getElementById('image-input').click()" class="btn-primary" style="width: 100%; margin-top: 15px; background: rgba(255,255,255,0.05); color: var(--text);">Change Photo</button>
                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 10px; text-align: center;">Click to change image (Max 2MB)</p>
                </div>

                <div class="stat-card">
                    <h3 style="margin-bottom: 25px; font-weight: 700;">Availability</h3>
                    <label class="remember-me" style="margin-bottom: 15px;">
                        <input type="checkbox" name="is_available" {{ $item->is_available ? 'checked' : '' }} style="display: none;">
                        <div class="checkbox-custom"></div>
                        <span class="remember-text">Available for customers to order</span>
                    </label>

                    <label class="remember-me">
                        <input type="checkbox" name="is_weekly_favorite" {{ $item->is_weekly_favorite ? 'checked' : '' }} style="display: none;">
                        <div class="checkbox-custom" style="border-color: #ffd700;"></div>
                        <span class="remember-text">Weekly Favorite (Featured)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Options / Attributes Builder -->
        <div class="stat-card" style="margin-top: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="font-weight: 700;">Product Options</h3>
                <button type="button" onclick="addAttribute()" class="btn-primary" style="width: auto; padding: 8px 16px; font-size: 13px;">+ Add Group</button>
            </div>

            <div id="attributes-container">
                @foreach($item->attributes as $index => $attribute)
                    <div class="attribute-group" id="attr-{{ $index }}" style="background: var(--dark-alt); padding: 25px; border-radius: 20px; border: 1px solid var(--border); margin-bottom: 20px;">
                        <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label class="form-label" style="font-size: 11px;">Group Name</label>
                                <input type="text" name="attributes[{{ $index }}][name]" class="form-control" value="{{ $attribute->name }}" required>
                            </div>
                            <button type="button" onclick="removeAttribute({{ $index }})" style="background: none; border: none; color: #ff6b6b; cursor: pointer; margin-top: 25px;">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                        
                        <div id="options-{{ $index }}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding-left: 20px; border-left: 2px solid var(--primary);">
                            @foreach($attribute->options as $optIndex => $option)
                                <div style="display: flex; gap: 10px; align-items: center; background: var(--dark); padding: 10px; border-radius: 12px; border: 1px solid var(--border);">
                                    <input type="text" name="attributes[{{ $index }}][options][{{ $optIndex }}][value]" class="form-control" value="{{ $option->value }}" required style="font-size: 13px;">
                                    <input type="number" name="attributes[{{ $index }}][options][{{ $optIndex }}][price]" step="0.001" class="form-control" value="{{ $option->price_adjustment }}" style="width: 120px; font-size: 13px;">
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addOption({{ $index }})" style="background: none; border: none; color: var(--primary); cursor: pointer; font-size: 12px; font-weight: 700; margin-top: 15px; display: flex; align-items: center; gap: 5px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">add</span> Add Option
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 40px; display: flex; gap: 20px;">
            <button type="submit" class="btn-primary" style="width: 200px;">Update Product</button>
            <a href="{{ route('admin.items.index') }}" class="btn-primary" style="width: 200px; background: rgba(255, 255, 255, 0.05); color: var(--text); text-align: center; text-decoration: none;">Discard Changes</a>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    // Existing script logic same as create, but with initialized counts
    document.getElementById('image-input').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        }
    });

    let attributeCount = {{ $item->attributes->count() }};
    let optionCounts = {
        @foreach($item->attributes as $index => $attribute)
            {{ $index }}: {{ $attribute->options->count() }},
        @endforeach
    };

    function addAttribute() {
        const id = attributeCount++;
        const html = `
            <div class="attribute-group" id="attr-${id}" style="background: var(--dark-alt); padding: 25px; border-radius: 20px; border: 1px solid var(--border); margin-bottom: 20px;">
                <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label class="form-label" style="font-size: 11px;">Group Name</label>
                        <input type="text" name="attributes[${id}][name]" class="form-control" required>
                    </div>
                    <button type="button" onclick="removeAttribute(${id})" style="background: none; border: none; color: #ff6b6b; cursor: pointer; margin-top: 25px;">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
                <div id="options-${id}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding-left: 20px; border-left: 2px solid var(--primary);"></div>
                <button type="button" onclick="addOption(${id})" style="background: none; border: none; color: var(--primary); cursor: pointer; font-size: 12px; font-weight: 700; margin-top: 15px; display: flex; align-items: center; gap: 5px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">add</span> Add Option
                </button>
            </div>
        `;
        document.getElementById('attributes-container').insertAdjacentHTML('beforeend', html);
        addOption(id);
    }

    function addOption(attrId) {
        if (!optionCounts[attrId]) optionCounts[attrId] = 0;
        const optId = optionCounts[attrId]++;
        const html = `
            <div style="display: flex; gap: 10px; align-items: center; background: var(--dark); padding: 10px; border-radius: 12px; border: 1px solid var(--border);">
                <input type="text" name="attributes[${attrId}][options][${optId}][value]" class="form-control" placeholder="Option" required style="font-size: 13px;">
                <input type="number" name="attributes[${attrId}][options][${optId}][price]" step="0.001" class="form-control" placeholder="+0.000" style="width: 120px; font-size: 13px;">
            </div>
        `;
        document.getElementById(`options-${attrId}`).insertAdjacentHTML('beforeend', html);
    }

    function removeAttribute(id) {
        document.getElementById(`attr-${id}`).remove();
    }
</script>
<style>
    .form-control:focus { border-color: var(--primary); }
</style>
@endsection
