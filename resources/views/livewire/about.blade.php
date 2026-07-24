<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $journeyItems = [
        [
            'year' => '2020',
            'title' => 'Founded',
            'body' => 'Born from conviction: queer Basotho deserve to thrive, not just survive.',
            'color' => '#E61E5C',
            'position' => 'top',
            'left' => '10%',
        ],
        [
            'year' => '2021-23',
            'title' => 'Finding our focus',
            'body' => "Queer Xpressions is born - activism through a creative's lens.",
            'color' => '#F05A12',
            'position' => 'bottom',
            'left' => '27%',
        ],
        [
            'year' => '2024',
            'title' => 'Laying foundations',
            'body' => 'First funding from The Other Foundation powers the Queer Econ baseline study in Mafeteng, Berea and Leribe.',
            'color' => '#D98608',
            'position' => 'top',
            'left' => '43%',
        ],
        [
            'year' => '2025',
            'title' => 'Formalised & funded',
            'body' => 'Incorporated as a non-profit company; grants from The Other Foundation, the Marang Fund, and the UNICEF-IOM Youth Power Hub.',
            'color' => '#14A84D',
            'position' => 'bottom',
            'left' => '60%',
        ],
        [
            'year' => '2026',
            'title' => 'Delivering & governing',
            'body' => '100+ trained across the North, South and Central regions; our Board of Directors is constituted.',
            'color' => '#149CB9',
            'position' => 'top',
            'left' => '79%',
        ],
        [
            'year' => 'Vision',
            'title' => 'Every district',
            'body' => 'Queer Econ reaching all ten districts of Lesotho.',
            'color' => '#7646E8',
            'position' => 'bottom',
            'left' => '90%',
        ],
    ];
@endphp

