{{-- Question Bank Panel --}}
<div x-show="currentView === 'questions'" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">Question Bank</h3>
            <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredQuestions().length} questions`"></p>
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-end">
            <select x-model="qFilterBoard" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Boards</option>
                <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
            </select>
            <select x-model="qFilterSubject" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Subjects</option>
                <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
            </select>
            <select x-model="qFilterType" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Types</option>
                <option value="MCQ">MCQ</option>
                <option value="Short">Short</option>
                <option value="Long">Long</option>
            </select>
            <select x-model="qFilterDifficulty" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">All Difficulty</option>
                <option value="Easy">Easy</option>
                <option value="Medium">Medium</option>
                <option value="Hard">Hard</option>
            </select>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="qSearch" placeholder="Search questions…"
                       class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-40 placeholder:text-slate-500">
            </div>
            <button @click="openQuestionCreateModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>
    </div>

    {{-- Question Modal (Bento - emerald) --}}
    <div x-show="showQuestionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden my-4">
            <div class="bg-gradient-to-br from-emerald-700 to-emerald-900 px-6 py-5">
                <h3 class="text-lg font-bold text-white" x-text="questionForm.id ? 'Edit Question' : 'Add New Question'"></h3>
                <p class="text-emerald-200 text-xs mt-1">Fill all classification fields and question text below.</p>
            </div>
            <form @submit.prevent="saveQuestion()" class="p-6 space-y-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Board</label>
                        <select x-model="questionForm.board_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="">Board</option>
                            <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Class</label>
                        <select x-model="questionForm.class_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="">Class</option>
                            <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Subject</label>
                        <select x-model="questionForm.subject_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="">Subject</option>
                            <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Chapter</label>
                        <select x-model="questionForm.chapter_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="">Chapter</option>
                            <template x-for="ch in chapters" :key="ch.id"><option :value="ch.id" x-text="ch.title"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Type</label>
                        <select x-model="questionForm.type" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="MCQ">MCQ</option>
                            <option value="Short">Short Question</option>
                            <option value="Long">Long Question</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Difficulty</label>
                        <select x-model="questionForm.difficulty" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Language</label>
                        <select x-model="questionForm.language" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                            <option value="English">English</option>
                            <option value="Urdu">Urdu</option>
                            <option value="Sindhi">Sindhi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Marks</label>
                        <input type="number" x-model="questionForm.marks" required min="1"
                               class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Question Text</label>
                    <textarea x-model="questionForm.question_text" required rows="3"
                              class="block w-full rounded-xl border border-slate-700 p-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                </div>
                {{-- MCQ Options --}}
                <div x-show="questionForm.type === 'MCQ'" class="space-y-2 p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                    <label class="block text-xs font-semibold text-slate-400">MCQ Options — select the correct one</label>
                    <template x-for="(opt, index) in questionForm.options" :key="index">
                        <div class="flex items-center gap-3">
                            <input type="radio" :name="'mcq_correct'" :checked="opt.is_correct" @change="setMcqCorrect(index)"
                                   class="h-4 w-4 bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs text-slate-500 font-mono w-5" x-text="['A','B','C','D'][index]"></span>
                            <input type="text" x-model="opt.option_text" :placeholder="`Option ${['A','B','C','D'][index]}`"
                                   class="flex-1 rounded-xl border-0 bg-slate-700 py-1.5 px-3 text-slate-100 text-xs focus:ring-1 focus:ring-indigo-500">
                            <span x-show="opt.is_correct" class="text-[10px] text-emerald-400 font-black">✓ Correct</span>
                        </div>
                    </template>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                    <button type="button" @click="showQuestionModal = false"
                            class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                    <button type="submit"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-xs font-semibold text-white rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Questions Table --}}
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                <tr>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Question</th>
                    <th class="px-6 py-4">Classification</th>
                    <th class="px-6 py-4">Difficulty</th>
                    <th class="px-6 py-4">Marks</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="q in filteredQuestions()" :key="q.id">
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition">
                        <td class="px-6 py-3.5">
                            <span :class="q.type === 'MCQ' ? 'bg-indigo-500/20 text-indigo-300' : q.type === 'Short' ? 'bg-purple-500/20 text-purple-300' : 'bg-orange-500/20 text-orange-300'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" x-text="q.type"></span>
                        </td>
                        <td class="px-6 py-3.5 text-xs max-w-xs"
                            x-text="q.question_text.length > 80 ? q.question_text.substring(0,80) + '…' : q.question_text"></td>
                        <td class="px-6 py-3.5 text-[10px] text-slate-400"
                            x-text="`${q.board?.code || '—'} / Cl.${q.class_id} / ${q.subject?.name || '—'}`"></td>
                        <td class="px-6 py-3.5">
                            <span :class="q.difficulty === 'Easy' ? 'bg-emerald-500/20 text-emerald-300' : q.difficulty === 'Medium' ? 'bg-amber-500/20 text-amber-300' : 'bg-rose-500/20 text-rose-300'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-black" x-text="q.difficulty"></span>
                        </td>
                        <td class="px-6 py-3.5 text-xs font-mono text-slate-300" x-text="q.marks"></td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center justify-center">
                                <div class="inline-grid grid-cols-2 gap-1.5">
                                    <button @click="editQuestion(q)" title="Edit"
                                            class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition">
                                        <i class="fas fa-pencil"></i>
                                    </button>
                                    <button @click="confirmDeleteQuestion(q)" title="Delete"
                                            class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="filteredQuestions().length === 0">
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500 text-sm">No questions found. Try adjusting filters or add questions.</td></tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
