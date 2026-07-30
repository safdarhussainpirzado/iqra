{{-- ═══════════════════════════════════════════════════════════════════
     SUBJECTS PANEL — Premium UI
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'subjects'" class="flex gap-6 min-h-0">

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-book-open text-purple-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-purple-400" x-text="subjects.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Subjects</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-building-columns text-indigo-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-indigo-400" x-text="boards.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Boards Covered</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bookmark text-emerald-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-emerald-400" x-text="chapters.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Chapters</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle-question text-pink-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-pink-400" x-text="questions.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Questions</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-3.5 flex items-center gap-3 shadow-lg">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-book-open text-purple-400 text-sm"></i>
                <span class="font-bold text-slate-100">Subjects Registry</span>
                <span class="ml-1 px-2 py-0.5 bg-purple-500/20 text-purple-300 text-[10px] font-black rounded-full"
                      x-text="subjectsFiltered().length + ' records'"></span>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="subjectSearch" placeholder="Search subjects…"
                       class="pl-8 pr-3 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-purple-500 w-44 placeholder:text-slate-500">
            </div>
            {{-- View toggle --}}
            <div class="flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700">
                <button @click="subjectView = 'table'"
                        :class="subjectView === 'table' ? 'bg-purple-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-list"></i> List
                </button>
                <button @click="subjectView = 'grid'"
                        :class="subjectView === 'grid' ? 'bg-purple-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-grid-2"></i> Grid
                </button>
            </div>
        </div>

        {{-- TABLE VIEW --}}
        <div x-show="subjectView === 'table'" class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-800/80 text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-black">Subject</th>
                        <th class="px-6 py-4 font-black">Code</th>
                        <th class="px-6 py-4 font-black">Board</th>
                        <th class="px-6 py-4 font-black">Chapters</th>
                        <th class="px-6 py-4 font-black">Questions</th>
                        <th class="px-6 py-4 text-center font-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="subj in subjectsPaged()" :key="subj.id">
                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black flex-shrink-0"
                                         :style="`background: hsl(${(subj.id * 83) % 360}, 55%, 22%); color: hsl(${(subj.id * 83) % 360}, 65%, 72%);`"
                                         x-text="subj.name.charAt(0)"></div>
                                    <div>
                                        <div class="font-semibold text-slate-100 text-sm" x-text="subj.name"></div>
                                        <div class="text-[10px] text-slate-500">ID #<span x-text="subj.id"></span></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 bg-purple-500/15 text-purple-300 text-[11px] rounded-lg font-black font-mono border border-purple-500/20"
                                      x-text="subj.code"></span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400" x-text="subj.board?.name || '—'"></td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-emerald-400 font-bold"
                                      x-text="chapters.filter(c => c.subject_id == subj.id).length"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-pink-400 font-bold"
                                      x-text="questions.filter(q => q.subject_id == subj.id).length"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <div class="inline-grid grid-cols-2 gap-1.5">
                                        <button @click="viewSubject(subj)" title="Inspect"
                                                class="w-9 h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="confirmDeleteItem(subj,'subjects','subjects','name')" title="Delete"
                                                class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="subjectsFiltered().length === 0">
                        <tr><td colspan="6" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-book-open text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm">No subjects found.</p>
                            </div>
                        </td></tr>
                    </template>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-between bg-slate-800/40">
                <span class="text-xs text-slate-500"
                      x-text="`Showing ${Math.min((subjectPage-1)*subjectPerPage+1, subjectsFiltered().length)}–${Math.min(subjectPage*subjectPerPage, subjectsFiltered().length)} of ${subjectsFiltered().length}`"></span>
                <div class="flex items-center gap-1">
                    <button @click="subjectPage = Math.max(1, subjectPage-1)" :disabled="subjectPage===1"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </button>
                    <template x-for="p in Math.ceil(subjectsFiltered().length/subjectPerPage)" :key="p">
                        <button @click="subjectPage = p"
                                :class="subjectPage===p ? 'bg-purple-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="w-8 h-8 rounded-lg text-xs font-bold transition" x-text="p"></button>
                    </template>
                    <button @click="subjectPage = Math.min(Math.ceil(subjectsFiltered().length/subjectPerPage), subjectPage+1)"
                            :disabled="subjectPage >= Math.ceil(subjectsFiltered().length/subjectPerPage)"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- GRID VIEW --}}
        <div x-show="subjectView === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <template x-for="subj in subjectsPaged()" :key="subj.id">
                <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-purple-500/40 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-black flex-shrink-0"
                             :style="`background: hsl(${(subj.id*83)%360},55%,20%); color: hsl(${(subj.id*83)%360},65%,70%);`"
                             x-text="subj.name.charAt(0)"></div>
                        <div class="min-w-0">
                            <div class="font-bold text-slate-100 text-sm truncate" x-text="subj.name"></div>
                            <span class="px-2 py-0.5 bg-purple-500/15 text-purple-300 text-[10px] font-black font-mono rounded" x-text="subj.code"></span>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mb-1" x-text="subj.board?.name || '—'"></div>
                    <div class="text-xs text-slate-600 mb-4"
                         x-text="chapters.filter(c=>c.subject_id==subj.id).length + ' chapters'"></div>
                    <div class="inline-grid grid-cols-2 gap-1.5 w-full">
                        <button @click="viewSubject(subj)"
                                class="h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button @click="confirmDeleteItem(subj,'subjects','subjects','name')"
                                class="h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Sticky Filter Sidebar --}}
    <div class="w-56 flex-shrink-0 space-y-4 sticky top-20 self-start">
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-4 shadow-xl">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-sliders text-purple-400 text-xs"></i>
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Filters</span>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Filter by Board</label>
                    <select x-model="subjectFilterBoard" @change="subjectPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-purple-500">
                        <option value="">All Boards</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Records Per Page</label>
                    <div class="grid grid-cols-4 gap-1">
                        <template x-for="n in [5,10,20,50]" :key="n">
                            <button @click="subjectPerPage = n; subjectPage = 1"
                                    :class="subjectPerPage === n ? 'bg-purple-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                                    class="py-1.5 rounded-lg text-[10px] font-bold transition" x-text="n"></button>
                        </template>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-800">
                    <button @click="subjectSearch = ''; subjectFilterBoard = ''; subjectPage = 1"
                            class="w-full py-2 bg-rose-600/20 hover:bg-rose-600/30 border border-rose-600/30 text-rose-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-rotate-left text-xs"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Subject View Modal --}}
<div x-show="showSubjectViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-purple-600 to-indigo-800 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-book-open text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wide" x-text="viewingSubject?.name"></h3>
                    <p class="text-purple-200 text-[10px] font-bold uppercase tracking-widest">Subject Profile</p>
                </div>
            </div>
            <button @click="showSubjectViewModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Subject Name</div>
                    <div class="text-sm font-bold text-slate-100" x-text="viewingSubject?.name"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Subject Code</div>
                    <div class="text-sm font-black text-purple-400 font-mono" x-text="viewingSubject?.code"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Board</div>
                    <div class="text-sm font-bold text-indigo-400" x-text="viewingSubject?.board?.name || '—'"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Chapters</div>
                    <div class="text-sm font-bold text-emerald-400"
                         x-text="chapters.filter(c=>c.subject_id==viewingSubject?.id).length + ' chapters'"></div>
                </div>
            </div>
            <div class="flex justify-end pt-2 border-t border-slate-800">
                <button @click="showSubjectViewModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
