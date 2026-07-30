{{-- ═══════════════════════════════════════════════════════════════════
     QUESTION BANK PANEL — Premium ZIWO Light Theme
     ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="currentView === 'questions'" class="space-y-6" x-transition>

    {{-- Stats Row matching ZIWO exactly with absolute top offset icons and status filters --}}
    <div class="grid grid-cols-4 gap-6 pt-6">
        <div @click="qFilterType = ''; qFilterDifficulty = ''; qPage = 1"
             :class="(qFilterType === '' && qFilterDifficulty === '') ? 'card-3d-active pink' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-pink-500 shadow-[0_8px_16px_rgba(244,63,94,0.2)] text-white">
                <i class="fas fa-circle-question text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Questions</span>
                <span class="text-3xl font-black text-slate-800" x-text="questions.length"></span>
            </div>
        </div>
        <div @click="qFilterType = 'MCQ'; qFilterDifficulty = ''; qPage = 1"
             :class="qFilterType === 'MCQ' ? 'card-3d-active indigo' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-indigo-500 shadow-[0_8px_16px_rgba(99,102,241,0.2)] text-white">
                <i class="fas fa-list-check text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">MCQ Count</span>
                <span class="text-3xl font-black text-slate-800" x-text="questions.filter(q => q.type === 'MCQ').length"></span>
            </div>
        </div>
        <div @click="qFilterDifficulty = 'Easy'; qFilterType = ''; qPage = 1"
             :class="qFilterDifficulty === 'Easy' ? 'card-3d-active emerald' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-emerald-500 shadow-[0_8px_16px_rgba(16,185,129,0.2)] text-white">
                <i class="fas fa-gauge-high text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Easy Level</span>
                <span class="text-3xl font-black text-slate-800" x-text="questions.filter(q => q.difficulty === 'Easy').length"></span>
            </div>
        </div>
        <div @click="qFilterDifficulty = 'Hard'; qFilterType = ''; qPage = 1"
             :class="qFilterDifficulty === 'Hard' ? 'card-3d-active rose' : ''"
             class="relative flex flex-col bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 hover:-translate-y-1 transition-all duration-300 p-5 text-right pt-5 cursor-pointer">
            <div class="absolute -top-4 left-4 h-10 w-10 flex items-center justify-center rounded-xl bg-rose-500 shadow-[0_8px_16px_rgba(225,29,72,0.2)] text-white">
                <i class="fas fa-fire text-xs"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Hard Level</span>
                <span class="text-3xl font-black text-slate-800" x-text="questions.filter(q => q.difficulty === 'Hard').length"></span>
            </div>
        </div>
    </div>

    {{-- Main Control Wrapper --}}
    <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Panel Header --}}
        <div class="bg-blue-50/40 px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center">
                    <i class="fas fa-circle-question text-2xl text-pink-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-blue-900 tracking-tight flex items-center gap-2">
                        Question Bank <span class="text-sm font-bold text-slate-400" x-text="'(' + questionsFiltered().length + ' records)'"></span>
                    </h2>
                    <p class="text-slate-500 text-xs font-bold mt-0.5">Manage evaluation items, marks, and language settings</p>
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
                    <button @click="qView = 'table'" :class="qView === 'table' ? 'bg-pink-600 text-white shadow-md' : 'text-slate-400 hover:text-pink-600'" class="w-9 h-9 flex items-center justify-center rounded-lg transition-all"><i class="fas fa-list-ul"></i></button>
                    <button @click="qView = 'grid'" :class="qView === 'grid' ? 'bg-pink-600 text-white shadow-md' : 'text-slate-400 hover:text-pink-600'" class="w-9 h-9 flex items-center justify-center rounded-lg transition-all"><i class="fas fa-th-large"></i></button>
                </div>

                <button @click="openQuestionCreateModal()" class="flex items-center gap-2 px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-black shadow-[0_8px_20px_rgba(219,39,119,0.25)] transition-all active:scale-95">
                    <i class="fas fa-plus"></i> Add Question
                </button>

                {{-- ZIWO Funnel Filter Toggle --}}
                <button @click="showFilters = !showFilters" :class="showFilters ? 'bg-pink-600 text-white shadow-md' : 'bg-white text-slate-400 hover:text-pink-600'" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        {{-- Table Area with Collapsible Sidebar Filters --}}
        <div class="flex gap-0 min-h-0 relative">
            
            <div class="flex-1 min-w-0 border-r border-slate-100">
                {{-- TABLE VIEW --}}
                <div x-show="qView === 'table'" class="overflow-x-auto">
                    <table class="w-full text-left" :class="density === 'condensed' ? 'condensed-table' : 'spacious-table'">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-pink-50 flex items-center justify-center text-pink-500 border border-pink-100 shadow-sm"><i class="fas fa-list text-[9px]"></i></div>
                                        <span>Type</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-pink-50 flex items-center justify-center text-pink-500 border border-pink-100 shadow-sm"><i class="fas fa-quote-left text-[9px]"></i></div>
                                        <span>Question Content</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-pink-50 flex items-center justify-center text-pink-500 border border-pink-100 shadow-sm"><i class="fas fa-tags text-[9px]"></i></div>
                                        <span>Classification</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-pink-50 flex items-center justify-center text-pink-500 border border-pink-100 shadow-sm"><i class="fas fa-gauge-high text-[9px]"></i></div>
                                        <span>Difficulty</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        <div class="w-7 h-7 rounded-lg bg-pink-50 flex items-center justify-center text-pink-500 border border-pink-100 shadow-sm"><i class="fas fa-star text-[9px]"></i></div>
                                        <span>Marks</span>
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
                            <template x-for="q in questionsPaged()" :key="q.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <span :class="q.type === 'MCQ' ? 'bg-blue-50 text-blue-600 border-blue-100' : q.type === 'Short' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-orange-50 text-orange-600 border-orange-100'"
                                              class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase font-mono" x-text="q.type"></span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="font-bold text-slate-800 text-sm truncate" x-text="q.question_text"></div>
                                        <div class="text-[10px] text-slate-400 mt-0.5" x-text="`Lang: ${q.language || 'English'}`"></div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-500" x-text="`${q.board?.code || '—'} / Cl.${q.class_id} / ${q.subject?.name || '—'}`"></td>
                                    <td class="px-6 py-4">
                                        <span :class="q.difficulty === 'Easy' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : q.difficulty === 'Medium' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-rose-50 text-rose-600 border-rose-100'"
                                              class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase" x-text="q.difficulty"></span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono font-black text-slate-600" x-text="q.marks"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center">
                                            <div class="inline-grid grid-cols-3 gap-1.5">
                                                <button @click="viewQuestion(q)" title="Inspect Data" class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition flex items-center justify-center">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                                <button @click="editQuestion(q)" title="Modify Properties" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white hover:bg-indigo-600 active:scale-95 transition flex items-center justify-center">
                                                    <i class="fas fa-sliders text-xs"></i>
                                                </button>
                                                <button @click="confirmDeleteQuestion(q)" title="Purge Record" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white hover:bg-rose-700 active:scale-95 transition flex items-center justify-center">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="questionsFiltered().length === 0">
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                                <i class="fas fa-circle-question text-slate-400 text-2xl"></i>
                                            </div>
                                            <p class="text-slate-400 text-sm font-bold">No questions found matching your query.</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- Pagination Row from ZIWO --}}
                    <div class="px-8 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-400"
                              x-text="`Showing ${Math.min((qPage-1)*qPerPage+1, questionsFiltered().length)}–${Math.min(qPage*qPerPage, questionsFiltered().length)} of ${questionsFiltered().length} questions`"></span>
                        <div class="flex items-center gap-1">
                            <button @click="qPage = Math.max(1, qPage - 1)" :disabled="qPage === 1"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </button>
                            <template x-for="p in Math.ceil(questionsFiltered().length / qPerPage)" :key="p">
                                <button @click="qPage = p"
                                        :class="qPage === p ? 'bg-pink-600 text-white shadow-md shadow-pink-500/20 font-black' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                                        class="w-8 h-8 rounded-lg text-xs transition" x-text="p"></button>
                            </template>
                            <button @click="qPage = Math.min(Math.ceil(questionsFiltered().length/qPerPage), qPage + 1)"
                                    :disabled="qPage >= Math.ceil(questionsFiltered().length / qPerPage)"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 text-xs flex items-center justify-center hover:bg-slate-50 disabled:opacity-40 transition">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- GRID VIEW --}}
                <div x-show="qView === 'grid'" class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        <template x-for="q in questionsPaged()" :key="q.id">
                            <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-slate-150 p-8 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden flex flex-col justify-between min-h-[260px]">
                                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                                    <i class="fas fa-circle-question text-9xl text-pink-900"></i>
                                </div>
                                <div>
                                    <div class="flex items-start justify-between mb-6 relative z-10">
                                        <span :class="q.type === 'MCQ' ? 'bg-blue-50 text-blue-600 border-blue-100' : q.type === 'Short' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-orange-50 text-orange-600 border-orange-100'"
                                              class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase font-mono" x-text="q.type"></span>
                                        <div class="text-right">
                                            <div class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">Complexity</div>
                                            <span :class="q.difficulty === 'Easy' ? 'text-emerald-500' : q.difficulty === 'Medium' ? 'text-amber-500' : 'text-rose-500'"
                                                  class="font-black text-[10px] tracking-widest uppercase justify-end" x-text="q.difficulty"></span>
                                        </div>
                                    </div>
                                    <div class="relative z-10">
                                        <h4 class="text-sm font-bold text-slate-700 leading-relaxed mb-4 line-clamp-3" x-text="q.question_text"></h4>
                                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100/80 mb-4 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Classification</span>
                                                <span class="font-bold text-slate-700 truncate max-w-[150px]" x-text="`${q.board?.code} / Cl.${q.class_id} / ${q.subject?.name}`"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-5 border-t border-slate-100 space-y-3 relative z-10">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest" x-text="`Marks Allocation: ${q.marks}`"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button @click="viewQuestion(q)" title="Inspect Data" class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white hover:bg-blue-600 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                        <button @click="editQuestion(q)" title="Modify Parameters" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white hover:bg-indigo-600 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                            <i class="fa-solid fa-sliders text-xs"></i>
                                        </button>
                                        <button @click="confirmDeleteQuestion(q)" title="Purge Record" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white hover:bg-rose-700 active:scale-95 transition-all shadow-sm flex items-center justify-center mx-auto aspect-square shrink-0">
                                            <i class="fa-solid fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right: Collapsible Sidebar Filter, aligned exactly where table begins --}}
            <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10"
                 class="w-64 flex-shrink-0 bg-white p-6 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-sliders text-pink-600 text-xs"></i>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Sidebar Filters</span>
                    </div>
                    <button @click="showFilters = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-4">
                    {{-- Board filter --}}
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Board</label>
                        <select x-model="qFilterBoard" @change="qPage = 1"
                                class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-pink-500 text-xs outline-none">
                            <option value="">All Boards</option>
                            <template x-for="board in boards" :key="board.id">
                                <option :value="board.id" x-text="board.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Subject filter --}}
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subject</label>
                        <select x-model="qFilterSubject" @change="qPage = 1"
                                class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-pink-500 text-xs outline-none">
                            <option value="">All Subjects</option>
                            <template x-for="subj in subjects" :key="subj.id">
                                <option :value="subj.id" x-text="subj.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Type filter --}}
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Evaluation Type</label>
                        <select x-model="qFilterType" @change="qPage = 1"
                                class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-pink-500 text-xs outline-none">
                            <option value="">All Types</option>
                            <option value="MCQ">MCQ</option>
                            <option value="Short">Short Question</option>
                            <option value="Long">Long Question</option>
                        </select>
                    </div>

                    {{-- Difficulty filter --}}
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Difficulty</label>
                        <select x-model="qFilterDifficulty" @change="qPage = 1"
                                class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-pink-500 text-xs outline-none">
                            <option value="">All Difficulty</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>

                    {{-- Records per page --}}
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Records Per Page</label>
                        <div class="grid grid-cols-4 gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200/50">
                            <template x-for="n in [5,10,20,50]" :key="n">
                                <button @click="qPerPage = n; qPage = 1"
                                        :class="qPerPage === n ? 'bg-white text-pink-600 shadow-sm font-black' : 'text-slate-500 hover:text-slate-700'"
                                        class="py-1.5 text-[9px] uppercase tracking-widest rounded-lg transition-all" x-text="n"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Action triggers --}}
                    <div class="pt-4 border-t border-slate-100">
                        <button @click="qSearch = ''; qFilterBoard = ''; qFilterSubject = ''; qFilterType = ''; qFilterDifficulty = ''; qPage = 1"
                                class="w-full py-4 text-rose-500 hover:bg-rose-50 rounded-3xl text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 active:scale-95 border border-rose-100">
                            <i class="fas fa-broom"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Question View Modal --}}
