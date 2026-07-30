{{-- Dashboard Panel — Premium ZIWO Light Theme --}}
<div x-show="currentView === 'dashboard'" class="space-y-6" x-transition>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl border border-slate-150/80 p-6 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Classes Supported</p>
                <div class="text-3xl font-black text-slate-800 mt-1">12</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm">
                <i class="fas fa-graduation-cap text-base"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl border border-slate-150/80 p-6 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Boards</p>
                <div class="text-3xl font-black text-slate-800 mt-1" x-text="boards.length || '0'"></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm">
                <i class="fas fa-building-columns text-base"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-150/80 p-6 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subjects Indexed</p>
                <div class="text-3xl font-black text-slate-800 mt-1" x-text="subjects.length || '0'"></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-pink-50 flex items-center justify-center text-pink-600 shadow-sm">
                <i class="fas fa-book-open text-base"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-150/80 p-6 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Questions Banked</p>
                <div class="text-3xl font-black text-slate-800 mt-1" x-text="questions.length || '0'"></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                <i class="fas fa-circle-question text-base"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-150 p-8 shadow-[0_10px_40px_rgba(0,0,0,0.02)]">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-server text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-blue-900 tracking-tight">Platform Specifications</h3>
                <p class="text-slate-400 text-xs font-bold mt-0.5">Deployment nodes and core microservice runtimes</p>
            </div>
        </div>
        <p class="text-slate-600 text-sm leading-relaxed max-w-4xl">IQRA is running on PHP 8.4 with Redis queue workers ready for OCR and document ingestion processing. Upload PDFs, Word documents, or web pages for automated text extraction and intelligent question bank population.</p>
        <div class="mt-6 flex flex-wrap gap-2">
            <span class="px-3.5 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl border border-blue-100 shadow-sm">PHP 8.4-FPM</span>
            <span class="px-3.5 py-1.5 bg-purple-50 text-purple-700 text-xs font-bold rounded-xl border border-purple-100 shadow-sm">Laravel 13</span>
            <span class="px-3.5 py-1.5 bg-pink-50 text-pink-700 text-xs font-bold rounded-xl border border-pink-100 shadow-sm">MySQL 8.0</span>
            <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100 shadow-sm">Redis Queue</span>
            <span class="px-3.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-bold rounded-xl border border-amber-100 shadow-sm">Tesseract OCR</span>
            <span class="px-3.5 py-1.5 bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-sm">Alpine.js SPA</span>
        </div>
    </div>
</div>
