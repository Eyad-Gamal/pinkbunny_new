@extends('layouts.admin')
@section('title', $slide ? 'Edit Slide' : 'Create Slide')
@section('page-title', $slide ? 'Edit Slide' : 'New Slide')

@section('content')
    @if($errors->any())
        <div class="form-error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $slide ? route('admin.slides.update', $slide->id) : route('admin.slides.store') }}" enctype="multipart/form-data" style="max-width: 1000px;">
        @csrf
        @if($slide) @method('PUT') @endif

        {{-- Live Preview --}}
        <div class="card mb-3" id="previewCard">
            <div class="card-header"><span class="card-title">Live Preview</span></div>
            <div style="padding: 16px;">
                <div id="slidePreview" style="border-radius: 16px; padding: 32px; position: relative; overflow: hidden; min-height: 200px; display: flex; align-items: center; transition: background 0.3s;"
                     data-from="{{ old('bg_color_from', $slide->bg_color_from ?? '#BAE6FD') }}"
                     data-to="{{ old('bg_color_to', $slide->bg_color_to ?? '#FFB5C5') }}">
                    <div>
                        <span id="prevBadge" style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: rgba(255,255,255,0.8); font-size: 12px; font-weight: 700;"></span>
                        <h2 id="prevTitle" style="font-size: 28px; font-weight: 800; margin-top: 8px; line-height: 1.2;"></h2>
                        <p id="prevSubtitle" style="font-size: 14px; margin-top: 6px; opacity: 0.8;"></p>
                        <span id="prevBtn" style="display: inline-block; margin-top: 12px; padding: 8px 20px; border-radius: 20px; font-size: 13px; font-weight: 700;"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid-2" style="gap: 20px;">
            {{-- Left Column: Content --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Content</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Badge Text</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $slide?->badge_text) }}" class="form-input" placeholder="✨ New Arrivals" id="badgeInput">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Title (English) *</label>
                            <input type="text" name="title_en" value="{{ old('title_en', $slide?->title_en) }}" class="form-input" required id="titleInput">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Title (Arabic)</label>
                            <input type="text" name="title_ar" value="{{ old('title_ar', $slide?->title_ar) }}" class="form-input" dir="rtl">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Subtitle (English)</label>
                            <input type="text" name="subtitle_en" value="{{ old('subtitle_en', $slide?->subtitle_en) }}" class="form-input" id="subtitleInput">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subtitle (Arabic)</label>
                            <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar', $slide?->subtitle_ar) }}" class="form-input" dir="rtl">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Button Text *</label>
                            <input type="text" name="button_text" value="{{ old('button_text', $slide?->button_text ?? 'Shop Now') }}" class="form-input" required id="btnTextInput">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Button Link *</label>
                            <input type="text" name="button_link" value="{{ old('button_link', $slide?->button_link ?? '/products') }}" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $slide?->sort_order ?? 0) }}" class="form-input" min="0">
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-end; padding-bottom: 4px;">
                            <label class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide?->is_active ?? true) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Colors & Images --}}
            <div>
                {{-- Colors --}}
                <div class="card mb-3">
                    <div class="card-header"><span class="card-title">Colors</span></div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                            <div class="form-group">
                                <label class="form-label">Gradient From</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="color" name="bg_color_from" value="{{ old('bg_color_from', $slide?->bg_color_from ?? '#BAE6FD') }}" style="width: 40px; height: 36px; border-radius: 8px; border: 1px solid var(--border-strong); cursor: pointer; background: transparent;" id="colorFrom">
                                    <input type="text" value="{{ old('bg_color_from', $slide?->bg_color_from ?? '#BAE6FD') }}" class="form-input" style="font-family: monospace; font-size: 12px;" id="colorFromText" onchange="document.getElementById('colorFrom').value=this.value; updatePreview()">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Gradient To</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="color" name="bg_color_to" value="{{ old('bg_color_to', $slide?->bg_color_to ?? '#FFB5C5') }}" style="width: 40px; height: 36px; border-radius: 8px; border: 1px solid var(--border-strong); cursor: pointer; background: transparent;" id="colorTo">
                                    <input type="text" value="{{ old('bg_color_to', $slide?->bg_color_to ?? '#FFB5C5') }}" class="form-input" style="font-family: monospace; font-size: 12px;" id="colorToText" onchange="document.getElementById('colorTo').value=this.value; updatePreview()">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="color" name="text_color" value="{{ old('text_color', $slide?->text_color ?? '#0F172A') }}" style="width: 40px; height: 36px; border-radius: 8px; border: 1px solid var(--border-strong); cursor: pointer; background: transparent;" id="colorText">
                                    <input type="text" value="{{ old('text_color', $slide?->text_color ?? '#0F172A') }}" class="form-input" style="font-family: monospace; font-size: 12px;" id="colorTextText" onchange="document.getElementById('colorText').value=this.value; updatePreview()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="card">
                    <div class="card-header"><span class="card-title">Images (3 floating product images)</span></div>
                    <div class="card-body">
                        @foreach([1,2,3] as $i)
                            <div class="form-group">
                                <label class="form-label">Image {{ $i }} {{ $i === 2 ? '(Main/Center)' : ($i === 1 ? '(Left)' : '(Right)') }}</label>
                                <input type="file" name="image_{{ $i }}" class="form-input" accept="image/*">
                                @php $imgField = "image_$i"; @endphp
                                @if($slide?->$imgField)
                                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                                        <img src="{{ str_starts_with($slide->$imgField, 'http') ? $slide->$imgField : asset('storage/'.$slide->$imgField) }}" style="width: 48px; height: 64px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                                        <span style="font-size: 11px; color: var(--text-muted);">Current image</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-1 mt-3">
            <button type="submit" class="btn btn-primary">{{ $slide ? 'Update Slide' : 'Create Slide' }}</button>
            <a href="{{ route('admin.slides.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>

    <script>
        function updatePreview() {
            const preview = document.getElementById('slidePreview');
            const from = document.getElementById('colorFrom').value;
            const to = document.getElementById('colorTo').value;
            const text = document.getElementById('colorText').value;

            preview.style.background = `linear-gradient(135deg, ${from}, ${to})`;
            preview.style.color = text;

            document.getElementById('colorFromText').value = from;
            document.getElementById('colorToText').value = to;
            document.getElementById('colorTextText').value = text;

            document.getElementById('prevBadge').textContent = document.getElementById('badgeInput').value;
            document.getElementById('prevTitle').textContent = document.getElementById('titleInput').value || 'Your Title Here';
            document.getElementById('prevSubtitle').textContent = document.getElementById('subtitleInput').value;
            document.getElementById('prevBtn').textContent = document.getElementById('btnTextInput').value || 'Shop Now';
            document.getElementById('prevBtn').style.background = text;
            document.getElementById('prevBtn').style.color = from;
        }

        // Bind all inputs
        ['colorFrom','colorTo','colorText'].forEach(id => {
            document.getElementById(id).addEventListener('input', updatePreview);
        });
        ['badgeInput','titleInput','subtitleInput','btnTextInput'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', updatePreview);
        });

        // Initial render
        updatePreview();
    </script>
@endsection
