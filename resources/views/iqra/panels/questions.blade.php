{{-- ═══════════════════════════════════════════════════════════════════
     QUESTION BANK PANEL — Premium UI
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'questions'" class="flex gap-6 min-h-0">

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle-question text-pink-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-pink-400" x-text="questions.length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Questions</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-list-check text-indigo-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-indigo-400" x-text="questions.filter(q => q.type === 'MCQ').length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">MCQ Count</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gauge-high text-emerald-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-emerald-400" x-text="questions.filter(q => q.difficulty === 'Easy').length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Easy Questions</div>
                </div>
            </div>
            <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-fire text-rose-400"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-rose-400" x-text="questions.filter(q => q.difficulty === 'Hard').length"></div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Hard Questions</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl px-5 py-3.5 flex items-center gap-3 shadow-lg">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-circle-question text-pink-400 text-sm"></i>
                <span class="font-bold text-slate-100">Question Bank</span>
                <span class="ml-1 px-2 py-0.5 bg-pink-500/20 text-pink-300 text-[10px] font-black rounded-full"
                      x-text="questionsFiltered().length + ' records'"></span>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="qSearch" placeholder="Search questions…"
                       class="pl-8 pr-3 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-pink-500 w-44 placeholder:text-slate-500">
            </div>
            {{-- View toggle --}}
            <div class="flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700">
                <button @click="qView = 'table'"
                        :class="qView === 'table' ? 'bg-pink-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-list"></i> List
                </button>
                <button @click="qView = 'grid'"
                        :class="qView === 'grid' ? 'bg-pink-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-grid-2"></i> Grid
                </button>
            </div>
            <button @click="openQuestionCreateModal()"
                    class="px-4 py-2 bg-pink-600 hover:bg-pink-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>

        {{-- TABLE VIEW --}}
        <div x-show="qView === 'table'" class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-800/80 text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-black">Type</th>
                        <th class="px-6 py-4 font-black">Question Content</th>
                        <th class="px-6 py-4 font-black">Classification</th>
                        <th class="px-6 py-4 font-black">Difficulty</th>
                        <th class="px-6 py-4 font-black">Marks</th>
                        <th class="px-6 py-4 text-center font-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="q in questionsPaged()" :key="q.id">
                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4">
                                <span :class="q.type === 'MCQ' ? 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30' : q.type === 'Short' ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-orange-500/20 text-orange-300 border-orange-500/30'"
                                      class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border" x-text="q.type"></span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-200 max-w-sm">
                                <div class="truncate" x-text="q.question_text"></div>
                                <div class="text-[10px] text-slate-500 mt-0.5" x-text="`Language: ${q.language || 'English'}`"></div>
                            </td>
                            <td class="px-6 py-4 text-[10px] text-slate-400"
                                x-text="`${q.board?.code || '—'} / Cl.${q.class_id} / ${q.subject?.name || '—'}`"></td>
                            <td class="px-6 py-4">
                                <span :class="q.difficulty === 'Easy' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : q.difficulty === 'Medium' ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border-rose-500/30'"
                                      class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border" x-text="q.difficulty"></span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono font-black text-slate-300" x-text="q.marks"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <div class="inline-grid grid-cols-3 gap-1.5">
                                        <button @click="viewQuestion(q)" title="Inspect"
                                                class="w-9 h-9 rounded-xl bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="editQuestion(q)" title="Edit"
                                                class="w-9 h-9 rounded-xl bg-indigo-600 border border-indigo-700 text-white flex items-center justify-center text-xs hover:bg-indigo-500 active:scale-95 transition">
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
                    <template x-if="questionsFiltered().length === 0">
                        <tr><td colspan="6" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-circle-question text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm">No questions found in this scope.</p>
                            </div>
                        </td></tr>
                    </template>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-between bg-slate-800/40">
                <span class="text-xs text-slate-500"
                      x-text="`Showing ${Math.min((qPage-1)*qPerPage+1, questionsFiltered().length)}–${Math.min(qPage*qPerPage, questionsFiltered().length)} of ${questionsFiltered().length}`"></span>
                <div class="flex items-center gap-1">
                    <button @click="qPage = Math.max(1, qPage-1)" :disabled="qPage===1"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </button>
                    <template x-for="p in Math.ceil(questionsFiltered().length/qPerPage)" :key="p">
                        <button @click="qPage = p"
                                :class="qPage===p ? 'bg-pink-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="w-8 h-8 rounded-lg text-xs font-bold transition" x-text="p"></button>
                    </template>
                    <button @click="qPage = Math.min(Math.ceil(questionsFiltered().length/qPerPage), qPage+1)"
                            :disabled="qPage >= Math.ceil(questionsFiltered().length/qPerPage)"
                            class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 text-xs flex items-center justify-center hover:bg-slate-600 disabled:opacity-40 transition">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- GRID VIEW --}}
        <div x-show="qView === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="q in questionsPaged()" :key="q.id">
                <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-pink-500/40 transition flex flex-col justify-between min-h-[200px]">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span :class="q.type === 'MCQ' ? 'bg-indigo-500/20 text-indigo-300' : q.type === 'Short' ? 'bg-purple-500/20 text-purple-300' : 'bg-orange-500/20 text-orange-300'"
                                  class="px-2 py-0.5 rounded-full text-[9px] font-black" x-text="q.type"></span>
                            <span :class="q.difficulty === 'Easy' ? 'text-emerald-400' : q.difficulty === 'Medium' ? 'text-amber-400' : 'text-rose-400'"
                                  class="text-[9px] font-black uppercase tracking-wider" x-text="q.difficulty"></span>
                        </div>
                        <p class="text-xs text-slate-200 font-semibold line-clamp-3 leading-relaxed" x-text="q.question_text"></p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between gap-3">
                        <span class="text-[10px] text-slate-500 font-mono" x-text="`Marks: ${q.marks}`"></span>
                        <div class="flex gap-1.5">
                            <button @click="viewQuestion(q)"
                                    class="w-8 h-8 rounded-lg bg-blue-600 border border-blue-700 text-white flex items-center justify-center text-xs hover:bg-blue-500 active:scale-95 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button @click="editQuestion(q)"
                                    class="w-8 h-8 rounded-lg bg-indigo-600 border border-indigo-700 text-white flex items-center justify-center text-xs hover:bg-indigo-500 active:scale-95 transition">
                                <i class="fas fa-pencil"></i>
                            </button>
                            <button @click="confirmDeleteQuestion(q)"
                                    class="w-8 h-8 rounded-lg bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Sticky Filter Sidebar --}}
    <div class="w-56 flex-shrink-0 space-y-4 sticky top-20 self-start">
        <div class="backdrop-blur-md bg-slate-900/70 border border-slate-800 rounded-2xl p-4 shadow-xl">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-sliders text-pink-400 text-xs"></i>
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Filters</span>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Board</label>
                    <select x-model="qFilterBoard" @change="qPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-pink-500">
                        <option value="">All Boards</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Subject</label>
                    <select x-model="qFilterSubject" @change="qPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-pink-500">
                        <option value="">All Subjects</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Type</label>
                    <select x-model="qFilterType" @change="qPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-pink-500">
                        <option value="">All Types</option>
                        <option value="MCQ">MCQ</option>
                        <option value="Short">Short Question</option>
                        <option value="Long">Long Question</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Difficulty</label>
                    <select x-model="qFilterDifficulty" @change="qPage = 1"
                            class="w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-pink-500">
                        <option value="">All Difficulty</option>
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-1.5">Records Per Page</label>
                    <div class="grid grid-cols-4 gap-1">
                        <template x-for="n in [5,10,20,50]" :key="n">
                            <button @click="qPerPage = n; qPage = 1"
                                    :class="qPerPage === n ? 'bg-pink-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                                    class="py-1.5 rounded-lg text-[10px] font-bold transition" x-text="n"></button>
                        </template>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-800">
                    <button @click="qSearch = ''; qFilterBoard = ''; qFilterSubject = ''; qFilterType = ''; qFilterDifficulty = ''; qPage = 1"
                            class="w-full py-2 bg-rose-600/20 hover:bg-rose-600/30 border border-rose-600/30 text-rose-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-rotate-left text-xs"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Question View Modal --}}
