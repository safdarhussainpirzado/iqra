{{-- ═══════════════════════════════════════════════════════════════════
     CHAPTERS PANEL — Premium UI
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'chapters'" class="flex gap-6 min-h-0">

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bookmark text-indigo-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-indigo-400" x-text="chapters.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Chapters Indexed</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-book-open text-purple-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-purple-400" x-text="subjects.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Active Subjects</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle-question text-emerald-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-emerald-400" x-text="questions.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Linked Questions</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-invoice text-pink-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-pink-400" x-text="libraryItems.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Extracted Corpora</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-3.5 flex items-center gap-3 shadow-lg">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-bookmark text-indigo-400 text-sm"></i>
                <span class="font-bold text-slate-100">Chapters Index</span>
                <span class="ml-1 px-2 py-0.5 bg-indigo-500/20 text-indigo-300 text-[10px] font-black rounded-full"
                      x-text="chaptersFiltered().length + ' records'"></span>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="chapterSearch" placeholder="Search chapters…"
                       class="pl-8 pr-3 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
            {{-- View toggle --}}
            <div class="flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700">
                <button @click="chapterView = 'table'"
                        :class="chapterView === 'table' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-list"></i> List
                </button>
                <button @click="chapterView = 'grid'"
                        :class="chapterView === 'grid' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-grid-2"></i> Grid
                </button>
            </div>
            <button @click="openChapterCreateModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Chapter
            </button>
        </div>

        {{-- TABLE VIEW --}}
        <div x-show="chapterView === 'table'" class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-800/80 text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-black">Ch #</th>
                        <th class="px-6 py-4 font-black">Chapter Title</th>
                        <th class="px-6 py-4 font-black">Subject</th>
                        <th class="px-6 py-4 font-black">Board</th>
                        <th class="px-6 py-4 text-center font-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="ch in chaptersPaged()" :key="ch.id">
                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/40 transition group">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 bg-slate-800 text-slate-400 text-xs font-mono font-black rounded-lg border border-slate-750"
                                      x-text="`Ch ${ch.chapter_number}`"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-bold text-slate-100 text-sm" x-text="ch.title"></div>
                                    <div class="text-[10px] text-slate-500">ID #<span x-text="ch.id"></span></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400" x-text="ch.subject?.name || '—'"></td>
                            <td class="px-6 py-4 text-xs text-slate-500" x-text="ch.board?.name || '—'"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <div class="inline-grid grid-cols-3 gap-1.5">
                                        <button @click="viewChapter(ch)" title="Inspect"
                                                class="w-9 h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="editChapter(ch)" title="Edit"
                                                class="w-9 h-9 rounded-xl bg-indigo-600 border border-indigo-700 text-white flex items-center justify-center text-xs hover:bg-indigo-500 active:scale-95 transition">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <button @click="confirmDeleteChapter(ch)" title="Delete"
                                                class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="chaptersFiltered().length === 0">
                        <tr><td colspan="5" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-bookmark text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm">No chapters found.</p>
                            </div>
                        </td></tr>
                    </template>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-between bg-slate-800/40">
                <span class="text-xs text-slate-500"
                      x-text="`Showing ${Math.min((chapterPage-1)*chapterPerPage+1, chaptersFiltered().length)}–${Math.min(chapterPage*chapterPerPage, chaptersFiltered().length)} of ${chaptersFiltered().length}`"></span>
                <div class="flex items-center gap-1">
                    <button @click="chapterPage = Math.max(1, chapterPage-1)" :disabled="chapterPage===1"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </button>
                    <template x-for="p in Math.ceil(chaptersFiltered().length/chapterPerPage)" :key="p">
                        <button @click="chapterPage = p"
                                :class="chapterPage===p ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="w-8 h-8 rounded-lg text-xs font-bold transition" x-text="p"></button>
                    </template>
                    <button @click="chapterPage = Math.min(Math.ceil(chaptersFiltered().length/chapterPerPage), chapterPage+1)"
                            :disabled="chapterPage >= Math.ceil(chaptersFiltered().length/chapterPerPage)"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- GRID VIEW --}}
        <div x-show="chapterView === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <template x-for="ch in chaptersPaged()" :key="ch.id">
                <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-indigo-500/40 transition flex flex-col justify-between min-h-[180px]">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-1 bg-indigo-500/10 text-indigo-300 text-[10px] font-black rounded-lg border border-indigo-500/20"
                                  x-text="`Ch ${ch.chapter_number}`"></span>
                            <span class="text-[10px] text-slate-500" x-text="ch.board?.code || ''"></span>
                        </div>
                        <h4 class="font-bold text-slate-100 text-sm leading-snug line-clamp-2" x-text="ch.title"></h4>
                        <div class="text-[10px] text-slate-500 mt-1 truncate" x-text="ch.subject?.name"></div>
                    </div>
                    <div class="inline-grid grid-cols-3 gap-1.5 w-full mt-4">
                        <button @click="viewChapter(ch)"
                                class="h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button @click="editChapter(ch)"
                                class="h-9 rounded-xl bg-indigo-600 border border-indigo-700 text-white flex items-center justify-center text-xs hover:bg-indigo-500 active:scale-95 transition">
                            <i class="fas fa-pencil"></i>
                        </button>
                        <button @click="confirmDeleteChapter(ch)"
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
                <i class="fas fa-sliders text-indigo-400 text-xs"></i>
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Filters</span>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Filter by Board</label>
                    <select x-model="chapterFilterBoard" @change="chapterPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Boards</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Filter by Subject</label>
                    <select x-model="chapterFilterSubject" @change="chapterPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Subjects</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Records Per Page</label>
                    <div class="grid grid-cols-4 gap-1">
                        <template x-for="n in [5,10,20,50]" :key="n">
                            <button @click="chapterPerPage = n; chapterPage = 1"
                                    :class="chapterPerPage === n ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                                    class="py-1.5 rounded-lg text-[10px] font-bold transition" x-text="n"></button>
                        </template>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-800">
                    <button @click="chapterSearch = ''; chapterFilterBoard = ''; chapterFilterSubject = ''; chapterPage = 1"
                            class="w-full py-2 bg-rose-600/20 hover:bg-rose-600/30 border border-rose-600/30 text-rose-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-rotate-left text-xs"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chapter View Modal --}}
