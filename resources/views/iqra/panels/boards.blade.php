{{-- ═══════════════════════════════════════════════════════════════════
     BOARDS PANEL — Premium ZIWO Light Theme
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'boards'" class="space-y-6" x-transition>

    {{-- Stats Row matching ZIWO exactly with absolute top offset icons --}}
    <div class="grid grid-cols-4 gap-6 pt-6">
        <div class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-indigo-500 shadow-[0_8px_16px_rgba(99,102,241,0.2)] text-white">
                <i class="fas fa-building-columns text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Boards</span>
                <span class="text-3xl font-black text-slate-800" x-text="boards.length"></span>
            </div>
        </div>
        <div class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-emerald-500 shadow-[0_8px_16px_rgba(16,185,129,0.2)] text-white">
                <i class="fas fa-circle-check text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Active Boards</span>
                <span class="text-3xl font-black text-slate-800" x-text="boards.length"></span>
            </div>
        </div>
        <div class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-purple-550 shadow-[0_8px_16px_rgba(147,51,234,0.2)] text-white">
                <i class="fas fa-book-open text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subjects Linked</span>
                <span class="text-3xl font-black text-slate-800" x-text="subjects.length"></span>
            </div>
        </div>
        <div class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-pink-500 shadow-[0_8px_16px_rgba(244,63,94,0.2)] text-white">
                <i class="fas fa-bookmark text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Chapters</span>
                <span class="text-3xl font-black text-slate-800" x-text="chapters.length"></span>
            </div>
        </div>
    </div>

    {{-- Main Control Wrapper --}}
    <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Panel Header --}}
        <div class="bg-blue-50/40 px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center">
                    <i class="fas fa-building-columns text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-blue-900 tracking-tight flex items-center gap-2">
                        Examination Boards <span class="text-sm font-bold text-slate-400" x-text="'(' + boardsFiltered().length + ' records)'"></span>
                    </h2>
                    <p class="text-slate-500 text-xs font-bold mt-0.5">Manage board structures and code mappings</p>
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
                    <button @click="boardView = 'table'" :class="boardView === 'table' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-blue-600'" class="w-9 h-9 flex items-center justify-center rounded-lg transition-all"><i class="fas fa-list-ul"></i></button>
                    <button @click="boardView = 'grid'" :class="boardView === 'grid' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-blue-600'" class="w-9 h-9 flex items-center justify-center rounded-lg transition-all"><i class="fas fa-th-large"></i></button>
                </div>

                <button @click="boardForm = {id:null,name:'',code:''}; showBoardModal = true" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all active:scale-95">
                    <i class="fas fa-plus"></i> Add Board
                </button>

                {{-- ZIWO Funnel Filter Toggle --}}
                <button @click="showFilters = !showFilters" :class="showFilters ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-400 hover:text-blue-600'" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        {{-- Main Table Area with Collapsible Sidebar --}}
        <div class="flex gap-0 min-h-0 relative">
            
            {{-- Left: Tabular content --}}
            <div class="flex-1 min-w-0">
                <div x-show="boardView === 'table'" class="overflow-x-auto">
                    <table class="w-full text-left" :class="density === 'condensed' ? 'condensed-table' : 'spacious-table'">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 shadow-sm"><i class="fas fa-tag text-[9px]"></i></div>
                                        <span>Board Identity</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 shadow-sm"><i class="fas fa-code text-[9px]"></i></div>
                                        <span>Board Code</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 shadow-sm"><i class="fas fa-book-open text-[9px]"></i></div>
                                        <span>Linked Subjects</span>
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
                            <template x-for="board in boardsPaged()" :key="board.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-900 p-0.5 flex items-center justify-center shadow-sm">
                                                <div class="w-full h-full rounded-[10px] bg-white flex items-center justify-center text-blue-900 font-extrabold text-sm uppercase" x-text="board.name.substring(0,2)"></div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm" x-text="board.name"></div>
                                                <div class="text-[10px] text-slate-400 mt-0.5">ID: #<span x-text="board.id"></span></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg border border-blue-100 font-mono" x-text="board.code"></span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-black text-emerald-600" x-text="subjects.filter(s => s.board_id == board.id).length + ' subjects'"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center">
                                            <div class="inline-grid grid-cols-3 gap-1.5">
                                                <button @click="viewBoard(board)" title="Inspect Data" class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition flex items-center justify-center">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                                <button @click="editBoard(board)" title="Modify Properties" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white hover:bg-indigo-600 active:scale-95 transition flex items-center justify-center">
                                                    <i class="fas fa-sliders text-xs"></i>
                                                </button>
                                                <button @click="confirmDeleteBoard(board)" title="Purge Record" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white hover:bg-rose-700 active:scale-95 transition flex items-center justify-center">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="boardsFiltered().length === 0">
                                <tr>
                                    <td colspan="4" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                                <i class="fas fa-building-columns text-slate-400 text-2xl"></i>
                                            </div>
                                            <p class="text-slate-400 text-sm font-bold">No boards matching the query.</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- Pagination Row from ZIWO --}}
                    <div class="px-8 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-400"
                              x-text="`Showing ${Math.min((boardPage-1)*boardPerPage+1, boardsFiltered().length)}–${Math.min(boardPage*boardPerPage, boardsFiltered().length)} of ${boardsFiltered().length} boards`"></span>
                        <div class="flex items-center gap-1">
                            <button @click="boardPage = Math.max(1, boardPage - 1)" :disabled="boardPage === 1"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </button>
                            <template x-for="p in Math.ceil(boardsFiltered().length / boardPerPage)" :key="p">
                                <button @click="boardPage = p"
                                        :class="boardPage === p ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-black' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                                        class="w-8 h-8 rounded-lg text-xs transition" x-text="p"></button>
                            </template>
                            <button @click="boardPage = Math.min(Math.ceil(boardsFiltered().length/boardPerPage), boardPage + 1)"
                                    :disabled="boardPage >= Math.ceil(boardsFiltered().length / boardPerPage)"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Grid view --}}
                <div x-show="boardView === 'grid'" class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        <template x-for="board in boardsPaged()" :key="board.id">
                            <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-slate-150 p-8 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                                    <i class="fas fa-building-columns text-9xl text-blue-900"></i>
                                </div>
                                <div class="flex items-start justify-between mb-8 relative z-10">
                                    <div class="w-20 h-20 rounded-[1.75rem] bg-blue-900 p-1 flex items-center justify-center shadow-lg group-hover:rotate-6 transition-transform">
                                        <div class="w-full h-full rounded-[1.5rem] bg-white flex items-center justify-center text-blue-900 font-extrabold text-2xl tracking-tight uppercase" x-text="board.name.substring(0,2)"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">State</div>
                                        <span class="flex items-center gap-1.5 text-blue-600 font-black text-[10px] tracking-widest uppercase justify-end">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Active
                                        </span>
                                    </div>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="text-xl font-extrabold text-blue-900 tracking-tight leading-tight mb-2 truncate" x-text="board.name"></h4>
                                    <div class="flex items-center gap-2 mb-6">
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg border border-blue-100 font-mono uppercase" x-text="board.code"></span>
                                    </div>
                                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100/80 mb-8">
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Linked Subjects</span>
                                                <span class="text-xs font-bold text-slate-700" x-text="subjects.filter(s => s.board_id == board.id).length"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-5 border-t border-slate-100 space-y-3">
                                        <div class="grid grid-cols-3 gap-2">
                                            <button @click="viewBoard(board)" title="Inspect Data" class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <button @click="editBoard(board)" title="Modify Parameters" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white hover:bg-indigo-600 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                                <i class="fa-solid fa-sliders text-xs"></i>
                                            </button>
                                            <button @click="confirmDeleteBoard(board)" title="Purge Record" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white hover:bg-rose-700 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                                <i class="fa-solid fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right: Collapsible Sidebar Filter, aligned exactly where table begins --}}
            <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10"
                 class="w-64 flex-shrink-0 border-l border-slate-100 bg-white p-6 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-sliders text-blue-600 text-xs"></i>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Sidebar Filters</span>
                    </div>
                    <button @click="showFilters = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Records Per Page</label>
                        <div class="grid grid-cols-4 gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200/50">
                            <template x-for="n in [5,10,20,50]" :key="n">
                                <button @click="boardPerPage = n; boardPage = 1"
                                        :class="boardPerPage === n ? 'bg-white text-blue-600 shadow-sm font-black' : 'text-slate-500 hover:text-slate-700'"
                                        class="py-1.5 text-[9px] uppercase tracking-widest rounded-lg transition-all" x-text="n"></button>
                            </template>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <button @click="boardSearch = ''; boardPage = 1"
                                class="w-full py-4 text-rose-500 hover:bg-rose-50 rounded-3xl text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 active:scale-95 border border-rose-100">
                            <i class="fas fa-broom"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Board View Modal --}}
