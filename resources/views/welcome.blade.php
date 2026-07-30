<!DOCTYPE html>
<html lang="en" class="h-full">
@include('iqra._layout.head')
<body class="h-full text-slate-100 antialiased selection:bg-indigo-500 selection:text-white" x-data="appState()" x-init="initApp()" x-cloak>

    @include('iqra._layout.background')
    @include('iqra._layout.toast')
    @include('iqra._layout.confirm-modal')

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
