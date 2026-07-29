<?php

use App\Models\JoinPageSetting;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $name = '';
    public $email = '';
    public $phone = '';
    public $interest = 'Entrepreneur / Business owner';
    public $businessDetails = '';
    public $submitted = false;

    public function join(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:160',
            'email' => 'required|email|max:180',
            'interest' => 'required|string|max:120',
            'businessDetails' => $this->interest === 'Entrepreneur / Business owner' ? ['required', 'string', 'max:300'] : ['nullable', 'string', 'max:300'],
        ]);

        Subscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'interest' => $validated['interest'],
                'message' => $validated['businessDetails']
                    ? 'Business name & sector: '.$validated['businessDetails']
                    : null,
            ]
        );

        $this->reset(['name', 'email', 'phone', 'businessDetails']);
        $this->interest = 'Entrepreneur / Business owner';
        $this->submitted = true;
        $this->dispatch('member-joined');
    }
}; ?>

@php
    $joinSettings = JoinPageSetting::first();
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <style>
        #join-form input,
        #join-form textarea,
        #join-form select,
        #join-form option {
            background-color: #0b0d1d;
            color: #ffffff;
        }

        #join-form input::placeholder,
        #join-form textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
    <section class="relative overflow-hidden bg-[radial-gradient(circle_at_30%_0%,rgba(230,30,92,0.22),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_70%,#0b0d1d_100%)]">
        <div class="mx-auto grid min-h-[420px] max-w-7xl items-center gap-8 px-6 py-16 {{ $joinSettings?->hero_image_path ? 'md:grid-cols-[0.95fr_1.05fr]' : '' }}">
            <div class="relative z-10 max-w-xl">
                <h1 class="text-5xl font-bold tracking-normal text-transparent sm:text-6xl" style="background: linear-gradient(90deg,#E61E5C,#F05A12,#FFD83D,#14A84D,#149CB9,#7646E8); -webkit-background-clip: text; background-clip: text;">Join Us</h1>
                <div class="mt-4 h-1.5 w-28 rounded-full bg-[#E61E5C]"></div>
                <p class="mt-6 max-w-sm text-base leading-7 text-white/75">
                    Be part of a community that empowers, uplifts, and creates change together.
                </p>
                <a href="#join-form" class="mt-7 inline-flex rounded-full bg-[#5E2E91] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-900/20 transition hover:bg-[#4b2376]">
                    Join Now
                </a>
            </div>

            @if ($joinSettings?->hero_image_path)
                <div class="relative flex justify-center md:justify-end">
                    <img src="{{ Storage::url($joinSettings->hero_image_path) }}" alt="Queer WorX community" class="h-full max-h-[360px] w-full max-w-xl rounded-[8px] object-cover shadow-2xl shadow-black/30">
                </div>
            @endif
        </div>
    </section>

    <section class="bg-[#111429] py-14">
        <div class="mx-auto max-w-6xl px-6">
            <h2 class="text-center text-2xl font-bold text-transparent" style="background: linear-gradient(90deg,#E61E5C,#F05A12,#FFD83D,#14A84D,#149CB9,#7646E8); -webkit-background-clip: text; background-clip: text;">Why Join Queer WorX?</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-4">
                @foreach ([
                    ['title' => 'Community', 'text' => 'Connect with a vibrant network of LGBTIQ+ individuals and allies.'],
                    ['title' => 'Opportunities', 'text' => 'Access programs, workshops, and events that support your growth.'],
                    ['title' => 'Impact', 'text' => 'Be part of meaningful initiatives driving inclusion and equity.'],
                    ['title' => 'Visibility', 'text' => 'Amplify your voice and celebrate queer identities at work.'],
                ] as $item)
                    <article class="rounded-[8px] border border-white/10 bg-white/[0.06] px-5 py-6 text-center">
                        <h3 class="font-bold text-white">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-white/70">{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#211146] py-14">
        <div class="mx-auto max-w-6xl px-6">
            <h2 class="text-center text-2xl font-bold text-transparent" style="background: linear-gradient(90deg,#E61E5C,#F05A12,#FFD83D,#14A84D,#149CB9,#7646E8); -webkit-background-clip: text; background-clip: text;">How to Get Involved</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-4">
                @foreach ([
                    ['title' => '1. Sign Up', 'text' => 'Create your free account on our platform.'],
                    ['title' => '2. Explore', 'text' => 'Discover programs, events, and volunteer opportunities.'],
                    ['title' => '3. Engage', 'text' => 'Join conversations, attend events, and connect with others.'],
                    ['title' => '4. Make an Impact', 'text' => 'Collaborate, lead, and help build a more inclusive future.'],
                ] as $index => $step)
                    <article class="relative text-center">
                        @if ($index > 0)
                            <span class="absolute -left-4 top-2 hidden text-white/30 md:block">→</span>
                        @endif
                        <h3 class="font-bold text-white">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-white/70">{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <div class="h-1.5 bg-[linear-gradient(90deg,#E61E5C_0%,#F05A12_18%,#FFD83D_34%,#14A84D_52%,#149CB9_72%,#7646E8_100%)]"></div>

    <section id="join-form" class="bg-[#0e0f20] py-16 text-white sm:py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[0.78fr_1.22fr] lg:items-start lg:gap-20">
            <div class="pt-6">
                <p class="text-sm font-extrabold uppercase tracking-[0.28em] text-[#a8adce]">Community</p>
                <h2 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">Join the community</h2>
                <div class="mt-5 h-1 w-[72px] rounded-full bg-[#7c36ef]"></div>
                <p class="mt-7 max-w-md text-lg leading-8 text-white/70">
                    Whether you're here to stay connected, grow a business, offer your time, or partner with us — tell us who you are and we'll keep the right opportunities coming your way.
                </p>
            </div>

            <form wire:submit="join" class="grid gap-6 rounded-[22px] border border-[#343451] bg-[#1a1a31] p-6 shadow-2xl shadow-black/15 sm:p-10">
                @if ($submitted)
                    <div class="rounded-[10px] border border-[#14A84D]/30 bg-[#14A84D]/10 p-4 text-sm font-semibold text-[#9CF5B5]">
                        Thank you. You are now connected to the Queer WorX community.
                    </div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-lg font-bold">Full name</span>
                        <input wire:model="name" type="text" autocomplete="name" class="rounded-[14px] border border-[#343451] bg-[#0e0f20] px-4 py-3 text-white outline-none transition focus:border-[#7c36ef] focus:ring-1 focus:ring-[#7c36ef]">
                        @error('name') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                    <label class="grid gap-2">
                        <span class="text-lg font-bold">Email</span>
                        <input wire:model="email" type="email" autocomplete="email" class="rounded-[14px] border border-[#343451] bg-[#0e0f20] px-4 py-3 text-white outline-none transition focus:border-[#7c36ef] focus:ring-1 focus:ring-[#7c36ef]">
                        @error('email') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                </div>

                <label class="grid gap-2">
                    <span class="text-lg font-bold">I'm joining as...</span>
                    <select wire:model.live="interest" class="rounded-[14px] border border-[#7c36ef] bg-[#0e0f20] px-4 py-3 text-lg text-white outline-none transition focus:ring-1 focus:ring-[#7c36ef]">
                        <option value="Community — keep me connected">Community — keep me connected</option>
                        <option value="Entrepreneur / Business owner">Entrepreneur / Business owner</option>
                        <option value="Volunteer — time &amp; skills">Volunteer — time &amp; skills</option>
                        <option value="Partner / Organisation">Partner / Organisation</option>
                        <option value="Creative / Contributor">Creative / Contributor</option>
                    </select>
                    @error('interest') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                </label>

                @if ($interest === 'Entrepreneur / Business owner')
                    <label class="grid gap-2 border-l-4 border-[#7c36ef] pl-4">
                        <span class="text-lg font-bold">Business name &amp; sector <span class="text-base font-normal text-white/50">(shown because you picked Entrepreneur)</span></span>
                        <input wire:model="businessDetails" type="text" class="rounded-[14px] border border-[#343451] bg-[#0e0f20] px-4 py-3 text-white outline-none transition focus:border-[#7c36ef] focus:ring-1 focus:ring-[#7c36ef]">
                        @error('businessDetails') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                @endif

                <div class="flex justify-end pt-1">
                    <button type="submit" class="rounded-full bg-[#7c36ef] px-8 py-3.5 text-lg font-bold text-white transition hover:bg-[#6827d7]">
                        Join the community
                    </button>
                </div>

                <p class="text-center text-sm leading-6 text-white/50">
                    Queer WorX is a non-membership organisation. Your details are stored securely to connect you with relevant programmes and opportunities — never shared without your consent.
                </p>
            </form>
        </div>
    </section>
</main>
