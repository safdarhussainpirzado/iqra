{{-- Paper Generator Panel — Premium ZIWO Light Theme --}}
<div x-show="currentView === 'papers'" class="space-y-6" x-transition>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left: Criteria Form --}}
        <div class="bg-white rounded-[2rem] border border-slate-150 p-6 shadow-[0_10px_40px_rgba(0,0,0,0.02)] h-fit">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                    <i class="fas fa-file-invoice text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-blue-900 uppercase tracking-wider">Paper Criteria</h3>
                    <p class="text-[9px] text-slate-400 font-bold mt-0.5">Parameters for generating exam sheets</p>
                </div>
            </div>
            
            <form @submit.prevent="generatePaper()" class="space-y-4" data-no-pjax>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Exam Title</label>
                    <input type="text" x-model="paperForm.title" required placeholder="E.g., Class 9 Midterm Exam"
                           class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Board</label>
                    <select x-model="paperForm.board_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Class</label>
                    <select x-model="paperForm.class_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Class</option>
                        <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subject</label>
                    <select x-model="paperForm.subject_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Subject</option>
                        <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">
                        Chapters <span class="text-slate-400 font-bold">(Ctrl+click multi-select)</span>
                    </label>
                    <select x-model="paperForm.chapter_ids" multiple required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs h-24 outline-none shadow-sm">
                        <template x-for="ch in chapters" :key="ch.id"><option :value="ch.id" x-text="ch.title"></option></template>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Difficulty</label>
                        <select x-model="paperForm.difficulty" class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                            <option value="All">All</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Marks</label>
                        <input type="number" x-model="paperForm.total_marks" required
                               class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                    </div>
                </div>
                
                <button type="submit"
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-wand-magic-sparkles"></i> Generate Exam Paper
                </button>
            </form>
        </div>

        {{-- Right: Generated Paper Preview --}}
        <div class="lg:col-span-2 space-y-6">
            <template x-if="generatedPaper">
                <div class="bg-white rounded-[2rem] border border-slate-150 p-8 shadow-[0_10px_40px_rgba(0,0,0,0.02)] space-y-8" id="paper-print-section">
                    <div class="text-center border-b border-slate-100 pb-5 space-y-2">
                        <h2 class="text-xl font-black text-blue-900 tracking-wider" x-text="generatedPaper.paper_structure_json.criteria.title"></h2>
                        <p class="text-xs font-bold text-slate-400">Time Allowed: 3 Hours | Total Marks:
                            <span class="text-blue-600 font-black" x-text="generatedPaper.paper_structure_json.total_marks_reached"></span>
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="font-bold text-slate-800" x-text="`${index + 1}. ${q.question_text}`"></span>
                                    <span class="text-slate-400 font-mono text-xs flex-shrink-0 ml-4 font-bold" x-text="`[${q.marks} Marks]`"></span>
                                </div>
                                <template x-if="q.type === 'MCQ'">
                                    <div class="grid grid-cols-2 gap-2 pl-4 text-xs font-bold text-slate-500">
                                        <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                            <span x-text="`${String.fromCharCode(65 + oIndex)}) ${opt.option_text}`"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Answer Key --}}
                    <div class="border-t border-slate-100 pt-6 space-y-4">
                        <h3 class="text-sm font-extrabold text-emerald-600 uppercase tracking-wider">Answer Key</h3>
                        <div class="space-y-2 text-xs text-slate-600 font-bold">
                            <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                                <div>
                                    <span x-text="`${index + 1}. `"></span>
                                    <template x-if="q.type === 'MCQ'">
                                        <span class="font-black text-emerald-600" x-text="`Correct: ${getCorrectOptionLabel(q.options)}`"></span>
                                    </template>
                                    <template x-if="q.type !== 'MCQ'">
                                        <span class="text-slate-400 font-mono">[Evaluated based on rubric]</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            
            <template x-if="!generatedPaper">
                <div class="h-64 border-2 border-dashed border-slate-200 bg-white rounded-[2rem] flex flex-col items-center justify-center text-slate-450 gap-3 shadow-[0_10px_40px_rgba(0,0,0,0.01)]">
                    <i class="fas fa-file-alt text-3xl text-slate-300"></i>
                    <p class="text-xs font-bold">Configure paper criteria on the left to generate your exam paper.</p>
                </div>
            </template>
        </div>
        
    </div>
</div>