<div class="bg-[#111429]">
<section class="relative overflow-hidden py-20 sm:py-24">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_0%,rgba(45,93,202,0.15),transparent_38%),linear-gradient(180deg,#111429_0%,#0e0f20_100%)]"></div>

    <div class="relative mx-auto max-w-5xl px-6">
        <div class="max-w-4xl text-left">
            <p class="text-sm font-extrabold uppercase tracking-[0.28em] text-[#9eabd1]"></p>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">Who we are</h1>
            <div class="mt-4 h-1 w-16 rounded-full bg-[#2e71ff]"></div>
            <p class="mt-7 text-lg leading-8 text-white/75">
                Queer WorX is a queer Basotho-led non-profit organisation advancing the rights, well-being and economic inclusion of LGBTIQ+ people in Lesotho. Founded in 2020 and incorporated under the Companies Act 2011 in 2025, we grew from a simple conviction: that LGBTIQ+ people in Lesotho deserve more than survival; they deserve to thrive, economically and socially.
            </p>
            <p class="mt-8 border-l-4 border-[#2e71ff] pl-6 text-lg leading-8 text-white/70">
                We hold that conviction without setting ourselves apart from anyone else. LGBTIQ+ Basotho are not a question separate from the nation; we are part of its social fabric. Beyond our identities, we are citizens of this country and of this earth, shaped by the same poverty, unemployment and barriers to opportunity that touch every Mosotho's life, and contributors to the same shared future. Our work is therefore intersectional by necessity: it meets queerness where it overlaps with gender, class, disability and geography, and it stands in solidarity with the wider struggle for a just society, rather than in isolation from it.
            </p>
        </div>
    </div>
</section>

<section class="relative overflow-hidden py-20 sm:py-24">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(77,61,145,0.28),transparent_42%),linear-gradient(180deg,#1b1740_0%,#111429_100%)]"></div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="text-center">
            <h1 class="text-4xl font-bold tracking-normal text-white sm:text-5xl">
                Our Journey
            </h1>

            <p class="mt-3 text-base text-white/45 sm:text-lg">
                From a conviction in 2020 to a movement reaching across Lesotho.
            </p>
        </div>

        <div class="relative mt-14 hidden h-[340px] lg:block">
            <div class="absolute left-[10%] right-[10%] top-[49.5%] h-[5px] -translate-y-1/2 rounded-full bg-[linear-gradient(90deg,#E61E5C_0%,#F05A12_22%,#D98608_40%,#14A84D_58%,#149CB9_78%,#7646E8_100%)]"></div>

            @foreach ($journeyItems as $item)
                <article
                    class="absolute w-[245px] -translate-x-1/2 text-center"
                    style="left: {{ $item['left'] }}; {{ $item['position'] === 'top' ? 'top: 0;' : 'bottom: 0;' }}"
                >
                    @if ($item['position'] === 'top')
                        <div class="mx-auto">
                            <h2 class="text-2xl font-bold leading-tight" style="color: {{ $item['color'] }}">
                                {{ $item['year'] }}
                            </h2>
                            <h3 class="mt-1 text-base font-bold leading-tight text-white">
                                {{ $item['title'] }}
                            </h3>
                            <p class="mx-auto mt-2 max-w-[220px] text-sm leading-5 text-white/45">
                                {{ $item['body'] }}
                            </p>
                        </div>

                    @else
                        <div class="mx-auto">
                            <h2 class="text-2xl font-bold leading-tight" style="color: {{ $item['color'] }}">
                                {{ $item['year'] }}
                            </h2>
                            <h3 class="mt-1 text-base font-bold leading-tight text-white">
                                {{ $item['title'] }}
                            </h3>
                            <p class="mx-auto mt-2 max-w-[240px] text-sm leading-5 text-white/45">
                                {{ $item['body'] }}
                            </p>
                        </div>
                    @endif
                </article>

                <span
                    class="absolute z-10 w-[2px] -translate-x-1/2"
                    style="left: {{ $item['left'] }}; top: calc(49.5% {{ $item['position'] === 'top' ? '- 38px' : '+ 14px' }}); height: 24px; background-color: {{ $item['color'] }};"
                    aria-hidden="true"
                ></span>

                <span
                    class="absolute top-[49.5%] z-10 h-7 w-7 -translate-x-1/2 -translate-y-1/2 rounded-full border-[3px] border-[#111429] shadow-[0_0_0_1px_rgba(0,0,0,0.35)]"
                    style="left: {{ $item['left'] }}; background-color: {{ $item['color'] }};"
                    aria-hidden="true"
                ></span>
            @endforeach
        </div>

        <div class="mt-14 lg:hidden">
            <div class="relative ml-4 space-y-10 border-l-4 border-white/15 pb-1">
                @foreach ($journeyItems as $item)
                    <article class="relative pl-9">
                        <span
                            class="absolute -left-[14px] top-1 h-6 w-6 rounded-full border-[3px] border-[#111429]"
                            style="background-color: {{ $item['color'] }};"
                            aria-hidden="true"
                        ></span>

                        <h2 class="text-2xl font-bold leading-tight" style="color: {{ $item['color'] }}">
                            {{ $item['year'] }}
                        </h2>
                        <h3 class="mt-1 text-lg font-bold text-white">
                            {{ $item['title'] }}
                        </h3>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-white/50">
                            {{ $item['body'] }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bg-[#0e0f20] py-16 text-white sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-[18px] border border-[#343451] bg-[#1a1a31] p-7 sm:p-8">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#7c36ef]/20 text-[#ad83ff]"><i class="fa-solid fa-bullseye"></i></span>
                    <div>
                        <h2 class="text-2xl font-bold">Mission</h2>
                        <div class="mt-2 h-1 w-[72px] rounded-full bg-[linear-gradient(90deg,#e61e5c,#f0b31a,#14a84d,#2e71ff,#7c36ef)]"></div>
                    </div>
                </div>
                <p class="mt-6 text-lg leading-8 text-white/70">To advance an inclusive and economically resilient LGBTIQ+ community in Lesotho by promoting economic and social inclusion, strengthening human capital, and supporting sustainable livelihoods and holistic wellness.</p>
            </article>

            <article class="rounded-[18px] border border-[#343451] bg-[#1a1a31] p-7 sm:p-8">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#7c36ef]/20 text-[#ad83ff]"><i class="fa-solid fa-eye"></i></span>
                    <div>
                        <h2 class="text-2xl font-bold">Our vision</h2>
                        <div class="mt-2 h-1 w-[72px] rounded-full bg-[linear-gradient(90deg,#e61e5c,#f0b31a,#14a84d,#2e71ff,#7c36ef)]"></div>
                    </div>
                </div>
                <p class="mt-6 text-lg leading-8 text-white/70">A Lesotho where all LGBTIQ+ individuals achieve their full potential, living with dignity, freedom and economic prosperity, and contributing fully to a vibrant, inclusive nation.</p>
            </article>
        </div>
    </div>
</section>


        <article class="mt-6 rounded-[18px] border border-[#343451] bg-[#1a1a31] p-7 sm:p-8">
            <h2 class="text-2xl font-bold">Our values</h2>
            <p class="mt-1 text-white/55">Institutional values · how we govern and hold ourselves</p>
            <div class="mt-4 flex flex-wrap gap-3">
                @foreach ([['Dignity', '#2e71ff'], ['Accountability', '#149cb9'], ['Evidence', '#14a84d'], ['Sustainability', '#d98608'], ['Solidarity', '#e61e5c']] as [$value, $color])
                    <span class="rounded-full border px-5 py-1.5 text-sm font-medium" style="border-color: {{ $color }}; color: {{ $color }};">{{ $value }}</span>
                @endforeach
            </div>
            <p class="mt-5 text-white/55">Practice principles — LIGHT-IDEA · how we show up every day</p>
            <div class="mt-3 flex flex-wrap gap-3">
                @foreach (['Love', 'Inclusion', 'Growth', 'Humanity', 'Trust', 'Intersectionality', 'Diversity', 'Empowerment', 'Advocacy'] as $value)
                    <span class="rounded-full border border-[#6a37b6] bg-[#3a1b69]/25 px-5 py-1.5 text-sm font-medium text-[#d5bbff]">{{ $value }}</span>
                @endforeach
            </div>
        </article>
    </div>
</section>

</div>
