@php
    use App\Models\AdminDocument;
    use App\Models\Program;
    use App\Models\ProgramActivity;
    use App\Models\Article;
    use App\Models\ArticleComment;
    use App\Models\Subscriber;

    $pendingArticles = Article::where('status', 'draft')->count();
    $pendingComments = ArticleComment::where('status', 'pending')->count();
    $reviewTotal = $pendingArticles + $pendingComments;

    $stats = [
        ['label' => 'Needs Review', 'value' => $reviewTotal, 'color' => '#FFD83D'],
        ['label' => 'Policies', 'value' => AdminDocument::where('type', 'policy')->count(), 'color' => '#E61E5C'],
        ['label' => 'Financials', 'value' => AdminDocument::where('type', 'financial')->count(), 'color' => '#149CB9'],
        ['label' => 'Programs', 'value' => Program::count(), 'color' => '#14A84D'],
    ];

    $programs = Program::withCount('activities')->latest()->take(6)->get();
@endphp

<x-layouts.app :title="__('Dashboard')">
    <div class="-m-6 min-h-screen space-y-6 bg-[radial-gradient(circle_at_20%_0%,rgba(118,70,232,0.32),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_52%,#0b0d1d_100%)] p-6 text-white">
        <section class="overflow-hidden rounded-lg border border-white/10 bg-black/25 p-6 text-white shadow-2xl shadow-black/20">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#FFD83D]">Admin Control Center</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-normal">Queer WorX dashboard</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-white/70">
                        Manage About Us documents, program activity lists, pictures, videos, PDFs, articles, resources, subscribers, and account access from one place.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a wire:navigate href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded border border-white/25 px-4 py-2 text-sm font-semibold text-white">
                        <flux:icon.home class="size-4" /> View Site
                    </a>
                    <a wire:navigate href="{{ route('about') }}" class="inline-flex items-center gap-2 rounded border border-white/25 px-4 py-2 text-sm font-semibold text-white">
                        <flux:icon.eye class="size-4" /> About Page
                    </a>
                    <a wire:navigate href="{{ route('admin.documents') }}" class="inline-flex items-center gap-2 rounded bg-white px-4 py-2 text-sm font-semibold text-[#111429]" style="color: #111429 !important;">
                        <flux:icon.document-arrow-up class="size-4" /> Upload PDF
                    </a>
                    <a wire:navigate href="{{ route('admin.programs') }}" class="inline-flex items-center gap-2 rounded border border-white/25 px-4 py-2 text-sm font-semibold text-white">
                        <flux:icon.squares-2x2 class="size-4" /> Add Activity
                    </a>
                </div>
            </div>

            <div class="mt-6 h-1 rounded bg-[linear-gradient(90deg,#E61E5C_0%,#F05A12_18%,#FFD83D_34%,#14A84D_52%,#149CB9_72%,#7646E8_100%)]"></div>
        </section>

        @if ($reviewTotal > 0)
            <section class="rounded-lg border border-[#FFD83D]/30 bg-[#FFD83D]/10 p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-[#FFD83D]">Review queue needs attention</h2>
                        <p class="mt-1 text-sm text-white/70">
                            {{ $pendingArticles }} article{{ $pendingArticles === 1 ? '' : 's' }} and {{ $pendingComments }} comment{{ $pendingComments === 1 ? '' : 's' }} waiting for approval.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a wire:navigate href="{{ route('articles.index') }}" class="rounded bg-white px-4 py-2 text-sm font-bold text-[#211146]" style="color: #211146 !important;">Review Articles</a>
                        <a wire:navigate href="{{ route('comments.index') }}" class="rounded border border-white/25 px-4 py-2 text-sm font-bold text-white">Review Comments</a>
                    </div>
                </div>
            </section>
        @endif

        <section class="grid gap-4 md:grid-cols-4">
            @foreach ($stats as $stat)
                <article class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm">
                    <span class="block h-1 w-12 rounded" style="background: {{ $stat['color'] }}"></span>
                    <p class="mt-4 text-sm text-white/55">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-3xl font-bold">{{ $stat['value'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-[1fr_360px]">
            <div class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <flux:heading size="lg">Program activity overview</flux:heading>
                    <a wire:navigate href="{{ route('admin.programs') }}" class="text-sm font-semibold text-[#7DD3FC]">Manage</a>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @forelse ($programs as $program)
                        <article class="rounded border border-white/10 bg-black/20 p-4">
                            <div>
                                <h3 class="font-semibold">{{ $program->name }}</h3>
                            </div>
                            <p class="mt-2 text-sm text-white/50">{{ $program->activities_count }} activities listed</p>
                        </article>
                    @empty
                        <p class="text-sm text-white/50">Add your first program to begin listing activities.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm">
                <flux:heading size="lg">Quick controls</flux:heading>

                <div class="mt-4 space-y-3">
                    <a wire:navigate href="{{ route('articles.index') }}" class="flex items-center justify-between rounded border border-white/10 bg-black/20 px-4 py-3 text-sm">
                        Articles <span>{{ Article::count() }}</span>
                    </a>
                    <a wire:navigate href="{{ route('comments.index') }}" class="flex items-center justify-between rounded border border-white/10 bg-black/20 px-4 py-3 text-sm">
                        Pending comments <span>{{ $pendingComments }}</span>
                    </a>
                    <a wire:navigate href="{{ route('resources.index') }}" class="flex items-center justify-between rounded border border-white/10 bg-black/20 px-4 py-3 text-sm">
                        Resources <span>Manage</span>
                    </a>
                    <a wire:navigate href="{{ route('subscribers.index') }}" class="flex items-center justify-between rounded border border-white/10 bg-black/20 px-4 py-3 text-sm">
                        Subscribers <span>{{ Subscriber::count() }}</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
