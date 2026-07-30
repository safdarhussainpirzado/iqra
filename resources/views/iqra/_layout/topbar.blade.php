{{-- Topbar --}}
<header class="h-16 backdrop-blur-md bg-slate-900/60 border-b border-slate-800 px-8 flex items-center justify-between flex-shrink-0 sticky top-0 z-10">
    <div class="flex items-center gap-3">
        <h2 class="text-lg font-semibold tracking-tight text-slate-100 capitalize"
            x-text="currentView === 'papers' ? 'Paper Generator'
                  : currentView === 'jobs'   ? 'Jobs & Queue Monitor'
                  : currentView === 'uploader' ? 'Ingestion & OCR'
                  : currentView === 'library'  ? 'Notes & Materials'
                  : currentView === 'scraper'  ? 'Web Importer'
                  : currentView">
        </h2>
        {{-- Live badge visible on Jobs view when work is queued --}}
        <span x-show="currentView === 'jobs' && (jobsData.counts?.pending > 0 || jobsData.counts?.processing > 0)"
              class="pulse-dot px-2 py-0.5 bg-amber-500/20 border border-amber-500/40 text-amber-300 text-[10px] font-black rounded-full uppercase tracking-widest">
            Live
        </span>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs text-slate-500"
              x-text="new Date().toLocaleDateString('en-PK', {weekday:'short', year:'numeric', month:'short', day:'numeric'})"></span>
        <button @click="logout()"
                class="px-3.5 py-1.5 text-xs font-semibold text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 rounded-lg transition duration-150">
            <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
        </button>
    </div>
</header>