<div x-show="showBoardViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden" @click.away="showBoardViewModal = false">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-750 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-building-columns"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none" x-text="viewingBoard?.name"></h3>
                    <p class="text-[9px] font-black text-blue-200 uppercase tracking-widest mt-1">Board profile details</p>
                </div>
            </div>
            <button @click="showBoardViewModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <div class="p-8 space-y-5 bg-slate-50/50">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Board Name</span>
                    <span class="text-sm font-extrabold text-blue-900" x-text="viewingBoard?.name"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Code</span>
                    <span class="text-sm font-black text-blue-600 font-mono" x-text="viewingBoard?.code"></span>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button @click="showBoardViewModal = false" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95">Close Details</button>
            </div>
        </div>
    </div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div x-show="showBoardModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden" @click.away="showBoardModal = false">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-750 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-building-columns"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none" x-text="boardForm.id ? 'Edit Board Details' : 'Register Board'"></h3>
                    <p class="text-[9px] font-black text-blue-200 uppercase tracking-widest mt-1" x-text="boardForm.id ? 'BOARD ID: #' + boardForm.id : 'NEW PROFILE ENTRY'"></p>
                </div>
            </div>
            <button @click="showBoardModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <form @submit.prevent="saveBoard()" class="p-8 space-y-5 bg-slate-50/50" data-no-pjax>
            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Board Code</label>
                    <input type="text" x-model="boardForm.code" required placeholder="E.g., FBISE"
                           class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Board Full Name</label>
                    <input type="text" x-model="boardForm.name" required placeholder="E.g., Federal Board of Intermediate & Secondary Education"
                           class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                </div>
            </div>
            <template x-if="boardError">
                <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400 animate-pulse" x-text="boardError"></div>
            </template>
            <div class="flex justify-between items-center pt-4 border-t border-slate-200/50">
                <button type="button" @click="showBoardModal = false"
                        class="px-6 py-2.5 border border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95">
                    Save Parameters
                </button>
            </div>
        </form>
    </div>
</div>
