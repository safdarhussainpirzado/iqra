{{-- ═══════════════════════════════════════════════════════════════════
     LOGS & REPORTS PANEL — Premium ZIWO Light Theme
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'logs'" class="space-y-6" x-transition>

    {{-- Stats Row matching ZIWO exactly with absolute top offset icons and status filters --}}
    <div class="grid grid-cols-4 gap-6 pt-6">
        <div @click="logFilterAction = ''; logSearch = '';"
             :class="logFilterAction === '' ? 'card-3d-active indigo' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-indigo-500 shadow-[0_8px_16px_rgba(99,102,241,0.2)] text-white">
                <i class="fas fa-clipboard-list text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Activities</span>
                <span class="text-3xl font-black text-slate-800" x-text="systemLogs.length"></span>
            </div>
        </div>
        <div @click="logFilterAction = 'success';"
             :class="logFilterAction === 'success' ? 'card-3d-active emerald' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-emerald-500 shadow-[0_8px_16px_rgba(16,185,129,0.2)] text-white">
                <i class="fas fa-circle-check text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Success Operations</span>
                <span class="text-3xl font-black text-slate-800" x-text="systemLogs.filter(l => l.action.includes('success')).length"></span>
            </div>
        </div>
        <div @click="logFilterAction = 'fail';"
             :class="logFilterAction === 'fail' ? 'card-3d-active rose' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-rose-500 shadow-[0_8px_16px_rgba(225,29,72,0.2)] text-white">
                <i class="fas fa-triangle-exclamation text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Failed Actions</span>
                <span class="text-3xl font-black text-slate-800" x-text="systemLogs.filter(l => l.action.includes('fail') || l.action.includes('error')).length"></span>
            </div>
        </div>
        <div @click="logFilterAction = 'ingest';"
             :class="logFilterAction === 'ingest' ? 'card-3d-active purple' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-purple-500 shadow-[0_8px_16px_rgba(168,85,247,0.2)] text-white">
                <i class="fas fa-upload text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Ingests Recorded</span>
                <span class="text-3xl font-black text-slate-800" x-text="systemLogs.filter(l => l.action.includes('ingest')).length"></span>
            </div>
        </div>
    </div>

    {{-- Main Control Wrapper --}}
    <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Panel Header --}}
        <div class="bg-blue-50/40 px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-blue-900 tracking-tight flex items-center gap-2">
                        Audit Trails &amp; Activity Log <span class="text-sm font-bold text-slate-400" x-text="'(' + logsFiltered().length + ' records)'"></span>
                    </h2>
                    <p class="text-slate-500 text-xs font-bold mt-0.5">Track system mutations, file ingestion outputs, and OCR logs</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                {{-- Row Density dropdown from ZIWO --}}
                <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-3 py-1.5 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 border-r border-slate-100 pr-2 uppercase font-mono">Row Density</span>
                    <select x-model="density" class="bg-transparent text-blue-650 text-[10px] font-black uppercase cursor-pointer outline-none focus:ring-0 border-none p-0 pr-4">
                        <option value="condensed">Condensed</option>
                        <option value="spacious">Spacious</option>
                    </select>
                </div>

                <button @click="exportLogsCSV()" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all active:scale-95">
                    <i class="fas fa-download"></i> Export CSV
                </button>

                {{-- ZIWO Funnel Filter Toggle --}}
                <button @click="showFilters = !showFilters" :class="showFilters ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-400 hover:text-blue-650'" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        {{-- Table Area with Collapsible Sidebar Filters --}}
        <div class="flex gap-0 min-h-0 relative">
            
            <div class="flex-1 min-w-0 border-r border-slate-100">
                {{-- TABLE VIEW --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left" :class="density === 'condensed' ? 'condensed-table' : 'spacious-table'">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-clock text-[9px]"></i></div>
                                        <span>Timestamp</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-user-circle text-[9px]"></i></div>
                                        <span>Operator</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-tag text-[9px]"></i></div>
                                        <span>Action</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-quote-left text-[9px]"></i></div>
                                        <span>Details</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-network-wired text-[9px]"></i></div>
                                        <span>IP Address</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <span>Inspect</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="log in logsPaged()" :key="log.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-xs font-mono text-slate-400 whitespace-nowrap" x-text="log.created_at"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-650 flex items-center justify-center font-black text-[10px]"
                                                 x-text="log.user?.name?.charAt(0) || 'S'"></div>
                                            <span class="text-xs font-bold text-slate-700" x-text="log.user?.name || 'System'"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="log.action.includes('success') || log.action.includes('create')
                                                       ? 'bg-emerald-50 text-emerald-600 border-emerald-100'
                                                       : log.action.includes('delete') || log.action.includes('fail')
                                                         ? 'bg-rose-50 text-rose-600 border-rose-100'
                                                         : 'bg-slate-100 text-slate-500 border-slate-200'"
                                              class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase" x-text="log.action"></span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-600 max-w-xs truncate" x-text="log.description"></td>
                                    <td class="px-6 py-4 text-[10px] text-slate-500 font-mono" x-text="log.ip_address"></td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="viewLog(log)" title="View Payload"
                                                class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition flex items-center justify-center mx-auto">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="logsFiltered().length === 0">
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                                <i class="fas fa-clipboard-list text-slate-400 text-2xl"></i>
                                            </div>
                                            <p class="text-slate-400 text-sm font-bold">No activity logs recorded.</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- Pagination Row from ZIWO --}}
                    <div class="px-8 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-400"
                              x-text="`Showing ${Math.min((logPage-1)*logPerPage+1, logsFiltered().length)}–${Math.min(logPage*logPerPage, logsFiltered().length)} of ${logsFiltered().length} activities`"></span>
                        <div class="flex items-center gap-1">
                            <button @click="logPage = Math.max(1, logPage - 1)" :disabled="logPage === 1"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </button>
                            <template x-for="p in Math.ceil(logsFiltered().length / logPerPage)" :key="p">
                                <button @click="logPage = p"
                                        :class="logPage === p ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-black' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                                        class="w-8 h-8 rounded-lg text-xs transition" x-text="p"></button>
                            </template>
                            <button @click="logPage = Math.min(Math.ceil(logsFiltered().length/logPerPage), logPage + 1)"
                                    :disabled="logPage >= Math.ceil(logsFiltered().length / logPerPage)"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Collapsible Sidebar Filter, aligned exactly where table begins --}}
            <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10"
                 class="w-64 flex-shrink-0 bg-white p-6 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-sliders text-blue-600 text-xs"></i>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Sidebar Filters</span>
                    </div>
                    <button @click="showFilters = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-4">
                    {{-- Action filter --}}
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Action Filter</label>
                        <select x-model="logFilterAction" @change="logPage = 1"
                                class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none">
                            <option value="">All Actions</option>
                            <option value="ingest">Ingestions</option>
                            <option value="ocr">OCR Tasks</option>
                            <option value="scrape">Scraping</option>
                            <option value="question">Questions</option>
                            <option value="paper">Papers</option>
                        </select>
                    </div>

                    {{-- Records per page --}}
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Records Per Page</label>
                        <div class="grid grid-cols-4 gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200/50">
                            <template x-for="n in [10,25,50,100]" :key="n">
                                <button @click="logPerPage = n; logPage = 1"
                                        :class="logPerPage === n ? 'bg-white text-blue-600 shadow-sm font-black' : 'text-slate-500 hover:text-slate-700'"
                                        class="py-1.5 text-[9px] uppercase tracking-widest rounded-lg transition-all" x-text="n"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Action triggers --}}
                    <div class="pt-4 border-t border-slate-100">
                        <button @click="logSearch = ''; logFilterAction = ''; logPage = 1"
                                class="w-full py-4 text-rose-500 hover:bg-rose-50 rounded-3xl text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 active:scale-95 border border-rose-100">
                            <i class="fas fa-broom"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Log Inspect Modal --}}
