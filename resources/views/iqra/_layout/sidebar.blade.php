{{-- Sidebar Navigation --}}
<div class="w-64 flex-shrink-0 backdrop-blur-md bg-slate-900/80 border-r border-slate-800 flex flex-col">
    <div class="h-16 flex items-center px-6 border-b border-slate-800">
        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">IQRA Platform</span>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pb-1">Overview</p>
        <a href="#" @click.prevent="currentView = 'dashboard'"
           :class="currentView === 'dashboard' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-th-large w-4 text-center opacity-60"></i> Dashboard
        </a>

        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">Academic Hierarchy</p>
        <a href="#" @click.prevent="loadBoards()"
           :class="currentView === 'boards' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-building-columns w-4 text-center opacity-60"></i> Boards
        </a>
        <a href="#" @click.prevent="loadSubjects()"
           :class="currentView === 'subjects' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-book-open w-4 text-center opacity-60"></i> Subjects
        </a>
        <a href="#" @click.prevent="loadChapters()"
           :class="currentView === 'chapters' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-bookmark w-4 text-center opacity-60"></i> Chapters
        </a>

        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">Content Pipeline</p>
        <a href="#" @click.prevent="openUploaderView()"
           :class="currentView === 'uploader' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-upload w-4 text-center opacity-60"></i> Ingestion &amp; OCR
        </a>
        <a href="#" @click.prevent="openJobsView()"
           :class="currentView === 'jobs' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-list-check w-4 text-center opacity-60"></i> Jobs &amp; Queue
            <span x-show="jobsData.counts?.pending > 0 || jobsData.counts?.processing > 0"
                  class="ml-auto px-1.5 py-0.5 bg-amber-500 text-white text-[9px] font-black rounded-full"
                  x-text="(jobsData.counts?.pending || 0) + (jobsData.counts?.processing || 0)"></span>
        </a>
        <a href="#" @click.prevent="openScraperView()"
           :class="currentView === 'scraper' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-spider w-4 text-center opacity-60"></i> Web Importer
        </a>
        <a href="#" @click.prevent="openLibraryView()"
           :class="currentView === 'library' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-folder-open w-4 text-center opacity-60"></i> Notes &amp; Materials
        </a>

        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">Examinations</p>
        <a href="#" @click.prevent="loadQuestions()"
           :class="currentView === 'questions' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-circle-question w-4 text-center opacity-60"></i> Question Bank
        </a>
        <a href="#" @click.prevent="openPaperGeneratorView()"
           :class="currentView === 'papers' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-file-alt w-4 text-center opacity-60"></i> Paper Generator
        </a>

        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">System</p>
        <a href="#" @click.prevent="openLogsView()"
           :class="currentView === 'logs' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
            <i class="fas fa-clipboard-list w-4 text-center opacity-60"></i> Logs &amp; Reports
        </a>

    </nav>
    <div class="p-4 border-t border-slate-800 text-xs text-slate-500 flex-shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600/30 flex items-center justify-center text-indigo-400 text-xs font-black"
                 x-text="user?.name?.charAt(0) || 'A'"></div>
            <span class="text-slate-300 font-medium truncate" x-text="user?.email"></span>
        </div>
    </div>
</div>
