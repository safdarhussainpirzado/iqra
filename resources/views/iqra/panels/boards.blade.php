{{-- Boards Panel --}}
<div x-show="currentView === 'boards'" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">Examination Boards</h3>
            <p class="text-xs text-slate-500 mt-0.5"
               x-text="`${boards.filter(b => b.name.toLowerCase().includes(boardSearch.toLowerCase())).length} boards registered`"></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="boardSearch" placeholder="Search boards…"
                       class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-48 placeholder:text-slate-500">
            </div>
            <button @click="boardForm = {id:null,name:'',code:''}; showBoardModal = true"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition duration-150 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Board
            </button>
        </div>
    </div>

    {{-- Board Modal (Bento style) --}}
    <div x-show="showBoardModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 px-6 py-5">
                <h3 class="text-lg font-bold text-white" x-text="boardForm.id ? 'Edit Board' : 'Add New Board'"></h3>
                <p class="text-indigo-200 text-xs mt-1">Configure examination board details below.</p>
            </div>
            <form @submit.prevent="saveBoard()" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Board Name</label>
                    <input type="text" x-model="boardForm.name" required placeholder="E.g., Punjab Board of Secondary Education"
                           class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Board Code</label>
                    <input type="text" x-model="boardForm.code" required placeholder="E.g., PBSE"
                           class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                    <button type="button" @click="showBoardModal = false"
                            class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Board
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Boards Table --}}
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Board Name</th>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="board in boards.filter(b => b.name.toLowerCase().includes(boardSearch.toLowerCase()))" :key="board.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition">
                        <td class="px-6 py-3.5 text-xs text-slate-500" x-text="board.id"></td>
                        <td class="px-6 py-3.5 font-semibold" x-text="board.name"></td>
                        <td class="px-6 py-3.5">
                            <span class="px-2.5 py-1 bg-indigo-500/15 text-indigo-300 text-xs rounded-lg font-mono font-bold" x-text="board.code"></span>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center justify-center">
                                <div class="inline-grid grid-cols-2 gap-1.5">
                                    <button @click="editBoard(board)" title="Edit"
                                            class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition">
                                        <i class="fas fa-pencil"></i>
                                    </button>
                                    <button @click="confirmDeleteItem(board, 'boards', 'boards', 'name')" title="Delete"
                                            class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="boards.length === 0">
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">No boards configured yet. Click "Add Board" to get started.</td></tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
