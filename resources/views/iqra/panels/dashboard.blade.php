{{-- Dashboard Panel --}}
<div x-show="currentView === 'dashboard'" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Classes Supported</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-indigo-400 text-xs"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold mt-2 text-indigo-400">12</div>
        </div>
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Boards</span>
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 flex items-center justify-center">
                    <i class="fas fa-building-columns text-purple-400 text-xs"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold mt-2 text-purple-400" x-text="boards.length || '—'"></div>
        </div>
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Subjects Indexed</span>
                <div class="w-9 h-9 rounded-xl bg-pink-500/10 flex items-center justify-center">
                    <i class="fas fa-book-open text-pink-400 text-xs"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold mt-2 text-pink-400" x-text="subjects.length || '—'"></div>
        </div>
        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Questions Banked</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <i class="fas fa-circle-question text-emerald-400 text-xs"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold mt-2 text-emerald-400" x-text="questions.length || '—'"></div>
        </div>
    </div>
    <div class="backdrop-blur-md bg-slate-900/40 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                <i class="fas fa-server text-indigo-400"></i>
            </div>
            <h3 class="text-lg font-bold text-indigo-400">Platform Specifications</h3>
        </div>
        <p class="text-slate-300 text-sm">IQRA is running on PHP 8.4 with Redis queue workers ready for OCR and document ingestion processing. Upload PDFs, Word documents, or web pages for automated text extraction and intelligent question bank population.</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">PHP 8.4-FPM</span>
            <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">Laravel 13</span>
            <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">MySQL 8.0</span>
            <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">Redis Queue</span>
            <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">Tesseract OCR</span>
            <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">Alpine.js SPA</span>
        </div>
    </div>
</div>
