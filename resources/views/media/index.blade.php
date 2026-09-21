@extends('layouts.app')

@section('title', 'Posters & Activity Content')

@section('content')
<div class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('media.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-3">
            <select name="media_type" class="custom-select h-10 px-3 border border-slate-300 rounded-xl text-xs bg-white text-slate-700">
                <option value="">All Media Types</option>
                <option value="poster" {{ request('media_type') === 'poster' ? 'selected' : '' }}>Poster</option>
                <option value="photo" {{ request('media_type') === 'photo' ? 'selected' : '' }}>Photo</option>
                <option value="video" {{ request('media_type') === 'video' ? 'selected' : '' }}>Video</option>
                <option value="announcement" {{ request('media_type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                <option value="activity" {{ request('media_type') === 'activity' ? 'selected' : '' }}>Society Activity</option>
            </select>
            <button type="submit" class="h-10 px-4 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-all shadow-sm">
                Filter
            </button>
        </form>

        @can('create', App\Models\PosterAndMedia::class)
        <a href="{{ route('media.create') }}" class="h-10 px-4 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-xl text-xs shadow-md shadow-teal-600/10 flex items-center transition-all whitespace-nowrap">
            + Create Poster / Media
        </a>
        @endcan
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Media Title</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Event Date / Venue</th>
                        <th class="px-6 py-3.5">Workflow Status</th>
                        <th class="px-6 py-3.5">Created By</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($mediaItems as $media)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            <a href="{{ route('media.show', $media) }}" class="hover:text-teal-600">{{ $media->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold uppercase text-teal-700">
                            {{ $media->media_type }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            <p>{{ $media->event_date ? $media->event_date->format('M d, Y') : 'N/A' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $media->venue ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full {{ 
                                match($media->status) {
                                    'approved', 'published' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                    'submitted', 'under_review' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                    'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                    default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                }
                            }}">
                                {{ str_replace('_', ' ', $media->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            {{ $media->submitter?->name }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('media.show', $media) }}" class="h-8 px-3 inline-flex items-center bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-lg font-bold text-xs transition-colors">
                                View Content ➔
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No posters or media items found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $mediaItems->links() }}
        </div>
    </div>
</div>
@endsection
