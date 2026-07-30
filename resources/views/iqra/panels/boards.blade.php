{{-- ═══════════════════════════════════════════════════════════════════
     BOARDS PANEL — Premium UI with stats, grid/list, filter, modals
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'boards'" class="flex gap-6 min-h-0">

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-building-columns text-indigo-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-indigo-400" x-text="boards.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Boards</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle-check text-emerald-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-emerald-400" x-text="boards.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Active</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-book-open text-purple-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-purple-400" x-text="subjects.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Subjects Linked</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bookmark text-pink-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-pink-400" x-text="chapters.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Chapters</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-3.5 flex items-center gap-3 shadow-lg">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-building-columns text-indigo-400 text-sm"></i>
                <span class="font-bold text-slate-100">Examination Boards</span>
                <span class="ml-1 px-2 py-0.5 bg-indigo-500/20 text-indigo-300 text-[10px] font-black rounded-full"
                      x-text="boardsFiltered().length + ' records'"></span>
            </div>
            {{-- Search --}}
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="boardSearch" placeholder="Search boards…"
                       class="pl-8 pr-3 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
            {{-- View toggle --}}
            <div class="flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700">
                <button @click="boardView = 'table'"
                        :class="boardView === 'table' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-list"></i> List
                </button>
                <button @click="boardView = 'grid'"
                        :class="boardView === 'grid' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-grid-2"></i> Grid
                </button>
            </div>
            <button @click="boardForm = {id:null,name:'',code:''}; showBoardModal = true"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Board
            </button>
        </div>

        {{-- ── TABLE VIEW ───────────────────────────────────────────── --}}
        <div x-show="boardView === 'table'" class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-800/80 text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-black">Board</th>
                        <th class="px-6 py-4 font-black">Code</th>
                        <th class="px-6 py-4 font-black">Subjects</th>
                        <th class="px-6 py-4 font-black">Added</th>
                        <th class="px-6 py-4 text-center font-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="board in boardsPaged()" :key="board.id">
                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/40 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black flex-shrink-0"
                                         :style="`background: hsl(${(board.id * 47) % 360}, 60%, 25%); color: hsl(${(board.id * 47) % 360}, 70%, 75%);`"
                                         x-text="board.name.charAt(0)"></div>
                                    <div>
                                        <div class="font-semibold text-slate-100 text-sm" x-text="board.name"></div>
                                        <div class="text-[10px] text-slate-500">ID #<span x-text="board.id"></span></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 bg-indigo-500/15 text-indigo-300 text-[11px] rounded-lg font-black font-mono tracking-wider border border-indigo-500/20"
                                      x-text="board.code"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-400"
                                      x-text="subjects.filter(s => s.board_id == board.id).length + ' subjects'"></span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500"
                                x-text="new Date(board.created_at).toLocaleDateString('en-PK',{day:'2-digit',month:'short',year:'numeric'})"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <div class="inline-grid grid-cols-3 gap-1.5">
                                        <button @click="viewBoard(board)" title="Inspect"
                                                class="w-9 h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="editBoard(board)" title="Edit"
                                                class="w-9 h-9 rounded-xl bg-indigo-600 border border-indigo-700 text-white flex items-center justify-center text-xs hover:bg-indigo-500 active:scale-95 transition shadow-sm">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <button @click="confirmDeleteItem(board,'boards','boards','name')" title="Delete"
                                                class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition shadow-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="boardsFiltered().length === 0">
                        <tr><td colspan="5" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-building-columns text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm">No boards found. Click <strong class="text-indigo-400">Add Board</strong> to create one.</p>
                            </div>
                        </td></tr>
                    </template>
                </tbody>
            </table>
            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-between bg-slate-800/40">
                <span class="text-xs text-slate-500"
                      x-text="`Showing ${Math.min((boardPage-1)*boardPerPage+1, boardsFiltered().length)}–${Math.min(boardPage*boardPerPage, boardsFiltered().length)} of ${boardsFiltered().length}`"></span>
                <div class="flex items-center gap-1">
                    <button @click="boardPage = Math.max(1, boardPage - 1)" :disabled="boardPage === 1"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </button>
                    <template x-for="p in Math.ceil(boardsFiltered().length / boardPerPage)" :key="p">
                        <button @click="boardPage = p"
                                :class="boardPage === p ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="w-8 h-8 rounded-lg text-xs font-bold transition" x-text="p"></button>
                    </template>
                    <button @click="boardPage = Math.min(Math.ceil(boardsFiltered().length/boardPerPage), boardPage + 1)"
                            :disabled="boardPage >= Math.ceil(boardsFiltered().length / boardPerPage)"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── GRID VIEW ────────────────────────────────────────────── --}}
        <div x-show="boardView === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <template x-for="board in boardsPaged()" :key="board.id">
                <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-indigo-500/40 transition group">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-black flex-shrink-0"
                             :style="`background: hsl(${(board.id * 47) % 360}, 60%, 20%); color: hsl(${(board.id * 47) % 360}, 70%, 70%);`"
                             x-text="board.name.charAt(0)"></div>
                        <div class="min-w-0">
                            <div class="font-bold text-slate-100 text-sm truncate" x-text="board.name"></div>
                            <span class="px-2 py-0.5 bg-indigo-500/15 text-indigo-300 text-[10px] font-black font-mono rounded" x-text="board.code"></span>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mb-4"
                         x-text="subjects.filter(s => s.board_id == board.id).length + ' subjects · ' + chapters.filter(c => c.board_id == board.id).length + ' chapters'"></div>
                    <div class="inline-grid grid-cols-3 gap-1.5 w-full">
                        <button @click="viewBoard(board)" title="Inspect"
                                class="h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button @click="editBoard(board)" title="Edit"
                                class="h-9 rounded-xl bg-indigo-600 border border-indigo-700 text-white flex items-center justify-center text-xs hover:bg-indigo-500 active:scale-95 transition">
                            <i class="fas fa-pencil"></i>
                        </button>
                        <button @click="confirmDeleteItem(board,'boards','boards','name')" title="Delete"
                                class="h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ── Sticky Filter Sidebar ─────────────────────────────────────── --}}
    <div class="w-56 flex-shrink-0 space-y-4 sticky top-20 self-start">
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-4 shadow-xl">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-sliders text-indigo-400 text-xs"></i>
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Filters</span>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Records Per Page</label>
                    <div class="grid grid-cols-4 gap-1">
                        <template x-for="n in [5,10,20,50]" :key="n">
                            <button @click="boardPerPage = n; boardPage = 1"
                                    :class="boardPerPage === n ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                                    class="py-1.5 rounded-lg text-[10px] font-bold transition" x-text="n"></button>
                        </template>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-800">
                    <button @click="boardSearch = ''; boardPage = 1"
                            class="w-full py-2 bg-rose-600/20 hover:bg-rose-600/30 border border-rose-600/30 text-rose-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-rotate-left text-xs"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════ VIEW MODAL ═══════════ --}}
