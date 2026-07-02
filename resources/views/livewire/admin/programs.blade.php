<?php

use App\Models\Program;
use App\Models\ProgramActivity;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $programName = '';
    public $programSummary = '';
    public $programId = null;

    public $activityProgramId = '';
    public $activityTitle = '';
    public $activityDescription = '';
    public $activityDate = '';
    public $image;
    public $video;
    public $pdf;
    public $activityId = null;

    public function getProgramsProperty()
    {
        return Program::withCount('activities')->latest()->get();
    }

    public function getActivitiesProperty()
    {
        return ProgramActivity::with('program')->latest('activity_date')->latest()->get();
    }

    public function saveProgram(): void
    {
        $validated = $this->validate([
            'programName' => 'required|string|max:255',
            'programSummary' => 'nullable|string',
        ]);

        Program::updateOrCreate(
            ['id' => $this->programId],
            [
                'name' => $validated['programName'],
                'summary' => $validated['programSummary'],
                'is_active' => true,
            ]
        );

        $this->resetProgramForm();
        $this->dispatch('program-saved');
    }

    public function editProgram($id): void
    {
        $program = Program::findOrFail($id);

        $this->programId = $program->id;
        $this->programName = $program->name;
        $this->programSummary = $program->summary;
    }

    public function deleteProgram($id): void
    {
        $program = Program::with('activities')->findOrFail($id);

        foreach ($program->activities as $activity) {
            Storage::disk('public')->delete(array_filter([$activity->image_path, $activity->video_path, $activity->pdf_path]));
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
            'image' => 'nullable|image|max:8192',
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:51200',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $activity = $this->activityId ? ProgramActivity::findOrFail($this->activityId) : new ProgramActivity();

        foreach (['image' => 'image_path', 'video' => 'video_path', 'pdf' => 'pdf_path'] as $upload => $column) {
            if ($this->{$upload}) {
                if ($activity->{$column}) {
                    Storage::disk('public')->delete($activity->{$column});
                }

                $validated[$column] = $this->{$upload}->store('program-activities', 'public');
            }
        }

        $activity->fill([
            'program_id' => $validated['activityProgramId'],
            'title' => $validated['activityTitle'],
            'description' => $validated['activityDescription'],
            'activity_date' => $validated['activityDate'],
            'image_path' => $validated['image_path'] ?? $activity->image_path,
            'video_path' => $validated['video_path'] ?? $activity->video_path,
            'pdf_path' => $validated['pdf_path'] ?? $activity->pdf_path,
        ]);
        $activity->save();

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
        $this->image = null;
        $this->video = null;
        $this->pdf = null;
    }

    public function deleteActivity($id): void
    {
        $activity = ProgramActivity::findOrFail($id);

        Storage::disk('public')->delete(array_filter([$activity->image_path, $activity->video_path, $activity->pdf_path]));
        $activity->delete();

        $this->dispatch('activity-deleted');
    }

    public function resetProgramForm(): void
    {
        $this->reset(['programName', 'programSummary', 'programId']);
    }

    public function resetActivityForm(): void
    {
        $this->reset(['activityProgramId', 'activityTitle', 'activityDescription', 'activityDate', 'image', 'video', 'pdf', 'activityId']);
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div>
            <flux:heading size="xl" level="1">{{ __('Programs & Activities') }}</flux:heading>
            <flux:breadcrumbs class="mb-4 mt-2">
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Programs & Activities</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <form wire:submit="saveProgram" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ $programId ? 'Edit program' : 'Add program' }}</flux:heading>

            <div class="mt-5 space-y-4">
                <flux:input wire:model="programName" label="Program name" />
                <flux:textarea wire:model="programSummary" label="Summary" rows="4" />

                <div class="flex items-center gap-3">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Save Program</flux:button>
                    @if ($programId)
                        <flux:button type="button" wire:click="resetProgramForm" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    @endif
                    <x-action-message on="program-saved">{{ __('Saved.') }}</x-action-message>
                </div>
            </div>
        </form>

        <form wire:submit="saveActivity" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ $activityId ? 'Edit activity' : 'Add activity' }}</flux:heading>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <flux:select wire:model="activityProgramId" label="Program">
                    <flux:select.option value="">Choose program</flux:select.option>
                    @foreach ($this->programs as $program)
                        <flux:select.option value="{{ $program->id }}">{{ $program->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="activityDate" type="date" label="Activity date" />
                <div class="sm:col-span-2">
                    <flux:input wire:model="activityTitle" label="Activity title" />
                </div>
                <div class="sm:col-span-2">
                    <flux:textarea wire:model="activityDescription" label="Description" rows="4" />
                </div>
                <flux:input wire:model="image" type="file" label="Picture" accept="image/*" />
                <flux:input wire:model="video" type="file" label="Video" accept="video/*" />
                <div class="sm:col-span-2">
                    <flux:input wire:model="pdf" type="file" label="Activity PDF" accept="application/pdf" />
                </div>

                <div class="flex items-center gap-3 sm:col-span-2">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Save Activity</flux:button>
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
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div>
                                    <h3 class="font-semibold">{{ $program->name }}</h3>
                                </div>
                                <p class="mt-1 text-sm text-zinc-500">{{ $program->activities_count }} activities</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" wire:click="editProgram({{ $program->id }})" class="text-[#14A84D]"><flux:icon.pencil-square class="size-5" /></button>
                                <button type="button" wire:click="deleteProgram({{ $program->id }})" wire:confirm="Delete this program and all activities?" class="text-[#E61E5C]"><flux:icon.trash class="size-5" /></button>
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
                        <td class="px-5 py-3 text-sm font-bold">Activity</td>
                        <td class="px-5 py-3 text-sm font-bold">Program</td>
                        <td class="px-5 py-3 text-sm font-bold">Date</td>
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
                            <td class="px-5 py-3 text-sm">
                                <div class="flex gap-2">
                                    @if ($activity->image_path)<span class="rounded bg-[#FFD83D] px-2 py-1 text-xs text-zinc-900">Image</span>@endif
                                    @if ($activity->video_path)<span class="rounded bg-[#7646E8] px-2 py-1 text-xs text-white">Video</span>@endif
                                    @if ($activity->pdf_path)<span class="rounded bg-[#149CB9] px-2 py-1 text-xs text-white">PDF</span>@endif
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm">
                                <div class="flex gap-3">
                                    <button type="button" wire:click="editActivity({{ $activity->id }})" class="text-[#14A84D]"><flux:icon.pencil-square class="size-5" /></button>
                                    <button type="button" wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Delete this activity?" class="text-[#E61E5C]"><flux:icon.trash class="size-5" /></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-500">No activities yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
