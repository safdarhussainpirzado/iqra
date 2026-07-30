{{-- ═══════════════════════════════════════════════════════════════════
     LOGS & REPORTS PANEL — Premium UI
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'logs'" class="flex gap-6 min-h-0">

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clipboard-list text-indigo-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-indigo-400" x-text="systemLogs.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Activities</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle-check text-emerald-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-emerald-400" x-text="systemLogs.filter(l => l.action.includes('success')).length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Success Operations</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-triangle-exclamation text-rose-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-rose-400" x-text="systemLogs.filter(l => l.action.includes('fail') || l.action.includes('error')).length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Failed Actions</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-upload text-purple-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-purple-400" x-text="systemLogs.filter(l => l.action.includes('ingest')).length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Ingests Recorded</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-3.5 flex items-center gap-3 shadow-lg">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-clipboard-list text-indigo-400 text-sm"></i>
                <span class="font-bold text-slate-100">Audit Trails &amp; Activity Log</span>
                <span class="ml-1 px-2 py-0.5 bg-indigo-500/20 text-indigo-300 text-[10px] font-black rounded-full"
                      x-text="logsFiltered().length + ' records'"></span>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="logSearch" placeholder="Search activities…"
                       class="pl-8 pr-3 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
            <button @click="exportLogsCSV()"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-650 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>

        {{-- TABLE VIEW --}}
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-800/80 text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-black">Timestamp</th>
                        <th class="px-6 py-4 font-black">Operator</th>
                        <th class="px-6 py-4 font-black">Action</th>
                        <th class="px-6 py-4 font-black">Operation Details</th>
                        <th class="px-6 py-4 font-black">IP Address</th>
                        <th class="px-6 py-4 text-center font-black">Inspect</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="log in logsPaged()" :key="log.id">
                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400 whitespace-nowrap" x-text="log.created_at"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-black text-xs"
                                         x-text="log.user?.name?.charAt(0) || 'S'"></div>
                                    <span class="text-xs font-semibold text-slate-300" x-text="log.user?.name || 'System'"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="log.action.includes('success') || log.action.includes('create')
                                               ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
                                               : log.action.includes('delete') || log.action.includes('fail')
                                                 ? 'bg-rose-500/20 text-rose-300 border-rose-500/30'
                                                 : 'bg-slate-800 text-slate-400 border-slate-705'"
                                      class="px-2.5 py-1 rounded-full text-[10px] font-black border uppercase" x-text="log.action"></span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-300 max-w-xs truncate" x-text="log.description"></td>
                            <td class="px-6 py-4 text-[10px] text-slate-500 font-mono" x-text="log.ip_address"></td>
                            <td class="px-6 py-4 text-center">
                                <button @click="viewLog(log)" title="View Payload"
                                        class="w-8 h-8 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="logsFiltered().length === 0">
                        <tr><td colspan="6" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm">No activity logs recorded.</p>
                            </div>
                        </td></tr>
                    </template>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-between bg-slate-800/40">
                <span class="text-xs text-slate-500"
                      x-text="`Showing ${Math.min((logPage-1)*logPerPage+1, logsFiltered().length)}–${Math.min(logPage*logPerPage, logsFiltered().length)} of ${logsFiltered().length}`"></span>
                <div class="flex items-center gap-1">
                    <button @click="logPage = Math.max(1, logPage-1)" :disabled="logPage===1"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </button>
                    <template x-for="p in Math.ceil(logsFiltered().length/logPerPage)" :key="p">
                        <button @click="logPage = p"
                                :class="logPage===p ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="w-8 h-8 rounded-lg text-xs font-bold transition" x-text="p"></button>
                    </template>
                    <button @click="logPage = Math.min(Math.ceil(logsFiltered().length/logPerPage), logPage+1)"
                            :disabled="logPage >= Math.ceil(logsFiltered().length/logPerPage)"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky Filter Sidebar --}}
    <div class="w-56 flex-shrink-0 space-y-4 sticky top-20 self-start">
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-4 shadow-xl">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-sliders text-indigo-400 text-xs"></i>
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Filters</span>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Action Filter</label>
                    <select x-model="logFilterAction" @change="logPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Actions</option>
                        <option value="ingest">Ingestions</option>
                        <option value="ocr">OCR Tasks</option>
                        <option value="scrape">Scraping</option>
                        <option value="question">Questions</option>
                        <option value="paper">Papers</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Logs Per Page</label>
                    <div class="grid grid-cols-4 gap-1">
                        <template x-for="n in [10,25,50,100]" :key="n">
                            <button @click="logPerPage = n; logPage = 1"
                                    :class="logPerPage === n ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                                    class="py-1.5 rounded-lg text-[10px] font-bold transition" x-text="n"></button>
                        </template>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-800">
                    <button @click="logSearch = ''; logFilterAction = ''; logPage = 1"
                            class="w-full py-2 bg-rose-600/20 hover:bg-rose-600/30 border border-rose-600/30 text-rose-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-rotate-left text-xs"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Log Inspect Modal --}}
<div x-show="showLogViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wide">Audit Payload Detail</h3>
                    <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest" x-text="`Log ID #${viewingLog?.id}`"></p>
                </div>
            </div>
            <button @click="showLogViewModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Timestamp</div>
                    <div class="text-xs font-mono font-bold text-slate-300" x-text="viewingLog?.created_at"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Operator</div>
                    <div class="text-xs font-bold text-slate-300" x-text="viewingLog?.user?.name || 'System'"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Action</div>
                    <div class="text-xs font-bold text-indigo-400 uppercase" x-text="viewingLog?.action"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">IP Address</div>
                    <div class="text-xs font-mono font-bold text-slate-400" x-text="viewingLog?.ip_address"></div>
                </div>
            </div>
            <div class="bg-slate-850 border border-slate-800 rounded-xl p-4">
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Action Log Narrative</div>
                <p class="text-xs text-slate-200 leading-relaxed font-semibold" x-text="viewingLog?.description"></p>
            </div>
            <div class="flex justify-end pt-2 border-t border-slate-800">
                <button @click="showLogViewModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
