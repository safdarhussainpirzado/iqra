{{-- Chapters Panel --}}
<div x-show="currentView === 'chapters'" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">Chapters</h3>
            <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredChapters().length} chapters`"></p>
        </div>
        <div class="flex items-center gap-3">
            <select x-model="chapterFilterBoard" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Boards</option>
                <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
            </select>
            <select x-model="chapterFilterSubject" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Subjects</option>
                <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
            </select>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="chapterSearch" placeholder="Search chapters…"
                       class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
            <button @click="openChapterCreateModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Chapter
            </button>
        </div>
    </div>

    {{-- Chapter Modal (Bento) --}}
    <div x-show="showChapterModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-br from-purple-700 to-purple-900 px-6 py-5">
                <h3 class="text-lg font-bold text-white" x-text="chapterForm.id ? 'Edit Chapter' : 'Add New Chapter'"></h3>
                <p class="text-purple-200 text-xs mt-1">Assign this chapter to its board and subject.</p>
            </div>
            <form @submit.prevent="saveChapter()" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Board</label>
                        <select x-model="chapterForm.board_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="">Select Board</option>
                            <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Subject</label>
                        <select x-model="chapterForm.subject_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="">Select Subject</option>
                            <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Chapter Number</label>
                        <input type="number" x-model="chapterForm.chapter_number" required class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Chapter Title</label>
                        <input type="text" x-model="chapterForm.title" required placeholder="E.g., Number Systems" class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
                <template x-if="chapterError">
                    <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400" x-text="chapterError"></div>
                </template>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                    <button type="button" @click="showChapterModal = false"
                            class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Chapter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Chapters Table --}}
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-4">Ch #</th>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Board</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="ch in filteredChapters()" :key="ch.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition">
                        <td class="px-6 py-3.5 text-xs font-mono text-slate-400" x-text="ch.chapter_number"></td>
                        <td class="px-6 py-3.5 font-semibold" x-text="ch.title"></td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="ch.subject?.name || '—'"></td>
                        <td class="px-6 py-3.5 text-xs text-slate-400" x-text="ch.board?.name || '—'"></td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center justify-center">
                                <div class="inline-grid grid-cols-2 gap-1.5">
                                    <button @click="editChapter(ch)" title="Edit"
                                            class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition">
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
                <template x-if="filteredChapters().length === 0">
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500 text-sm">No chapters found.</td></tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
