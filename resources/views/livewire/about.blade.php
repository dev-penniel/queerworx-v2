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
<section id="governance" class="relative overflow-hidden py-20 sm:py-24">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(77,61,145,0.28),transparent_42%),linear-gradient(180deg,#1b1740_0%,#111429_100%)]"></div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="inline-flex items-end justify-center gap-2 text-left font-serif italic leading-none text-[#111429] [text-shadow:0_2px_0_rgba(255,255,255,0.9)] [-webkit-text-stroke:1.5px_white] sm:gap-3">
                <span class="text-7xl sm:text-8xl">
                    <span class="bg-[linear-gradient(180deg,#E61E5C_0%,#FFD83D_32%,#14A84D_56%,#149CB9_76%,#7646E8_100%)] bg-clip-text text-transparent [-webkit-text-stroke:1.5px_white]">
                </span>
                <span class="mb-1 block text-4xl not-italic leading-[0.82] text-white font-semibold [-webkit-text-stroke:0] [text-shadow:0_2px_0_rgba(0,0,0,0.45)] sm:mb-2 sm:text-5xl">
                    Overview
                </span>
            </h1>

            <p class="mt-3 text-base  text-white sm:text-lg">
               Driven by the urgent need to address economic exclusion within the LGBTQ+ community, Queer WorX was founded to bridge the critical gap in services and support specifically tailored to our needs. Economic advancement is crucial for achieving true equality, enabling individuals to live independently, support their families, and contribute meaningfully to society. The transformative impact of economic empowerment on the lives of LGBTIQ+ individuals fuels our passion for advocating for policies, programs, and initiatives that break down barriers and create inclusive economic opportunities.
            </p>
        </div>

        <div class="mt-14 grid gap-6 lg:grid-cols-2">
            <article class="relative overflow-hidden rounded-[8px] border border-white/10 bg-black/30 p-7 shadow-2xl shadow-black/20 sm:p-9">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-[linear-gradient(90deg,#E61E5C_0%,#F05A12_18%,#FFD83D_34%,#14A84D_52%,#149CB9_72%,#7646E8_100%)]"></div>

                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[linear-gradient(135deg,#E61E5C_0%,#FFD83D_32%,#14A84D_54%,#149CB9_74%,#7646E8_100%)] text-2xl text-white shadow-lg shadow-purple-900/30">
                        <i class="fa-solid fa-bullseye"></i>
                    </span>

                    <h2 class="rounded-full bg-[linear-gradient(90deg,#FF5A77_0%,#FFD83D_32%,#14A84D_54%,#149CB9_74%,#7646E8_100%)] px-8 py-2 text-3xl font-bold text-[#111429] shadow-lg shadow-black/20">
                        Mission
                    </h2>
                </div>

                <p class="mt-7 text-lg font-normal leading-7 text-white/90 sm:text-xl sm:leading-8">
                    Queer WorX's mission is to build an inclusive and economically resilient LGBTIQ+ community in Lesotho by advancing human capital, fostering self-sufficiency, and supporting holistic wellness through education, mentorship, and sustainable livelihood initiatives.
                </p>
            </article>

            <article class="relative overflow-hidden rounded-[8px] border border-white/10 bg-black/30 p-7 shadow-2xl shadow-black/20 sm:p-9">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-[linear-gradient(90deg,#E61E5C_0%,#F05A12_18%,#FFD83D_34%,#14A84D_52%,#149CB9_72%,#7646E8_100%)]"></div>

                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[linear-gradient(135deg,#E61E5C_0%,#FFD83D_32%,#14A84D_54%,#149CB9_74%,#7646E8_100%)] text-2xl text-white shadow-lg shadow-purple-900/30">
                        <i class="fa-solid fa-eye"></i>
                    </span>

                    <h2 class="rounded-full bg-[linear-gradient(90deg,#FF5A77_0%,#FFD83D_32%,#14A84D_54%,#149CB9_74%,#7646E8_100%)] px-8 py-2 text-3xl font-bold text-[#111429] shadow-lg shadow-black/20">
                        Our Vision
                    </h2>
                </div>

                <p class="mt-7 text-lg font-normal leading-7 text-white/90 sm:text-xl sm:leading-8">
                    Queer WorX envisions a Lesotho where all LGBTIQ+ individuals achieve their full potential, living with dignity, freedom, and economic prosperity, and contributing fully to a vibrant and inclusive nation.
                </p>
            </article>
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

</div>
