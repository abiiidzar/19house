<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5 border border-neutral-200 bg-white p-6">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <div class="grid gap-5 md:grid-cols-2">
        <label class="block text-sm">Label kecil<input name="eyebrow" value="{{ old('eyebrow', $section?->eyebrow) }}" maxlength="100" class="mt-1 block w-full border p-3"></label>
        <label class="block text-sm">Judul *<input name="title" value="{{ old('title', $section?->title) }}" maxlength="180" required class="mt-1 block w-full border p-3"></label>
    </div>
    <label class="block text-sm">Deskripsi singkat<textarea name="subtitle" rows="2" maxlength="500" class="mt-1 block w-full border p-3">{{ old('subtitle', $section?->subtitle) }}</textarea></label>
    <div class="grid gap-5 md:grid-cols-2">
        <label class="block text-sm">Teks tombol<input name="button_label" value="{{ old('button_label', $section?->button_label) }}" maxlength="60" placeholder="DISCOVER" class="mt-1 block w-full border p-3"></label>
        <label class="block text-sm">Tujuan tombol (path internal)<input name="button_path" value="{{ old('button_path', $section?->button_path) }}" maxlength="255" placeholder="/shop" class="mt-1 block w-full border p-3"></label>
    </div>
    <div class="grid gap-5 md:grid-cols-2">
        <div><label class="block text-sm">Foto desktop {{ $mode === 'create' ? '*' : '' }}<input type="file" name="image" accept="image/jpeg,image/png,image/webp" {{ $mode === 'create' ? 'required' : '' }} class="mt-1 block w-full border p-3"></label>
            @if($section?->image_path)<img src="{{ Storage::disk('public')->url($section->image_path) }}" alt="Foto desktop {{ $section->title }}" class="mt-3 h-36 w-full object-cover"><label class="mt-2 flex gap-2 text-xs"><input type="checkbox" name="remove_image" value="1">Hapus foto desktop</label>@endif
        </div>
        <div><label class="block text-sm">Foto mobile (opsional)<input type="file" name="mobile_image" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full border p-3"></label>
            @if($section?->mobile_image_path)<img src="{{ Storage::disk('public')->url($section->mobile_image_path) }}" alt="Foto mobile {{ $section->title }}" class="mt-3 h-36 w-28 object-cover"><label class="mt-2 flex gap-2 text-xs"><input type="checkbox" name="remove_mobile_image" value="1">Hapus foto mobile</label>@endif
        </div>
    </div>
    <div class="grid gap-5 md:grid-cols-4">
        <label class="block text-sm">Posisi teks<select name="text_position" class="mt-1 block w-full border p-3"><option value="left" @selected(old('text_position', $section?->text_position ?? 'left') === 'left')>Kiri</option><option value="center" @selected(old('text_position', $section?->text_position) === 'center')>Tengah</option></select></label>
        <label class="block text-sm">Warna teks<select name="text_color" class="mt-1 block w-full border p-3"><option value="light" @selected(old('text_color', $section?->text_color ?? 'light') === 'light')>Terang</option><option value="dark" @selected(old('text_color', $section?->text_color) === 'dark')>Gelap</option></select></label>
        @if($mode !== 'hero')<label class="block text-sm">Urutan<input type="number" name="sort_order" min="0" max="999" value="{{ old('sort_order', $section?->sort_order ?? 0) }}" class="mt-1 block w-full border p-3"></label>@endif
        <label class="block text-sm">Status<select name="is_active" class="mt-1 block w-full border p-3"><option value="1" @selected((int) old('is_active', $section?->is_active ?? 1) === 1)>Aktif</option><option value="0" @selected((int) old('is_active', $section?->is_active ?? 1) === 0)>Nonaktif</option></select></label>
    </div>
    <p class="text-xs text-neutral-500">JPG, PNG, atau WEBP; maksimal 8 MB per foto. Rekomendasi desktop 2000×1200 px dan mobile 900×1200 px. Tombol hanya menerima path situs seperti /shop atau /collections/nama.</p>
    @if($errors->any())<ul class="text-sm text-red-600">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
    <button type="submit" class="bg-neutral-900 px-6 py-3 text-sm uppercase tracking-wider text-white">{{ $mode === 'create' ? 'Tambah editorial' : 'Simpan perubahan' }}</button>
</form>
