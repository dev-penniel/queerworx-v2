<?php

use App\Models\Subscriber;
use App\Models\SupportPageSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $name = '';
    public $email = '';
    public $phone = '';
    public $supportType = 'Donate';
    public $message = '';
    public $submitted = false;
    public $showSupportForm = false;

    public function chooseSupport(string $type): void
    {
        $this->supportType = $type;
        $this->showSupportForm = true;
    }

    public function submitSupport(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:160',
            'email' => 'required|email|max:180',
            'phone' => 'nullable|string|max:60',
            'supportType' => 'required|string|max:120',
            'message' => 'nullable|string|max:600',
        ]);

        Subscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'interest' => 'Support: '.$validated['supportType'],
                'message' => $validated['message'],
            ]
        );

        $this->reset(['name', 'email', 'phone', 'message']);
        $this->supportType = 'Donate';
        $this->showSupportForm = true;
        $this->submitted = true;
        $this->dispatch('support-submitted');
    }
}; ?>

@php
    $supportSettings = SupportPageSetting::first();
    $impactStats = [
        ['value' => \App\Models\Subscriber::count(), 'label' => 'Lives Empowered'],
        ['value' => \App\Models\Program::count() + \App\Models\ProgramActivity::count(), 'label' => 'Programs & Events'],
        ['value' => \App\Models\Subscriber::where('interest', 'Support: Volunteer')->count(), 'label' => 'Volunteers'],
        ['value' => \App\Models\Subscriber::where('interest', 'Support: Partner')->count(), 'label' => 'Partner Organizations'],
    ];
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <style>
        #support-form input,
        #support-form textarea,
        #support-form select,
        #support-form option {
            background-color: #0b0d1d;
            color: #ffffff;
        }

        #support-form input::placeholder,
        #support-form textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>

    <section class="relative overflow-hidden bg-[radial-gradient(circle_at_24%_0%,rgba(230,30,92,0.22),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_70%,#0b0d1d_100%)]">
        <div class="mx-auto grid min-h-[430px] max-w-7xl items-center gap-8 px-6 py-16 {{ $supportSettings?->hero_image_path ? 'md:grid-cols-[0.95fr_1.05fr]' : '' }}">
            <div class="max-w-xl">
                <h1 class="text-5xl font-bold tracking-normal text-transparent sm:text-6xl" style="background: linear-gradient(90deg,#E61E5C,#F05A12,#FFD83D,#14A84D,#149CB9,#7646E8); -webkit-background-clip: text; background-clip: text;">
                    Support<br>Queer WorX
                </h1>
                <div class="mt-4 h-1.5 w-28 rounded-full bg-[#E61E5C]"></div>
                <p class="mt-6 max-w-md text-base leading-7 text-white/75">
                    Your support helps us create safe spaces, provide resources, and empower LGBTIQ+ individuals in the workplace and beyond.
                </p>
                <a href="#ways-to-support" class="mt-7 inline-flex rounded-full bg-[#E61E5C] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-pink-900/20 transition hover:bg-[#c9184f]">
                    Donate Now
                </a>
            </div>

            @if ($supportSettings?->hero_image_path)
                <div class="relative flex justify-center md:justify-end">
                    <img src="{{ Storage::url($supportSettings->hero_image_path) }}" alt="Support Queer WorX" class="h-full max-h-[360px] w-full max-w-xl rounded-[8px] object-cover shadow-2xl shadow-black/30">
                </div>
            @endif
        </div>
    </section>

    <section id="ways-to-support" class="bg-[#111429] py-14 text-white">
        <div class="mx-auto max-w-6xl px-6">
            <h2 class="text-center text-2xl font-bold text-[#14A84D]">Ways to Support</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-4">
                @foreach ([
                    ['icon' => 'fa-heart', 'title' => 'Donate', 'text' => 'Your donation fuels programs, events, and resources for our community.', 'action' => 'Give Now', 'type' => 'Donate'],
                    ['icon' => 'fa-users', 'title' => 'Volunteer', 'text' => 'Share your time and skills to make a lasting impact.', 'action' => 'Sign Up', 'type' => 'Volunteer'],
                    ['icon' => 'fa-gift', 'title' => 'Partner', 'text' => 'Collaborate with us to create inclusive and meaningful change.', 'action' => 'Learn More', 'type' => 'Partner'],
                    ['icon' => 'fa-bullhorn', 'title' => 'Spread the Word', 'text' => 'Help us reach more people by sharing our mission and events.', 'action' => 'Share Now', 'type' => 'Spread the Word'],
                ] as $item)
                    <article class="rounded-[8px] border border-[#14A84D]/70 bg-white/[0.06] p-6 text-center shadow-2xl shadow-black/10">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center text-[#14A84D]">
                            <i class="fa-solid {{ $item['icon'] }} text-2xl"></i>
                        </div>
                        <h3 class="mt-4 font-bold text-white">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-white/85">{{ $item['text'] }}</p>
                        <a href="#support-form" wire:click="chooseSupport('{{ $item['type'] }}')" class="mt-5 inline-flex text-sm font-bold text-[#14A84D] transition hover:text-[#FFD83D]">
                            {{ $item['action'] }} &rarr;
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#111429] py-14 text-white">
        <div class="mx-auto max-w-6xl px-6">
            <h2 class="text-center text-2xl font-bold text-[#FFD83D]">Your Impact</h2>
            <div class="mt-10 grid gap-6 text-center md:grid-cols-4">
                @foreach ($impactStats as $impact)
                    <div class="rounded-[8px] border border-[#FFD83D]/70 bg-white/[0.06] p-5 shadow-lg shadow-black/10">
                        <p class="text-4xl font-bold text-[#FFD83D]">{{ number_format($impact['value']) }}</p>
                        <p class="mt-2 text-sm font-semibold text-white/80">{{ $impact['label'] }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mt-8 text-center text-sm font-semibold text-white/75">
                <span class="text-[#E61E5C]">&hearts;</span> Together, we are building a more inclusive tomorrow.
            </p>
        </div>
    </section>

    <div class="h-1.5 bg-[linear-gradient(90deg,#E61E5C_0%,#F05A12_18%,#FFD83D_34%,#14A84D_52%,#149CB9_72%,#7646E8_100%)]"></div>

    @if ($showSupportForm || $submitted)
        <section id="support-form" class="bg-[#111429] py-16">
            <div class="mx-auto grid max-w-6xl gap-10 px-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-[#FFD83D]">Take Action</p>
                    <h2 class="mt-3 text-4xl font-bold tracking-normal text-transparent" style="background: linear-gradient(90deg,#E61E5C,#F05A12,#FFD83D,#14A84D,#149CB9,#7646E8); -webkit-background-clip: text; background-clip: text;">Support our work</h2>
                    <p class="mt-4 text-white/65">
                        Tell us how you would like to support Queer WorX. Your submission will appear in the admin Subscribers area for follow-up.
                    </p>
                </div>

                <form wire:submit="submitSupport" class="grid gap-5 rounded-[8px] border border-white/10 bg-white/[0.06] p-6 shadow-2xl shadow-black/20">
                @if ($submitted)
                    <div class="rounded-[8px] border border-[#14A84D]/30 bg-[#14A84D]/10 p-4 text-sm font-semibold text-[#9CF5B5]">
                        Thank you. Your support request has been received.
                    </div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Full name</span>
                        <input wire:model="name" type="text" class="rounded-[8px] border border-white/10 bg-[#0b0d1d] px-4 py-3 text-white outline-none focus:border-purple-400">
                        @error('name') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Email</span>
                        <input wire:model="email" type="email" class="rounded-[8px] border border-white/10 bg-[#0b0d1d] px-4 py-3 text-white outline-none focus:border-purple-400">
                        @error('email') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Phone</span>
                        <input wire:model="phone" type="text" class="rounded-[8px] border border-white/10 bg-[#0b0d1d] px-4 py-3 text-white outline-none focus:border-purple-400">
                        @error('phone') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Support type</span>
                        <select wire:model="supportType" class="rounded-[8px] border border-white/10 bg-[#0b0d1d] px-4 py-3 text-white outline-none focus:border-purple-400">
                            <option value="Donate">Donate</option>
                            <option value="Volunteer">Volunteer</option>
                            <option value="Partner">Partner</option>
                            <option value="Spread the Word">Spread the Word</option>
                        </select>
                        @error('supportType') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                </div>

                <label class="grid gap-2">
                    <span class="text-sm font-semibold">Message</span>
                    <textarea wire:model="message" rows="4" class="rounded-[8px] border border-white/10 bg-[#0b0d1d] px-4 py-3 text-white outline-none focus:border-purple-400"></textarea>
                    @error('message') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                </label>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-full bg-[#E61E5C] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#c9184f]">
                        Submit Support
                    </button>
                </div>
                </form>
            </div>
        </section>
    @endif
</main>
