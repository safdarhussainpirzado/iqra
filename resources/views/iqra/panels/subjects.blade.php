{{-- Subjects Panel --}}
<div x-show="currentView === 'subjects'" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">Registered Subjects</h3>
            <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredSubjects().length} subjects`"></p>
        </div>
        <div class="flex items-center gap-3">
            <select x-model="subjectFilterBoard" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Boards</option>
                <template x-for="board in boards" :key="board.id">
                    <option :value="board.id" x-text="board.name"></option>
                </template>
            </select>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="subjectSearch" placeholder="Search subjects…"
                       class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
        </div>
    </div>
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-4">Subject Name</th>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">Board</th>
                    <th class="px-6 py-4">Class Level</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="subj in filteredSubjects()" :key="subj.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition">
                        <td class="px-6 py-3.5 font-semibold" x-text="subj.name"></td>
                        <td class="px-6 py-3.5">
                            <span class="px-2.5 py-1 bg-purple-500/15 text-purple-300 text-xs rounded-lg font-mono font-bold" x-text="subj.code"></span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="subj.board?.name || '—'"></td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="subj.class ? 'Class ' + subj.class_id : '—'"></td>
                    </tr>
                </template>
                <template x-if="filteredSubjects().length === 0">
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">No subjects found.</td></tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
