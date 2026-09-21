@extends('layouts.app')

@section('title', 'Create Poster & Media')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <h2 class="text-lg font-bold text-slate-900">Create Official Society Poster / Content</h2>
        <a href="{{ route('media.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Media</a>
    </div>

    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Content / Event Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Annual Friendship Meet & Cultural Gala"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Media Type *</label>
                <select name="media_type" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                    <option value="poster" {{ old('media_type') === 'poster' ? 'selected' : '' }}>Event Poster</option>
                    <option value="announcement" {{ old('media_type') === 'announcement' ? 'selected' : '' }}>Announcement Poster</option>
                    <option value="activity" {{ old('media_type') === 'activity' ? 'selected' : '' }}>Society Activity</option>
                    <option value="photo" {{ old('media_type') === 'photo' ? 'selected' : '' }}>Photo</option>
                    <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>Video Link</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Event Date</label>
                <input type="date" name="event_date" value="{{ old('event_date') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Event Time</label>
                <input type="time" name="event_time" value="{{ old('event_time') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Venue</label>
                <input type="text" name="venue" value="{{ old('venue') }}" placeholder="Location"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description & Details</label>
            <textarea name="description" rows="4" class="w-full p-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Poster Image</label>
            <input type="file" name="file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
        </div>

        <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-4">
            <button type="submit" name="action" value="draft" class="h-10 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-sm">
                Save Draft
            </button>
            <button type="submit" name="action" value="submit" class="h-10 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow">
                Submit for President Review
            </button>
        </div>
    </form>
</div>
@endsection
