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
    $supporterCount = Subscriber::where('interest', 'like', 'Support:%')->count();
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

    <section class="bg-[#0e0f20] px-6 pb-8 pt-10 text-white sm:pb-12 sm:pt-16">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-[24px] border border-[#e61e5c]/55 bg-[#1a1a31] px-6 py-10 sm:px-11 sm:py-11">
            <div class="absolute left-5 right-5 top-0 h-1 bg-[#e61e5c]"></div>
            <div class="grid items-center gap-10 {{ $supportSettings?->hero_image_path ? 'lg:grid-cols-[minmax(0,1fr)_260px_300px]' : 'lg:grid-cols-[minmax(0,1fr)_300px]' }}">
                <div class="max-w-2xl">
                    <p class="text-sm font-extrabold uppercase tracking-[0.28em] text-pink-300">The campaign</p>
                    <h1 class="mt-3 text-4xl font-bold tracking-tight text-white sm:text-5xl">The One Loti Drive</h1>
                    <p class="mt-4 max-w-2xl text-lg leading-8 text-white/70">
                        Loti by loti, we keep the work going. From M1, anyone can help build the Pink Economy — proof that queer economic power is everyone's business.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                        <a href="#support-form" wire:click="chooseSupport('Donate')" class="inline-flex rounded-full bg-[#e61e5c] px-7 py-3.5 text-base font-bold text-white transition hover:bg-[#c9184f]">
                            Add your loti
                        </a>
                        <span class="text-base text-white/55">from M1 · once-off or monthly</span>
                    </div>
                </div>

                @if ($supportSettings?->hero_image_path)
                    <img src="{{ Storage::url($supportSettings->hero_image_path) }}" alt="Support Queer WorX" class="mx-auto aspect-square w-full max-w-[230px] rounded-full border-2 border-[#e61e5c] object-cover">
                @endif

                <div class="text-center lg:justify-self-center">
                    <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full border-4 border-[#e61e5c] text-3xl font-extrabold text-pink-300">M1</div>
                    <p class="mt-4 text-4xl font-extrabold text-white">{{ number_format($supporterCount) }}</p>
                    <p class="mt-1 text-base text-white/55">people have joined the drive</p>
                </div>
            </div>
        </div>
    </section>

    <section id="ways-to-support" class="bg-[#0e0f20] py-8 pb-16 text-white sm:pb-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white">Other ways to support</h2>
                <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-[#e61e5c]"></div>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['icon' => 'fa-box-open', 'title' => 'Give in kind', 'text' => "Equipment, venue space, printing, pro-bono services — if you have something the work needs, we'll put it to use.", 'action' => 'See what we need', 'type' => 'Give in kind'],
                    ['icon' => 'fa-hand-holding-heart', 'title' => 'Volunteer', 'text' => 'Give your time and skills — mentoring, facilitation, events, or expertise our programmes can use.', 'action' => 'Offer your time', 'type' => 'Volunteer'],
                    ['icon' => 'fa-people-group', 'title' => 'Partner with us', 'text' => 'For organisations, funders and institutions ready to build with us — programme collaboration, funding partnerships, shared advocacy.', 'action' => 'Start a conversation', 'type' => 'Partner'],
                    ['icon' => 'fa-bullhorn', 'title' => 'Spread the word', 'text' => 'Share our work, events and stories — reach is a resource, and yours costs nothing to give.', 'action' => 'Share now', 'type' => 'Spread the Word'],
                ] as $item)
                    <article class="flex min-h-[330px] flex-col rounded-[18px] border border-[#343451] bg-[#1a1a31] p-8 shadow-xl shadow-black/10">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#e61e5c]/15 text-pink-300">
                            <i class="fa-solid {{ $item['icon'] }} text-lg"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-base leading-7 text-white/65">{{ $item['text'] }}</p>
                        <a href="#support-form" wire:click="chooseSupport('{{ $item['type'] }}')" class="mt-auto pt-7 text-sm font-extrabold tracking-wide text-pink-300 transition hover:text-pink-200">
                            {{ $item['action'] }} &rarr;
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#0e0f20] py-2 pb-16 text-white sm:pb-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white">Your impact</h2>
                <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-[#e61e5c]"></div>
            </div>
            <div class="mt-10 grid gap-6 text-center md:grid-cols-4">
                @foreach ($impactStats as $impact)
                    <div class="rounded-[18px] border border-[#e61e5c]/65 bg-[#1a1a31] p-7 shadow-lg shadow-black/10">
                        <p class="text-4xl font-bold text-pink-300">{{ number_format($impact['value']) }}</p>
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
                            <option value="Give in kind">Give in kind</option>
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