<div x-show="showQuestionViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-pink-600 to-indigo-800 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-circle-question text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wide">Question Profile</h3>
                    <p class="text-pink-200 text-[10px] font-bold uppercase tracking-widest" x-text="`Question ID #${viewingQuestion?.id}`"></p>
                </div>
            </div>
            <button @click="showQuestionViewModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-slate-850 border border-slate-800 rounded-xl p-4">
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Question Text</div>
                <p class="text-sm font-semibold text-slate-100 leading-relaxed" x-text="viewingQuestion?.question_text"></p>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Type</div>
                    <div class="text-xs font-bold text-indigo-400 uppercase" x-text="viewingQuestion?.type"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Difficulty</div>
                    <div class="text-xs font-bold text-amber-400 uppercase" x-text="viewingQuestion?.difficulty"></div>
                </div>
                <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-3">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Marks</div>
                    <div class="text-xs font-black text-emerald-400 font-mono" x-text="viewingQuestion?.marks"></div>
                </div>
            </div>
            <template x-if="viewingQuestion?.type === 'MCQ' && viewingQuestion?.options">
                <div class="space-y-2 p-4 bg-slate-800/60 border border-slate-700/50 rounded-xl">
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Multiple Choice Options</div>
                    <div class="grid grid-cols-1 gap-2">
                        <template x-for="(opt, idx) in viewingQuestion.options" :key="idx">
                            <div class="flex items-center justify-between text-xs py-1 px-2.5 rounded-lg bg-slate-900/40 border"
                                 :class="opt.is_correct ? 'border-emerald-500/30 bg-emerald-500/5 text-emerald-300' : 'border-slate-800 text-slate-400'">
                                <span x-text="`${['A','B','C','D'][idx]}. ${opt.option_text}`"></span>
                                <span x-show="opt.is_correct" class="text-[10px] font-black uppercase">Correct</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <div class="flex justify-end pt-2 border-t border-slate-800">
                <button @click="showQuestionViewModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div x-show="showQuestionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden my-4">
        <div class="bg-gradient-to-br from-pink-600 via-pink-700 to-indigo-900 px-6 py-5 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-circle-question text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white" x-text="questionForm.id ? 'Edit Question Record' : 'Register Question'"></h3>
                    <p class="text-pink-200 text-[10px] font-bold uppercase tracking-widest"
                       x-text="questionForm.id ? 'RECORD ID: ' + questionForm.id : 'NEW ENTRY'"></p>
                </div>
            </div>
            <button @click="showQuestionModal = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <form @submit.prevent="saveQuestion()" class="p-6 space-y-4" data-no-pjax>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Board</label>
                    <select x-model="questionForm.board_id" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Class</label>
                    <select x-model="questionForm.class_id" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="">Select Class</option>
                        <template x-for="cls in classesList" :key="cls.id">
                            <option :value="cls.id" x-text="cls.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Subject</label>
                    <select x-model="questionForm.subject_id" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="">Select Subject</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Chapter</label>
                    <select x-model="questionForm.chapter_id" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="">Select Chapter</option>
                        <template x-for="ch in chapters" :key="ch.id">
                            <option :value="ch.id" x-text="ch.title"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Type</label>
                    <select x-model="questionForm.type" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="MCQ">MCQ</option>
                        <option value="Short">Short Question</option>
                        <option value="Long">Long Question</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Difficulty</label>
                    <select x-model="questionForm.difficulty" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Language</label>
                    <select x-model="questionForm.language" required class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                        <option value="English">English</option>
                        <option value="Urdu">Urdu</option>
                        <option value="Sindhi">Sindhi</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Marks</label>
                    <input type="number" x-model="questionForm.marks" required min="1"
                           class="block w-full rounded-xl border-0 bg-slate-800 border border-slate-700 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-xs">
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Question Text</label>
                <textarea x-model="questionForm.question_text" required rows="3" placeholder="Write question context here..."
                          class="block w-full rounded-xl border border-slate-700 bg-slate-800 p-3 text-slate-100 focus:ring-2 focus:ring-pink-500 text-sm"></textarea>
            </div>
            {{-- MCQ Options Options --}}
            <div x-show="questionForm.type === 'MCQ'" class="space-y-2 p-4 bg-slate-800/60 rounded-xl border border-slate-700/50">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">Options Matrix</label>
                <template x-for="(opt, idx) in questionForm.options" :key="idx">
                    <div class="flex items-center gap-3">
                        <input type="radio" :name="'mcq_correct'" :checked="opt.is_correct" @change="setMcqCorrect(idx)"
                               class="h-4 w-4 bg-slate-800 border-slate-700 text-pink-600 focus:ring-pink-500">
                        <span class="text-xs text-slate-500 font-mono w-5" x-text="['A','B','C','D'][idx]"></span>
                        <input type="text" x-model="opt.option_text" :placeholder="`Option ${['A','B','C','D'][idx]} text`"
                               class="flex-1 rounded-xl border-0 bg-slate-700 py-1.5 px-3 text-slate-100 text-xs focus:ring-1 focus:ring-pink-500">
                        <span x-show="opt.is_correct" class="text-[10px] text-emerald-400 font-black">✓ CORRECT</span>
                    </div>
                </template>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                <button type="button" @click="showQuestionModal = false"
                        class="px-5 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-700 rounded-xl transition uppercase tracking-widest">
                    Abort
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-pink-600 to-indigo-600 hover:from-pink-500 hover:to-indigo-500 text-xs font-black text-white rounded-xl transition shadow-lg uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-floppy-disk"></i>
                    <span>Save Question</span>
                </button>
            </div>
        </form>
    </div>
</div>
