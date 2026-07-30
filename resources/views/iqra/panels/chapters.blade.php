{{-- ═══════════════════════════════════════════════════════════════════
     CHAPTERS PANEL — Premium ZIWO Light Theme
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'chapters'" class="flex gap-8 min-h-0" x-transition>

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-6">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl border border-slate-150/80 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                    <i class="fas fa-bookmark text-base"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800" x-text="chapters.length"></div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Chapters Indexed</div>
                </div>
            </div>
            <div class="bg-white rounded-3xl border border-slate-150/80 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm">
                    <i class="fas fa-book-open text-base"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800" x-text="subjects.length"></div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Active Subjects</div>
                </div>
            </div>
            <div class="bg-white rounded-3xl border border-slate-150/80 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fas fa-circle-question text-base"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800" x-text="questions.length"></div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Linked Questions</div>
                </div>
            </div>
            <div class="bg-white rounded-3xl border border-slate-150/80 p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-pink-50 flex items-center justify-center text-pink-600 shadow-sm">
                    <i class="fas fa-file-invoice text-base"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800" x-text="libraryItems.length"></div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Extracted Corpora</div>
                </div>
            </div>
        </div>

        {{-- Main Control Wrapper --}}
        <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
            
            {{-- Panel Header --}}
            <div class="bg-blue-50/40 px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center">
                        <i class="fas fa-bookmark text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-blue-900 tracking-tight flex items-center gap-2">
                            Chapters Index <span class="text-sm font-bold text-slate-400" x-text="'(' + chaptersFiltered().length + ' records)'"></span>
                        </h2>
                        <p class="text-slate-500 text-xs font-bold mt-0.5">Manage chapters and associate them with subject structures</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                    {{-- Row Density dropdown from ZIWO --}}
                    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-3 py-1.5 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 border-r border-slate-100 pr-2 uppercase font-mono">Row Density</span>
                        <select x-model="density" class="bg-transparent text-blue-600 text-[10px] font-black uppercase cursor-pointer outline-none focus:ring-0 border-none p-0 pr-4">
                            <option value="condensed">Condensed</option>
                            <option value="spacious">Spacious</option>
                        </select>
                    </div>

                    {{-- List/Grid toggle from ZIWO --}}
                    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl p-1.5 shadow-sm">
                        <button @click="chapterView = 'table'" :class="chapterView === 'table' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-blue-600'" class="w-9 h-9 flex items-center justify-center rounded-lg transition-all"><i class="fas fa-list-ul"></i></button>
                        <button @click="chapterView = 'grid'" :class="chapterView === 'grid' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-blue-600'" class="w-9 h-9 flex items-center justify-center rounded-lg transition-all"><i class="fas fa-th-large"></i></button>
                    </div>

                    <button @click="openChapterCreateModal()" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all active:scale-95">
                        <i class="fas fa-plus"></i> Add Chapter
                    </button>
                </div>
            </div>

            {{-- ── TABLE VIEW ───────────────────────────────────────────── --}}
            <div x-show="chapterView === 'table'" class="overflow-x-auto">
                <table class="w-full text-left" :class="density === 'condensed' ? 'condensed-table' : 'spacious-table'">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">
                                <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-list-ol text-[9px]"></i></div>
                                    <span>Ch #</span>
                                </div>
                            </th>
                            <th class="px-6 py-4">
                                <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-bookmark text-[9px]"></i></div>
                                    <span>Chapter Title</span>
                                </div>
                            </th>
                            <th class="px-6 py-4">
                                <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-book-open text-[9px]"></i></div>
                                    <span>Subject</span>
                                </div>
                            </th>
                            <th class="px-6 py-4">
                                <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100 shadow-sm"><i class="fas fa-building text-[9px]"></i></div>
                                    <span>Board</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    <span>Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="ch in chaptersPaged()" :key="ch.id">
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-lg border border-slate-200 font-mono" x-text="`Ch ${ch.chapter_number}`"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm" x-text="ch.title"></div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">ID: #<span x-text="ch.id"></span></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-500" x-text="ch.subject?.name || '—'"></td>
                                <td class="px-6 py-4 text-xs text-slate-500" x-text="ch.board?.name || '—'"></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        <div class="inline-grid grid-cols-3 gap-1.5">
                                            <button @click="viewChapter(ch)" title="Inspect Data" class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition flex items-center justify-center">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                            <button @click="editChapter(ch)" title="Modify Properties" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white hover:bg-indigo-600 active:scale-95 transition flex items-center justify-center">
                                                <i class="fas fa-sliders text-xs"></i>
                                            </button>
                                            <button @click="confirmDeleteChapter(ch)" title="Purge Record" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white hover:bg-rose-700 active:scale-95 transition flex items-center justify-center">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="chaptersFiltered().length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                            <i class="fas fa-bookmark text-slate-400 text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 text-sm font-bold">No chapters matching the query.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                {{-- Pagination Row from ZIWO --}}
                <div class="px-8 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <span class="text-xs font-bold text-slate-400"
                          x-text="`Showing ${Math.min((chapterPage-1)*chapterPerPage+1, chaptersFiltered().length)}–${Math.min(chapterPage*chapterPerPage, chaptersFiltered().length)} of ${chaptersFiltered().length} chapters`"></span>
                    <div class="flex items-center gap-1">
                        <button @click="chapterPage = Math.max(1, chapterPage - 1)" :disabled="chapterPage === 1"
                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                        <template x-for="p in Math.ceil(chaptersFiltered().length / chapterPerPage)" :key="p">
                            <button @click="chapterPage = p"
                                    :class="chapterPage === p ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-black' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                                    class="w-8 h-8 rounded-lg text-xs transition" x-text="p"></button>
                        </template>
                        <button @click="chapterPage = Math.min(Math.ceil(chaptersFiltered().length/chapterPerPage), chapterPage + 1)"
                                :disabled="chapterPage >= Math.ceil(chaptersFiltered().length / chapterPerPage)"
                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── GRID VIEW ────────────────────────────────────────────── --}}
            <div x-show="chapterView === 'grid'" class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    <template x-for="ch in chaptersPaged()" :key="ch.id">
                        <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-slate-150 p-8 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden flex flex-col justify-between min-h-[250px]">
                            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                                <i class="fas fa-bookmark text-9xl text-blue-900"></i>
                            </div>
                            
                            <div>
                                <div class="flex items-start justify-between mb-6 relative z-10">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg border border-blue-100 font-mono uppercase" x-text="`Ch ${ch.chapter_number}`"></span>
                                    <div class="text-right">
                                        <div class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">State</div>
                                        <span class="flex items-center gap-1.5 text-blue-600 font-black text-[10px] tracking-widest uppercase justify-end">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Active
                                        </span>
                                    </div>
                                </div>

                                <div class="relative z-10">
                                    <h4 class="text-xl font-extrabold text-blue-900 tracking-tight leading-tight mb-2 line-clamp-2" x-text="ch.title"></h4>
                                    
                                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100/80 mb-4">
                                        <div class="space-y-2 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subject</span>
                                                <span class="font-bold text-slate-700 truncate max-w-[150px]" x-text="ch.subject?.name"></span>
                                            </div>
                                            <div class="flex justify-between border-t border-slate-250/20 pt-2">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Board</span>
                                                <span class="font-bold text-slate-500 truncate max-w-[150px]" x-text="ch.board?.name"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-slate-100 space-y-3 relative z-10">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-bookmark text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Chapter Registry</span>
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <button @click="viewChapter(ch)" title="Inspect Data" class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <button @click="editChapter(ch)" title="Modify Parameters" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white hover:bg-indigo-600 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                        <i class="fa-solid fa-sliders text-xs"></i>
                                    </button>
                                    <button @click="confirmDeleteChapter(ch)" title="Purge Record" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white hover:bg-rose-700 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                        <i class="fa-solid fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

    </div>

    {{-- ── Sticky Filter Sidebar ─────────────────────────────────────── --}}
    <div class="w-64 flex-shrink-0 space-y-4 sticky top-20 self-start">
        <div class="bg-white border border-slate-150 rounded-3xl p-5 shadow-[0_10px_40px_rgba(0,0,0,0.02)] space-y-5">
            <div class="flex items-center gap-2 mb-2 pb-3 border-b border-slate-100">
                <i class="fas fa-sliders text-blue-600 text-xs"></i>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Sidebar Filters</span>
            </div>
            
            <div class="space-y-4">
                {{-- Board filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Board</label>
                    <select x-model="chapterFilterBoard" @change="chapterPage = 1"
                            class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none">
                        <option value="">All Boards</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>

                {{-- Subject filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subject</label>
                    <select x-model="chapterFilterSubject" @change="chapterPage = 1"
                            class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none">
                        <option value="">All Subjects</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>

                {{-- Records per page --}}
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Records Per Page</label>
                    <div class="grid grid-cols-4 gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200/50">
                        <template x-for="n in [5,10,20,50]" :key="n">
                            <button @click="chapterPerPage = n; chapterPage = 1"
                                    :class="chapterPerPage === n ? 'bg-white text-blue-600 shadow-sm font-black' : 'text-slate-500 hover:text-slate-700'"
                                    class="py-1.5 text-[9px] uppercase tracking-widest rounded-lg transition-all" x-text="n"></button>
                        </template>
                    </div>
                </div>

                {{-- Action triggers --}}
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <button @click="chapterSearch = ''; chapterFilterBoard = ''; chapterFilterSubject = ''; chapterPage = 1"
                            class="w-full py-4 text-rose-500 hover:bg-rose-50 rounded-3xl text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 active:scale-95 border border-rose-100">
                        <i class="fas fa-broom"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chapter View Modal --}}
<div x-show="showChapterViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden" @click.away="showChapterViewModal = false">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-750 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-bookmark"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none" x-text="viewingChapter?.title"></h3>
                    <p class="text-[9px] font-black text-blue-200 uppercase tracking-widest mt-1">Chapter detail values</p>
                </div>
            </div>
            <button @click="showChapterViewModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <div class="p-8 space-y-5 bg-slate-50/50">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm col-span-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Chapter Title</span>
                    <span class="text-sm font-extrabold text-blue-900 animate-pulse" x-text="viewingChapter?.title"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Chapter Number</span>
                    <span class="text-sm font-black text-indigo-600 font-mono" x-text="`Ch ${viewingChapter?.chapter_number}`"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Subject</span>
                    <span class="text-xs font-bold text-slate-700 truncate block" x-text="viewingChapter?.subject?.name"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm col-span-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Board</span>
                    <span class="text-xs font-bold text-slate-500" x-text="viewingChapter?.board?.name"></span>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button @click="showChapterViewModal = false" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95">Close Details</button>
            </div>
        </div>
    </div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div x-show="showChapterModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden" @click.away="showChapterModal = false">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-750 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-bookmark"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none" x-text="chapterForm.id ? 'Edit Chapter Details' : 'Register Chapter'"></h3>
                    <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mt-1" x-text="chapterForm.id ? 'CHAPTER ID: #' + chapterForm.id : 'NEW PROFILE ENTRY'"></p>
                </div>
            </div>
            <button @click="showChapterModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <form @submit.prevent="saveChapter()" class="p-8 space-y-5 bg-slate-50/50" data-no-pjax>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Board</label>
                    <select x-model="chapterForm.board_id" required class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subject</label>
                    <select x-model="chapterForm.subject_id" required class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                        <option value="">Select Subject</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1 col-span-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Chapter Number</label>
                    <input type="number" x-model="chapterForm.chapter_number" required
                           class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                </div>
                <div class="space-y-1 col-span-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Chapter Title</label>
                    <input type="text" x-model="chapterForm.title" required placeholder="E.g., Matrices & Determinants"
                           class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                </div>
            </div>
            <template x-if="chapterError">
                <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400 animate-pulse" x-text="chapterError"></div>
            </template>
            <div class="flex justify-between items-center pt-4 border-t border-slate-200/50">
                <button type="button" @click="showChapterModal = false"
                        class="px-6 py-2.5 border border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95">
                    Save Parameters
                </button>
            </div>
        </form>
    </div>
</div>