<div x-show="showChapterViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-800 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-bookmark text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wide" x-text="viewingChapter?.title"></h3>
                    <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Chapter Details</p>
                </div>
            </div>
            <button @click="showChapterViewModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Chapter Title</div>
                    <div class="text-sm font-bold text-slate-100" x-text="viewingChapter?.title"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Chapter Number</div>
                    <div class="text-sm font-black text-indigo-400 font-mono" x-text="viewingChapter?.chapter_number"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Subject</div>
                    <div class="text-sm font-bold text-purple-400" x-text="viewingChapter?.subject?.name || '—'"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Board</div>
                    <div class="text-sm font-bold text-pink-400" x-text="viewingChapter?.board?.name || '—'"></div>
                </div>
            </div>
            <div class="flex justify-end pt-2 border-t border-slate-800">
                <button @click="showChapterViewModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div x-show="showChapterModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-700 via-indigo-800 to-purple-900 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-bookmark text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white" x-text="chapterForm.id ? 'Edit Chapter' : 'Register Chapter'"></h3>
                    <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest"
                       x-text="chapterForm.id ? 'CHAPTER ID: ' + chapterForm.id : 'NEW RECORD'"></p>
                </div>
            </div>
            <button @click="showChapterModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <form @submit.prevent="saveChapter()" class="p-6 space-y-4" data-no-pjax>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Board</label>
                    <select x-model="chapterForm.board_id" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Subject</label>
                    <select x-model="chapterForm.subject_id" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">Select Subject</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Chapter Number</label>
                    <input type="number" x-model="chapterForm.chapter_number" required
                           class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Chapter Title</label>
                    <input type="text" x-model="chapterForm.title" required placeholder="E.g., Matrices & Determinants"
                           class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
            </div>
            <template x-if="chapterError">
                <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400" x-text="chapterError"></div>
            </template>
            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                <button type="button" @click="showChapterModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-xs font-black text-white rounded-xl transition shadow-lg uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-floppy-disk"></i>
                    <span>Save Chapter</span>
                </button>
            </div>
        </form>
    </div>
</div>