<div x-show="showQuestionViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden" @click.away="showQuestionViewModal = false">
        <div class="bg-gradient-to-r from-pink-600 to-indigo-750 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-circle-question"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none">Question profile payload</h3>
                    <p class="text-[9px] font-black text-pink-200 uppercase tracking-widest mt-1" x-text="`Question ID #${viewingQuestion?.id}`"></p>
                </div>
            </div>
            <button @click="showQuestionViewModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <div class="p-8 space-y-5 bg-slate-50/50">
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Question Content</span>
                <span class="text-sm font-bold text-slate-700 leading-relaxed block" x-text="viewingQuestion?.question_text"></span>
            </div>
            
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Type</span>
                    <span class="text-xs font-black text-indigo-650 uppercase" x-text="viewingQuestion?.type"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Difficulty</span>
                    <span class="text-xs font-black text-amber-650 uppercase" x-text="viewingQuestion?.difficulty"></span>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Marks</span>
                    <span class="text-xs font-black text-emerald-650 font-mono" x-text="viewingQuestion?.marks"></span>
                </div>
            </div>

            <template x-if="viewingQuestion?.type === 'MCQ' && viewingQuestion?.options">
                <div class="space-y-2 p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Options Matrix</div>
                    <div class="grid grid-cols-1 gap-2">
                        <template x-for="(opt, idx) in viewingQuestion.options" :key="idx">
                            <div class="flex items-center justify-between text-xs py-2 px-3 rounded-xl border transition"
                                 :class="opt.is_correct ? 'border-emerald-250 bg-emerald-50 text-emerald-700 font-bold shadow-sm' : 'border-slate-150 text-slate-500 bg-slate-50/50'">
                                <span x-text="`${['A','B','C','D'][idx]}. ${opt.option_text}`"></span>
                                <span x-show="opt.is_correct" class="text-[9px] font-black uppercase text-emerald-600">✓ CORRECT</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div class="flex justify-end pt-2">
                <button @click="showQuestionViewModal = false" class="px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95">Close Details</button>
            </div>
        </div>
    </div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div x-show="showQuestionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-150 rounded-[2rem] w-full max-w-2xl shadow-2xl overflow-hidden my-4" @click.away="showQuestionModal = false">
        <div class="bg-gradient-to-r from-pink-650 via-pink-700 to-indigo-800 px-8 py-6 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><i class="fas fa-circle-question"></i></div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight leading-none" x-text="questionForm.id ? 'Edit Question Parameters' : 'Register Question'"></h3>
                    <p class="text-[9px] font-black text-indigo-250 uppercase tracking-widest mt-1" x-text="questionForm.id ? 'QUESTION ID: #' + questionForm.id : 'NEW ENTRY PROFILE'"></p>
                </div>
            </div>
            <button @click="showQuestionModal = false" class="w-8 h-8 rounded-full bg-black/10 hover:bg-black/20 text-white flex items-center justify-center transition"><i class="fas fa-times text-xs"></i></button>
        </div>
        <form @submit.prevent="saveQuestion()" class="p-8 space-y-6 bg-slate-50/50" data-no-pjax>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Board</label>
                    <select x-model="questionForm.board_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id">
                            <option :value="board.id" x-text="board.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Class</label>
                    <select x-model="questionForm.class_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Class</option>
                        <template x-for="cls in classesList" :key="cls.id">
                            <option :value="cls.id" x-text="cls.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Subject</label>
                    <select x-model="questionForm.subject_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Subject</option>
                        <template x-for="subj in subjects" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Chapter</label>
                    <select x-model="questionForm.chapter_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Chapter</option>
                        <template x-for="ch in chapters" :key="ch.id">
                            <option :value="ch.id" x-text="ch.title"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Evaluation Type</label>
                    <select x-model="questionForm.type" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="MCQ">MCQ</option>
                        <option value="Short">Short Question</option>
                        <option value="Long">Long Question</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Difficulty</label>
                    <select x-model="questionForm.difficulty" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Language</label>
                    <select x-model="questionForm.language" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="English">English</option>
                        <option value="Urdu">Urdu</option>
                        <option value="Sindhi">Sindhi</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Marks Value</label>
                    <input type="number" x-model="questionForm.marks" required min="1"
                           class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm">
                </div>
            </div>
            
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Question Context Text</label>
                <textarea x-model="questionForm.question_text" required rows="3" placeholder="Context statement..."
                          class="block w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-sm outline-none shadow-sm"></textarea>
            </div>
            
            {{-- MCQ Options options --}}
            <div x-show="questionForm.type === 'MCQ'" class="space-y-3 p-5 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Options Matrix Definition</label>
                <template x-for="(opt, idx) in questionForm.options" :key="idx">
                    <div class="flex items-center gap-3">
                        <input type="radio" :name="'mcq_correct'" :checked="opt.is_correct" @change="setMcqCorrect(idx)"
                               class="h-4 w-4 bg-white border-slate-350 text-pink-650 focus:ring-pink-500">
                        <span class="text-xs text-slate-500 font-mono w-5 font-black" x-text="['A','B','C','D'][idx]"></span>
                        <input type="text" x-model="opt.option_text" :placeholder="`Option ${['A','B','C','D'][idx]} parameter statement`"
                               class="flex-1 rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-850 text-xs focus:ring-1 focus:ring-pink-500 outline-none">
                        <span x-show="opt.is_correct" class="text-[9px] text-emerald-600 font-black">✓ CORRECT</span>
                    </div>
                </template>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-slate-200/50">
                <button type="button" @click="showQuestionModal = false"
                        class="px-6 py-2.5 border border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">Cancel</button>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-pink-650 via-pink-700 to-indigo-800 hover:from-pink-700 hover:to-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95">
                    Save Parameters
                </button>
            </div>
        </form>
    </div>
</div>
