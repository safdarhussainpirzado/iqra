{{-- Logs & Reports Panel --}}
<div x-show="currentView === 'logs'" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">System Activity Logs</h3>
            <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredLogs().length} log entries`"></p>
        </div>
        <div class="flex items-center gap-3">
            <select x-model="logFilterAction" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Actions</option>
                <option value="ingest">Ingestion</option>
                <option value="ocr">OCR</option>
                <option value="scrape">Scraping</option>
                <option value="question">Questions</option>
                <option value="paper">Papers</option>
            </select>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="logSearch" placeholder="Search logs…"
                       class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
            <button @click="exportLogsCSV()"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-xl text-xs font-semibold transition flex items-center gap-2">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>
    </div>
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-4">Timestamp</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">IP</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="log in filteredLogs()" :key="log.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                        <td class="px-6 py-3 text-xs text-slate-400 whitespace-nowrap" x-text="log.created_at"></td>
                        <td class="px-6 py-3 text-xs font-medium" x-text="log.user?.name || 'System'"></td>
                        <td class="px-6 py-3">
                            <span :class="log.action.includes('success') || log.action.includes('create')
                                           ? 'bg-emerald-500/20 text-emerald-300'
                                           : log.action.includes('delete') || log.action.includes('fail')
                                             ? 'bg-rose-500/20 text-rose-300'
                                             : 'bg-slate-700 text-slate-300'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-black" x-text="log.action"></span>
                        </td>
                        <td class="px-6 py-3 text-xs text-slate-300 max-w-sm truncate" x-text="log.description"></td>
                        <td class="px-6 py-3 text-[10px] text-slate-500 font-mono" x-text="log.ip_address"></td>
                    </tr>
                </template>
                <template x-if="filteredLogs().length === 0">
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500 text-sm">No log entries match your filters.</td></tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
