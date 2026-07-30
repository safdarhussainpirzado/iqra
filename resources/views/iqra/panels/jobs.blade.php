{{-- Jobs & Queue Monitor Panel — Premium ZIWO Light Theme --}}
<div x-show="currentView === 'jobs'" class="space-y-6" x-transition>

    {{-- Stats Row --}}
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-slate-150 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm">
                <i class="fas fa-hourglass-half text-base"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800" x-text="jobsData.counts?.pending || 0"></div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pending Tasks</div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl border border-slate-150 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                <span class="pulse-dot w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800" x-text="jobsData.counts?.processing || 0"></div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Active Processing</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-150 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-sm">
                <i class="fas fa-circle-xmark text-base"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800" x-text="jobsData.counts?.failed || 0"></div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Failed Actions</div>
            </div>
        </div>
    </div>

    {{-- Active Queue --}}
    <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="bg-blue-50/40 px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-blue-600"><i class="fas fa-list-check"></i></div>
                <div>
                    <h4 class="text-sm font-extrabold text-blue-900 uppercase tracking-wider">Active Operations Queue</h4>
                    <p class="text-[10px] text-slate-400 font-bold mt-0.5">Tasks currently staged for worker execution</p>
                </div>
            </div>
            <button @click="loadJobs()" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-black uppercase tracking-widest rounded-xl transition flex items-center gap-2 active:scale-95">
                <i class="fas fa-rotate-right"></i> Refresh
            </button>
        </div>
        
        <table class="w-full text-left spacious-table">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Job Type</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Attempts</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="job in jobsData.pending || []" :key="job.id">
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-xs font-mono text-slate-400" x-text="job.id"></td>
                        <td class="px-6 py-4 font-bold text-slate-700 text-xs" x-text="job.job_name"></td>
                        <td class="px-6 py-4">
                            <span :class="job.status === 'processing' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-amber-50 text-amber-600 border-amber-100'"
                                  class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase tracking-wide flex items-center gap-1.5 w-fit">
                                <span x-show="job.status === 'processing'" class="pulse-dot w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span x-text="job.status"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-bold" x-text="job.attempts"></td>
                        <td class="px-6 py-4 text-xs text-slate-400" x-text="job.created_at"></td>
                    </tr>
                </template>
                <template x-if="(jobsData.pending || []).length === 0">
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm font-bold">No active jobs in the queue.</td></tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Failed Jobs --}}
    <div class="bg-white rounded-[2rem] border border-rose-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="bg-rose-50/40 px-8 py-5 border-b border-rose-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-rose-100 shadow-sm flex items-center justify-center text-rose-600"><i class="fas fa-triangle-exclamation"></i></div>
                <div>
                    <h4 class="text-sm font-extrabold text-rose-900 uppercase tracking-wider">Failed Operations List</h4>
                    <p class="text-[10px] text-rose-400 font-bold mt-0.5">Exceptions recorded during ingestion cycles</p>
                </div>
            </div>
        </div>
        
        <table class="w-full text-left spacious-table">
            <thead class="bg-rose-50/30 border-b border-rose-100">
                <tr>
                    <th class="px-6 py-4 text-[9px] font-black text-rose-400 uppercase tracking-widest">ID</th>
                    <th class="px-6 py-4 text-[9px] font-black text-rose-400 uppercase tracking-widest">Job Type</th>
                    <th class="px-6 py-4 text-[9px] font-black text-rose-400 uppercase tracking-widest">Error</th>
                    <th class="px-6 py-4 text-[9px] font-black text-rose-400 uppercase tracking-widest">Failed At</th>
                    <th class="px-6 py-4 text-center text-[9px] font-black text-rose-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                <template x-for="job in jobsData.failed || []" :key="job.id">
                    <tr class="hover:bg-rose-50/20 transition">
                        <td class="px-6 py-4 text-xs font-mono text-rose-400" x-text="job.id"></td>
                        <td class="px-6 py-4 font-bold text-slate-700 text-xs" x-text="job.job_name"></td>
                        <td class="px-6 py-4 text-xs text-rose-600 max-w-xs truncate font-mono" x-text="job.error"></td>
                        <td class="px-6 py-4 text-xs text-slate-400" x-text="job.created_at"></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="retryJob(job.id)" title="Retry Job"
                                        class="w-9 h-9 rounded-xl bg-amber-500 border border-amber-600 text-white flex items-center justify-center text-xs hover:bg-amber-600 active:scale-95 transition shadow-sm">
                                    <i class="fas fa-rotate-right"></i>
                                </button>
                                <button @click="deleteFailedJob(job.id)" title="Purge Job"
                                        class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-700 active:scale-95 transition shadow-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="(jobsData.failed || []).length === 0">
                    <tr><td colspan="5" class="px-6 py-10 text-center text-emerald-600 font-bold text-sm">All operations cleared. No failed items found.</td></tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Completed OCR Jobs --}}
    <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="bg-blue-50/40 px-8 py-5 border-b border-slate-100">
            <h4 class="text-sm font-extrabold text-blue-900 uppercase tracking-wider">Completed OCR Jobs Activity Log</h4>
        </div>
        
        <table class="w-full text-left spacious-table">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Timestamp</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">User</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Document</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="log in systemLogs.filter(l => l.action.includes('ingest') || l.action.includes('ocr'))" :key="log.id">
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-xs text-slate-400 font-mono" x-text="log.created_at"></td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-700" x-text="log.user?.name || 'System'"></td>
                        <td class="px-6 py-4">
                            <span :class="log.action.includes('success') ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'"
                                  class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase" x-text="log.action"></span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600" x-text="log.description"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
