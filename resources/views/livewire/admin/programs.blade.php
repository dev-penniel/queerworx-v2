<?php

use App\Models\Program;
use App\Models\ProgramActivity;
use App\Models\ProgramActivityMedia;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $programName = '';
    public $programSummary = '';
    public $programSortOrder = 0;
    public $programCoverImage;
    public $programId = null;

    public $activityProgramId = '';
    public $activityTitle = '';
    public $activityDescription = '';
    public $activityDate = '';
    public $activityTime = '';
    public $activityVenue = '';
    public $activityStatus = 'upcoming';
    public $featuredImage;
    public $galleryFiles = [];
    public $pdf;
    public $activityId = null;

    public function getProgramsProperty()
    {
        return Program::withCount('activities')->orderBy('sort_order')->latest()->get();
    }

    public function getActivitiesProperty()
    {
        return ProgramActivity::with(['program', 'media'])->latest('activity_date')->latest()->get();
    }

    public function saveProgram(): void
    {
        $validated = $this->validate([
            'programName' => 'required|string|max:255',
            'programSummary' => 'nullable|string',
            'programSortOrder' => 'nullable|integer|min:0',
            'programCoverImage' => 'nullable|image|max:8192',
        ]);

        $program = $this->programId ? Program::findOrFail($this->programId) : new Program();

        if ($this->programCoverImage) {
            if ($program->cover_image_path) {
                Storage::disk('public')->delete($program->cover_image_path);
            }

            $program->cover_image_path = $this->programCoverImage->store('program-covers', 'public');
        }

        $program->fill([
            'name' => $validated['programName'],
            'summary' => $validated['programSummary'] ?: null,
            'sort_order' => $validated['programSortOrder'] ?? 0,
            'is_active' => true,
        ]);
        $program->save();

        $this->resetProgramForm();
        $this->dispatch('program-saved');
    }

    public function editProgram($id): void
    {
        $program = Program::findOrFail($id);

        $this->programId = $program->id;
        $this->programName = $program->name;
        $this->programSummary = $program->summary;
        $this->programSortOrder = $program->sort_order;
        $this->programCoverImage = null;
    }

    public function deleteProgram($id): void
    {
        $program = Program::with('activities.media')->findOrFail($id);

        Storage::disk('public')->delete(array_filter([$program->cover_image_path]));
        foreach ($program->activities as $activity) {
            Storage::disk('public')->delete(array_filter([
                $activity->image_path,
                $activity->video_path,
                $activity->pdf_path,
                $activity->featured_image_path,
            ]));
            foreach ($activity->media as $media) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        $program->delete();
        $this->dispatch('program-deleted');
    }

    public function saveActivity(): void
    {
        $validated = $this->validate([
            'activityProgramId' => 'required|exists:programs,id',
            'activityTitle' => 'required|string|max:255',
            'activityDescription' => 'nullable|string',
            'activityDate' => 'nullable|date',
            'activityTime' => 'nullable|date_format:H:i',
            'activityVenue' => 'nullable|string|max:255',
            'activityStatus' => 'required|in:upcoming,ongoing,completed',
            'featuredImage' => 'nullable|image|max:8192',
            'galleryFiles.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/quicktime,video/x-msvideo|max:51200',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $activity = $this->activityId ? ProgramActivity::findOrFail($this->activityId) : new ProgramActivity();

        if ($this->featuredImage) {
            Storage::disk('public')->delete(array_filter([$activity->featured_image_path, $activity->image_path]));
            $validated['featured_image_path'] = $this->featuredImage->store('program-activities', 'public');
        }

        if ($this->pdf) {
            Storage::disk('public')->delete(array_filter([$activity->pdf_path]));
            $validated['pdf_path'] = $this->pdf->store('program-activities', 'public');
        }

        $activity->fill([
            'program_id' => $validated['activityProgramId'],
            'title' => $validated['activityTitle'],
            'description' => $validated['activityDescription'] ?: null,
            'activity_date' => $validated['activityDate'] ?: null,
            'activity_time' => $validated['activityTime'] ?: null,
            'venue' => $validated['activityVenue'] ?: null,
            'status' => $validated['activityStatus'],
            'featured_image_path' => $validated['featured_image_path'] ?? $activity->featured_image_path,
            'image_path' => $validated['featured_image_path'] ?? $activity->image_path,
            'pdf_path' => $validated['pdf_path'] ?? $activity->pdf_path,
        ]);
        $activity->save();

        foreach ($this->galleryFiles as $index => $file) {
            $mime = $file->getMimeType();
            $type = str_starts_with($mime, 'video/') ? 'video' : 'image';

            ProgramActivityMedia::create([
                'program_activity_id' => $activity->id,
                'type' => $type,
                'file_path' => $file->store('program-activity-media', 'public'),
                'sort_order' => $index,
            ]);
        }

        $this->resetActivityForm();
        $this->dispatch('activity-saved');
    }

    public function editActivity($id): void
    {
        $activity = ProgramActivity::findOrFail($id);

        $this->activityId = $activity->id;
        $this->activityProgramId = $activity->program_id;
        $this->activityTitle = $activity->title;
        $this->activityDescription = $activity->description;
        $this->activityDate = optional($activity->activity_date)->format('Y-m-d');
        $this->activityTime = $activity->activity_time ? $activity->activity_time->format('H:i') : '';
        $this->activityVenue = $activity->venue;
        $this->activityStatus = $activity->status ?: 'upcoming';
        $this->featuredImage = null;
        $this->galleryFiles = [];
        $this->pdf = null;
    }

    public function deleteActivity($id): void
    {
        $activity = ProgramActivity::with('media')->findOrFail($id);

        Storage::disk('public')->delete(array_filter([
            $activity->image_path,
            $activity->video_path,
            $activity->pdf_path,
            $activity->featured_image_path,
        ]));
        foreach ($activity->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        $activity->delete();

        $this->dispatch('activity-deleted');
    }

    public function deleteMedia($id): void
    {
        $media = ProgramActivityMedia::findOrFail($id);
        Storage::disk('public')->delete(array_filter([$media->file_path]));
        $media->delete();
        $this->dispatch('media-deleted');
    }

    public function resetProgramForm(): void
    {
        $this->reset(['programName', 'programSummary', 'programSortOrder', 'programCoverImage', 'programId']);
        $this->programSortOrder = 0;
    }

    public function resetActivityForm(): void
    {
        $this->reset([
            'activityProgramId',
            'activityTitle',
            'activityDescription',
            'activityDate',
            'activityTime',
            'activityVenue',
            'activityStatus',
            'featuredImage',
            'galleryFiles',
            'pdf',
            'activityId',
        ]);
        $this->activityStatus = 'upcoming';
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Programs & Events') }}</flux:heading>
        <flux:breadcrumbs class="mb-4 mt-2">
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Programs & Events</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <form wire:submit="saveProgram" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ $programId ? 'Edit program' : 'Create program' }}</flux:heading>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <flux:input wire:model="programName" label="Program name" />
                <flux:input wire:model="programSortOrder" type="number" min="0" label="Display order" />
                <div class="sm:col-span-2">
                    <flux:textarea wire:model="programSummary" label="Short description" rows="4" />
                </div>
                <div class="sm:col-span-2">
                    <flux:input wire:model="programCoverImage" type="file" label="Program cover image" accept="image/*" />
                </div>

                <div class="flex items-center gap-3 sm:col-span-2">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Save Program</flux:button>
                    @if ($programId)
                        <flux:button type="button" wire:click="resetProgramForm" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    @endif
                    <x-action-message on="program-saved">{{ __('Saved.') }}</x-action-message>
                </div>
            </div>
        </form>

        <form wire:submit="saveActivity" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ $activityId ? 'Edit event' : 'Create event' }}</flux:heading>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <flux:select wire:model="activityProgramId" label="Program">
                    <flux:select.option value="">Choose program</flux:select.option>
                    @foreach ($this->programs as $program)
                        <flux:select.option value="{{ $program->id }}">{{ $program->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="activityStatus" label="Status">
                    <flux:select.option value="upcoming">Upcoming</flux:select.option>
                    <flux:select.option value="ongoing">Ongoing</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                </flux:select>
                <flux:input wire:model="activityDate" type="date" label="Date" />
                <flux:input wire:model="activityTime" type="time" label="Time" />
                <div class="sm:col-span-2">
                    <flux:input wire:model="activityVenue" label="Venue" />
                </div>
                <div class="sm:col-span-2">
                    <flux:input wire:model="activityTitle" label="Event title" />
                </div>
                <div class="sm:col-span-2">
                    <flux:textarea wire:model="activityDescription" label="Description" rows="4" />
                </div>
                <flux:input wire:model="featuredImage" type="file" label="Featured image" accept="image/*" />
                <flux:input wire:model="pdf" type="file" label="Event PDF" accept="application/pdf" />
                <div class="sm:col-span-2">
                    <flux:input wire:model="galleryFiles" type="file" label="Gallery photos and videos" accept="image/*,video/*" multiple />
                </div>

                <div class="flex items-center gap-3 sm:col-span-2">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Save Event</flux:button>
                    @if ($activityId)
                        <flux:button type="button" wire:click="resetActivityForm" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    @endif
                    <x-action-message on="activity-saved">{{ __('Saved.') }}</x-action-message>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[360px_1fr]">
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="border-b border-zinc-200 p-4 dark:border-zinc-700">
                <flux:heading size="lg">Programs</flux:heading>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->programs as $program)
                    <article class="p-4">
                        @if ($program->cover_image_path)
                            <img src="{{ Storage::url($program->cover_image_path) }}" alt="{{ $program->name }}" class="mb-3 aspect-video w-full rounded object-cover">
                        @endif
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-semibold">{{ $program->name }}</h3>
                                <p class="mt-1 text-sm text-zinc-500">Order {{ $program->sort_order }} · {{ $program->activities_count }} events</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" wire:click="editProgram({{ $program->id }})" @class(['inline-flex items-center gap-1 rounded px-2 py-1 text-[#14A84D]', 'bg-[#14A84D] font-semibold text-white' => $programId === $program->id])>
                                    <flux:icon.pencil-square class="size-5" />
                                    @if ($programId === $program->id)<span>Editing</span>@endif
                                </button>
                                <button type="button" wire:click="deleteProgram({{ $program->id }})" wire:confirm="Delete this program and all events?" class="text-[#E61E5C]"><flux:icon.trash class="size-5" /></button>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="p-4 text-sm text-zinc-500">No programs yet.</p>
                @endforelse
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-zinc-100 dark:bg-zinc-800">
                        <td class="px-5 py-3 text-sm font-bold">Event</td>
                        <td class="px-5 py-3 text-sm font-bold">Program</td>
                        <td class="px-5 py-3 text-sm font-bold">Date</td>
                        <td class="px-5 py-3 text-sm font-bold">Status</td>
                        <td class="px-5 py-3 text-sm font-bold">Media</td>
                        <td class="px-5 py-3 text-sm font-bold">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->activities as $activity)
                        <tr class="border-t border-zinc-200 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                            <td class="px-5 py-3 text-sm">{{ $activity->title }}</td>
                            <td class="px-5 py-3 text-sm">{{ $activity->program->name }}</td>
                            <td class="px-5 py-3 text-sm">{{ optional($activity->activity_date)->format('M d, Y') ?? 'Unscheduled' }}</td>
                            <td class="px-5 py-3 text-sm capitalize">{{ $activity->status }}</td>
                            <td class="px-5 py-3 text-sm">
                                <div class="flex flex-wrap gap-2">
                                    @if ($activity->featured_image_path || $activity->image_path)<span class="rounded bg-[#FFD83D] px-2 py-1 text-xs text-zinc-900">Featured</span>@endif
                                    @if ($activity->media_count ?? $activity->media->count())<span class="rounded bg-[#7646E8] px-2 py-1 text-xs text-white">{{ $activity->media->count() }} gallery</span>@endif
                                    @if ($activity->pdf_path)<span class="rounded bg-[#149CB9] px-2 py-1 text-xs text-white">PDF</span>@endif
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm">
                                <div class="flex gap-3">
                                    <button type="button" wire:click="editActivity({{ $activity->id }})" @class(['inline-flex items-center gap-1 rounded px-2 py-1 text-[#14A84D]', 'bg-[#14A84D] font-semibold text-white' => $activityId === $activity->id])>
                                        <flux:icon.pencil-square class="size-5" />
                                        @if ($activityId === $activity->id)<span>Editing</span>@endif
                                    </button>
                                    <button type="button" wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Delete this event?" class="text-[#E61E5C]"><flux:icon.trash class="size-5" /></button>
                                </div>
                            </td>
                        </tr>
                        @if ($activityId === $activity->id && $activity->media->isNotEmpty())
                            <tr>
                                <td colspan="6" class="px-5 py-3">
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($activity->media as $media)
                                            <button type="button" wire:click="deleteMedia({{ $media->id }})" wire:confirm="Remove this media file?" class="rounded border border-white/10 px-3 py-2 text-xs">
                                                Remove {{ $media->type }}
                                            </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-zinc-500">No events yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