<div x-show="showLogViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden" @click.away="showLogViewModal = false">
        <div class="bg-gradient-to-r from-indigo-700 to-indigo-900 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none">Audit Narrative Payload</h3>
                    <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mt-1" x-text="`Log Entry ID #${viewingLog?.id}`"></p>
                </div>
            </div>
            <button @click="showLogViewModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <div class="p-8 space-y-5 bg-slate-50/50">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Timestamp</span>
                    <span class="text-xs font-mono font-bold text-slate-700 block" x-text="viewingLog?.created_at"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Operator</span>
                    <span class="text-xs font-bold text-slate-700 truncate block" x-text="viewingLog?.user?.name || 'System'"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Action Code</span>
                    <span class="text-xs font-black text-indigo-650 uppercase block" x-text="viewingLog?.action"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">IP Address</span>
                    <span class="text-xs font-mono font-bold text-slate-500 block" x-text="viewingLog?.ip_address"></span>
                </div>
            </div>
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Audit Message Narrative</span>
                <p class="text-xs text-slate-700 leading-relaxed font-semibold" x-text="viewingLog?.description"></p>
            </div>
            <div class="flex justify-end pt-2">
                <button @click="showLogViewModal = false" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95">Close Details</button>
            </div>
        </div>
    </div>
</div>
