{{-- Premium Topbar Navigation matching ZIWO CRM Layout --}}
<header class="h-16 bg-white border-b border-slate-100 px-8 flex items-center justify-between flex-shrink-0 sticky top-0 z-30 shadow-[0_2px_15px_rgba(0,0,0,0.015)]">
    <div class="flex items-center gap-3">
        <h2 class="text-base font-black tracking-tight text-navy-900 capitalize"
            x-text="currentView === 'papers' ? 'Paper Generator'
                  : currentView === 'jobs'   ? 'Jobs & Queue Monitor'
                  : currentView === 'uploader' ? 'Ingestion & OCR'
                  : currentView === 'library'  ? 'Notes & Materials'
                  : currentView === 'scraper'  ? 'Web Importer'
                  : currentView === 'questions' ? 'Question Bank'
                  : currentView === 'logs' ? 'Logs & Reports'
                  : currentView">
        </h2>
        {{-- Live badge visible when jobs are active --}}
        <span x-show="jobsData.counts?.pending > 0 || jobsData.counts?.processing > 0"
              class="pulse-dot px-2 py-0.5 bg-amber-500/10 border border-amber-500/25 text-amber-600 text-[9px] font-black rounded-full uppercase tracking-widest">
            Live Queue worker running
        </span>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase"
              x-text="new Date().toLocaleDateString('en-PK', {weekday:'short', year:'numeric', month:'short', day:'numeric'})"></span>
        <button @click="logout()"
                class="px-4 py-2 text-xs font-black text-rose-500 hover:bg-rose-50 border border-rose-100 rounded-xl transition-all duration-150 active:scale-95 flex items-center gap-2">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>
    </div>
</header>
