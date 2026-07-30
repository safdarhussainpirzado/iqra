<!DOCTYPE html>
<html lang="en" class="h-full">
@include('iqra._layout.head')
<body class="h-full text-slate-800 antialiased selection:bg-blue-500 selection:text-white bg-[#f4f7fb]" x-data="appState()" x-init="initApp()" x-cloak>

    @include('iqra._layout.background')
    @include('iqra._layout.toast')
    @include('iqra._layout.confirm-modal')

    {{-- Global SPA Loader (100% cloned from ZIWO layout) --}}
    <div x-show="navigating" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[10000] bg-white/60 backdrop-blur-md flex flex-col items-center justify-center gap-4">
        <div class="relative">
            <div class="w-14 h-14 border-[3px] border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-graduation-cap text-blue-600 text-xs animate-pulse"></i>
            </div>
        </div>
        <div class="flex flex-col items-center">
            <span class="text-[10px] font-black text-blue-700 uppercase tracking-[0.3em] animate-pulse">IQRA Platform</span>
            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Syncing Educational Records Grid...</span>
        </div>
    </div>

    {{-- ── Login Screen ──────────────────────────────────────────── --}}
    @include('iqra.auth.login')

    {{-- ── Authenticated Shell ───────────────────────────────────── --}}
    <template x-if="token">
        <div class="flex h-full min-h-screen">

            @include('iqra._layout.sidebar')

            {{-- ── Content Area ──────────────────────────────────── --}}
            <div class="flex-1 flex flex-col overflow-y-auto">

                @include('iqra._layout.topbar')

                <main class="flex-1 p-8">
                    @include('iqra.panels.dashboard')
                    @include('iqra.panels.boards')
                    @include('iqra.panels.subjects')
                    @include('iqra.panels.chapters')
                    @include('iqra.panels.ingestion')
                    @include('iqra.panels.jobs')
                    @include('iqra.panels.web-importer')
                    @include('iqra.panels.library')
                    @include('iqra.panels.questions')
                    @include('iqra.panels.papers')
                    @include('iqra.panels.logs')
                </main>

            </div>
        </div>
    </template>

    @include('iqra.js.app-state')

</body>
</html>
