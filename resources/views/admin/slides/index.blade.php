@extends('layouts.admin')
@section('title', 'Slides')
@section('page-title', 'Hero Slides')

@section('content')
    <div class="toolbar">
        <span class="card-title" style="font-size: 16px;">All Slides ({{ $slides->total() }})</span>
        <a href="{{ route('admin.slides.create') }}" class="btn btn-primary">+ Add Slide</a>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr>
                    <th style="width: 50px;">#</th>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Badge</th>
                    <th>Button</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @forelse($slides as $slide)
                        <tr>
                            <td class="text-muted">{{ $slide->sort_order }}</td>
                            <td>
                                <div style="display: flex; gap: 4px;">
                                    <div style="width: 80px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, {{ $slide->bg_color_from }}, {{ $slide->bg_color_to }}); display: flex; align-items: center; justify-content: center; gap: 2px; padding: 4px;">
                                        @foreach(['image_1','image_2','image_3'] as $imgField)
                                            @if($slide->$imgField)
                                                <img src="{{ str_starts_with($slide->$imgField, 'http') ? $slide->$imgField : asset('storage/'.$slide->$imgField) }}" style="height: 28px; width: 20px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(255,255,255,0.5);">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong class="text-primary">{{ $slide->title_en }}</strong>
                                @if($slide->subtitle_en)
                                    <br><span style="font-size: 11px; color: var(--text-muted);">{{ Str::limit($slide->subtitle_en, 40) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($slide->badge_text)
                                    <span class="badge badge-pink">{{ $slide->badge_text }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary); font-size: 12px;">{{ $slide->button_text }} → {{ $slide->button_link }}</td>
                            <td>
                                <span class="badge {{ $slide->is_active ? 'badge-success' : 'badge-danger' }}">{{ $slide->is_active ? 'Active' : 'Hidden' }}</span>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.slides.edit', $slide->id) }}" class="link-action">Edit</a>
                                    <form method="POST" action="{{ route('admin.slides.destroy', $slide->id) }}" class="inline" onsubmit="return confirm('Delete this slide?')">
                                        @csrf @method('DELETE')
                                        <button class="link-action danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center" style="padding: 48px; color: var(--text-muted);">No slides yet. Create your first slide!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination">{{ $slides->links() }}</div>
@endsection
