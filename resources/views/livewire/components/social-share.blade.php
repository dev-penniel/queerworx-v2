<?php

use Livewire\Volt\Component;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;


new class extends Component {
    public $article;

    public function mount($id)
    {
        $this->article = Article::findOrFail($id);
    }

    public function shareUrl()
    {
        return url()->route('article', ['slug' => $this->article->slug]);
    }
};
?>

<wire:head>
    <title>{{ $article->title }}</title>
    <meta name="description" content="{{ $article->description }}">

    <!-- Open Graph / Facebook / LinkedIn -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->exerpt }}">
    {{-- <meta property="og:image" content="{{ Storage::url($article->thumbnail)) }}"> --}}

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $article->title }}">
    <meta name="twitter:description" content="{{ $article->exerpt }}">
    {{-- <meta name="twitter:image" content="{{ Storage::url($article->thumbnail)) }}"> --}}
</wire:head>

<div class="rounded-2xl shadow p-6 mt-6">
    <h2 class="text-xl font-semibold mb-4">Share this article</h2>
    <div class="flex gap-4">
    <!-- Facebook -->
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($this->shareUrl()) }}" target="_blank"
       class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 5.005 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.89h2.54V9.845c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.463h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33V21.878C18.343 21.128 22 17.005 22 12z"/>
        </svg>
    </a>

    <!-- Twitter/X -->
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($this->shareUrl()) }}&text={{ urlencode($article->title) }}" target="_blank"
       class="p-2 bg-sky-500 text-white rounded-full hover:bg-sky-600">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
        </svg>
    </a>

    <!-- LinkedIn -->
    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($this->shareUrl()) }}&title={{ urlencode($article->title) }}" target="_blank"
       class="p-2 bg-blue-800 text-white rounded-full hover:bg-blue-900">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-10h3v10zm-1.5-11.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.784 1.764-1.75 1.764zm13.5 11.268h-3v-5.604c0-1.336-.024-3.064-1.867-3.064-1.868 0-2.154 1.459-2.154 2.968v5.7h-3v-10h2.881v1.367h.041c.401-.761 1.381-1.562 2.842-1.562 3.037 0 3.599 2 3.599 4.59v5.605z"/>
        </svg>
    </a>

    <!-- WhatsApp -->
    <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . $this->shareUrl()) }}" target="_blank"
       class="p-2 bg-green-500 text-white rounded-full hover:bg-green-600">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.52 3.48A11.88 11.88 0 0012 0C5.373 0 0 5.373 0 12c0 2.122.552 4.129 1.596 5.91L0 24l6.3-1.618A11.875 11.875 0 0012 24c6.627 0 12-5.373 12-12 0-3.19-1.243-6.192-3.48-8.52zM12 22c-2.036 0-3.934-.63-5.518-1.704l-.396-.248-3.735.958.998-3.637-.256-.407A9.93 9.93 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm5.373-7.12c-.246-.123-1.457-.72-1.683-.803-.226-.083-.39-.123-.554.123-.164.246-.634.803-.778.968-.144.164-.287.184-.532.062-.246-.123-1.036-.382-1.97-1.207-.728-.648-1.218-1.447-1.36-1.693-.144-.246-.015-.378.108-.501.112-.112.246-.287.369-.43.123-.144.164-.246.246-.41.082-.164.041-.307-.02-.43-.062-.123-.554-1.338-.758-1.832-.2-.48-.404-.415-.554-.423l-.47-.008c-.144 0-.379.052-.578.246-.2.195-.764.747-.764 1.82 0 1.072.781 2.107.889 2.254.108.144 1.53 2.328 3.707 3.264.518.224.922.358 1.236.458.52.164.994.141 1.367.086.417-.058 1.457-.596 1.665-1.17.205-.574.205-1.066.144-1.17-.062-.103-.226-.164-.472-.287z"/>
        </svg>
    </a>

    <!-- Copy link -->
    <button wire:click="$dispatch('copy-link', { link: '{{ $this->shareUrl() }}' })"
       class="p-2 bg-gray-700 text-white rounded-full hover:bg-gray-800">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3.9 12a4 4 0 014-4h4v2H7.9a2 2 0 00-2 2v4h2v-4zm6 4v2h4a4 4 0 004-4v-4h-2v4a2 2 0 01-2 2h-4zm-1-7h4V5a4 4 0 00-4 4v4h2V9z"/>
        </svg>
    </button>
</div>

</div>


<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('copy-link', ({ link }) => {
            navigator.clipboard.writeText(link).then(() => {
                alert('Link copied to clipboard!');
            });
        });
    });
</script>