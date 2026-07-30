{{-- Jobs & Queue Monitor Panel --}}
<div x-show="currentView === 'jobs'" class="space-y-6">

    {{-- Stats row --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-5 rounded-2xl shadow-xl flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center"><i class="fas fa-hourglass-half text-amber-400"></i></div>
            <div>
                <div class="text-2xl font-black text-amber-400" x-text="jobsData.counts?.pending || 0"></div>
                <div class="text-xs text-slate-500 font-medium">Pending</div>
            </div>
        </div>
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-5 rounded-2xl shadow-xl flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                <div class="pulse-dot w-3 h-3 rounded-full bg-blue-400"></div>
            </div>
            <div>
                <div class="text-2xl font-black text-blue-400" x-text="jobsData.counts?.processing || 0"></div>
                <div class="text-xs text-slate-500 font-medium">Processing</div>
            </div>
        </div>
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-5 rounded-2xl shadow-xl flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center"><i class="fas fa-circle-xmark text-rose-400"></i></div>
            <div>
                <div class="text-2xl font-black text-rose-400" x-text="jobsData.counts?.failed || 0"></div>
                <div class="text-xs text-slate-500 font-medium">Failed</div>
            </div>
        </div>
    </div>

    {{-- Pending / Active Jobs --}}
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-200 flex items-center gap-2"><i class="fas fa-list-check text-indigo-400"></i> Active Queue</h4>
            <button @click="loadJobs()" class="text-xs text-slate-400 hover:text-indigo-400 flex items-center gap-1 transition">
                <i class="fas fa-rotate-right text-xs"></i> Refresh
            </button>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Job Type</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Attempts</th>
                    <th class="px-6 py-3">Created</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="job in jobsData.pending || []" :key="job.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                        <td class="px-6 py-3.5 text-xs font-mono text-slate-500" x-text="job.id"></td>
                        <td class="px-6 py-3.5 font-medium text-xs" x-text="job.job_name"></td>
                        <td class="px-6 py-3.5">
                            <span :class="job.status === 'processing' ? 'bg-blue-500/20 text-blue-300' : 'bg-amber-500/20 text-amber-300'"
                                  class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide flex items-center gap-1.5 w-fit">
                                <span x-show="job.status === 'processing'" class="pulse-dot w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                <span x-text="job.status"></span>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="job.attempts"></td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="job.created_at"></td>
                    </tr>
                </template>
                <template x-if="(jobsData.pending || []).length === 0">
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">No active jobs. Upload a PDF with OCR to see jobs appear here.</td></tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Failed Jobs --}}
    <div class="backdrop-blur-md bg-slate-900/60 border border-rose-900/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h4 class="text-sm font-bold text-rose-400 flex items-center gap-2"><i class="fas fa-circle-xmark"></i> Failed Jobs</h4>
            <span class="text-xs text-slate-500">Retry or purge individual failed jobs below</span>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Job Type</th>
                    <th class="px-6 py-3">Error</th>
                    <th class="px-6 py-3">Failed At</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="job in jobsData.failed || []" :key="job.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                        <td class="px-6 py-3.5 text-xs font-mono text-slate-500" x-text="job.id"></td>
                        <td class="px-6 py-3.5 font-medium text-xs" x-text="job.job_name"></td>
                        <td class="px-6 py-3.5 text-xs text-rose-400 max-w-xs truncate" x-text="job.error"></td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="job.created_at"></td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="retryJob(job.id)" title="Retry Job"
                                        class="w-9 h-9 rounded-xl bg-amber-500 border border-amber-600 text-white flex items-center justify-center text-xs hover:bg-amber-400 active:scale-95 transition">
                                    <i class="fas fa-rotate-right"></i>
                                </button>
                                <button @click="deleteFailedJob(job.id)" title="Purge Job"
                                        class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="(jobsData.failed || []).length === 0">
                    <tr><td colspan="5" class="px-6 py-8 text-center text-emerald-500/70 text-sm"><i class="fas fa-check-circle mr-2"></i>No failed jobs — all clear!</td></tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Completed OCR Jobs (Activity Log) --}}
    <div class="backdrop-blur-md bg-slate-900/60 border border-emerald-900/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-slate-800">
            <h4 class="text-sm font-bold text-emerald-400 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Completed OCR Jobs (Activity Log)
            </h4>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3">Timestamp</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Action</th>
                    <th class="px-6 py-3">Document</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="log in systemLogs.filter(l => l.action.includes('ingest') || l.action.includes('ocr'))" :key="log.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="log.created_at"></td>
                        <td class="px-6 py-3.5 text-xs font-medium" x-text="log.user?.name || 'System'"></td>
                        <td class="px-6 py-3.5">
                            <span :class="log.action.includes('success') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-black" x-text="log.action"></span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-300" x-text="log.description"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
