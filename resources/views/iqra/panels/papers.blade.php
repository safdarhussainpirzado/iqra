{{-- Paper Generator Panel --}}
<div x-show="currentView === 'papers'" class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Criteria Form --}}
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl h-fit">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                    <i class="fas fa-file-alt text-indigo-400 text-xs"></i>
                </div>
                <h3 class="text-sm font-bold text-indigo-400">Paper Criteria</h3>
            </div>
            <form @submit.prevent="generatePaper()" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Exam Title</label>
                    <input type="text" x-model="paperForm.title" required placeholder="E.g., Class 9 Midterm Exam"
                           class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Board</label>
                    <select x-model="paperForm.board_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Class</label>
                    <select x-model="paperForm.class_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                        <option value="">Select Class</option>
                        <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Subject</label>
                    <select x-model="paperForm.subject_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                        <option value="">Select Subject</option>
                        <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">
                        Chapters <span class="text-slate-600">(Ctrl+click to multi-select)</span>
                    </label>
                    <select x-model="paperForm.chapter_ids" multiple required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs h-24">
                        <template x-for="ch in chapters" :key="ch.id"><option :value="ch.id" x-text="ch.title"></option></template>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Difficulty</label>
                        <select x-model="paperForm.difficulty" class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                            <option value="All">All</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Total Marks</label>
                        <input type="number" x-model="paperForm.total_marks" required
                               class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                    </div>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center justify-center gap-2">
                    <i class="fas fa-wand-magic-sparkles"></i> Generate Exam Paper
                </button>
            </form>
        </div>

        {{-- Right: Generated Paper Preview --}}
        <div class="lg:col-span-2 space-y-6">
            <template x-if="generatedPaper">
                <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-8 rounded-2xl shadow-xl space-y-8" id="paper-print-section">
                    <div class="text-center border-b border-slate-800 pb-4 space-y-1">
                        <h2 class="text-xl font-bold tracking-wider" x-text="generatedPaper.paper_structure_json.criteria.title"></h2>
                        <p class="text-xs text-slate-400">Time Allowed: 3 Hours | Total Marks:
                            <span x-text="generatedPaper.paper_structure_json.total_marks_reached"></span>
                        </p>
                    </div>
                    <div class="space-y-6">
                        <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium" x-text="`${index + 1}. ${q.question_text}`"></span>
                                    <span class="text-slate-400 text-xs flex-shrink-0 ml-4" x-text="`[${q.marks} Marks]`"></span>
                                </div>
                                <template x-if="q.type === 'MCQ'">
                                    <div class="grid grid-cols-2 gap-2 pl-4 text-xs text-slate-300">
                                        <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                            <span x-text="`${String.fromCharCode(65 + oIndex)}) ${opt.option_text}`"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    {{-- Answer Key --}}
                    <div class="border-t border-slate-800 pt-6 space-y-4">
                        <h3 class="text-md font-bold text-emerald-400">Answer Key</h3>
                        <div class="space-y-1 text-xs text-slate-300">
                            <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                                <div>
                                    <span x-text="`${index + 1}. `"></span>
                                    <template x-if="q.type === 'MCQ'">
                                        <span class="font-semibold text-emerald-400" x-text="`Correct: ${getCorrectOptionLabel(q.options)}`"></span>
                                    </template>
                                    <template x-if="q.type !== 'MCQ'">
                                        <span class="text-slate-500">[Evaluated based on rubric]</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!generatedPaper">
                <div class="h-64 border border-dashed border-slate-800 rounded-2xl flex flex-col items-center justify-center text-slate-500 gap-3">
                    <i class="fas fa-file-alt text-3xl"></i>
                    <p class="text-sm">Configure paper criteria on the left to generate your exam paper.</p>
                </div>
            </template>
        </div>
    </div>
</div>