<div x-show="showBoardViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-800 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-building-columns text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wide" x-text="viewingBoard?.name"></h3>
                    <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Board Profile</p>
                </div>
            </div>
            <button @click="showBoardViewModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Board Name</div>
                    <div class="text-sm font-bold text-slate-100" x-text="viewingBoard?.name"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Board Code</div>
                    <div class="text-sm font-black text-indigo-400 font-mono" x-text="viewingBoard?.code"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Subjects</div>
                    <div class="text-sm font-bold text-purple-400"
                         x-text="subjects.filter(s => s.board_id == viewingBoard?.id).length + ' linked'"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Chapters</div>
                    <div class="text-sm font-bold text-pink-400"
                         x-text="chapters.filter(c => c.board_id == viewingBoard?.id).length + ' indexed'"></div>
                </div>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                <button @click="showBoardViewModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Close
                </button>
                <button @click="showBoardViewModal = false; editBoard(viewingBoard)"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-xl transition uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-pencil"></i> Edit Board
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════ CREATE / EDIT MODAL ═══════════ --}}
<div x-show="showBoardModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-700 via-indigo-800 to-purple-900 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-building-columns text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white" x-text="boardForm.id ? 'Modify Board' : 'Register Board'"></h3>
                    <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest"
                       x-text="boardForm.id ? 'BOARD ID: ' + boardForm.id : 'NEW RECORD'"></p>
                </div>
            </div>
            <button @click="showBoardModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <form @submit.prevent="saveBoard()" class="p-6 space-y-5">
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Board Name</label>
                <input type="text" x-model="boardForm.name" required placeholder="E.g., Punjab Board of Secondary Education"
                       class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2.5 px-4 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Board Code</label>
                <input type="text" x-model="boardForm.code" required placeholder="E.g., PBSE"
                       class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2.5 px-4 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm font-mono uppercase">
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                <button type="button" @click="showBoardModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Abort
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-xs font-black text-white rounded-xl transition shadow-lg uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-floppy-disk"></i>
                    <span x-text="boardForm.id ? 'Update Record' : 'Register Board'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
