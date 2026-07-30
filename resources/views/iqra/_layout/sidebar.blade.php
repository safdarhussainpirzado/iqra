{{-- Premium Sidebar Navigation matching ZIWO CRM Layout --}}
<aside class="sidebar-wrap h-screen bg-white border-r border-slate-100 shadow-[2px_0_20px_rgba(0,0,0,0.04)]"
       :class="{ 'collapsed': !showSidebar }">

    <!-- Floating Collapse Toggle Tab -->
    <button class="sidebar-toggle-tab hidden lg:flex group" @click="showSidebar = !showSidebar"
            :title="showSidebar ? 'Collapse Sidebar' : 'Expand Sidebar'">
        <i class="fa-solid fa-chevron-left" :class="{ 'rotate-180': !showSidebar }"></i>
    </button>

    <div class="sidebar-inner">
        <!-- Brand Header -->
        <div class="flex items-center px-4 h-[64px] border-b border-slate-100 shrink-0 gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-[0_4px_10px_rgba(37,99,235,0.3)] shrink-0 mx-auto">
                <i class="fa-solid fa-graduation-cap text-sm"></i>
            </div>
            <div class="flex flex-col sidebar-brand-text flex-1 min-w-0">
                <span class="font-black text-base tracking-tight text-navy-900 leading-none">IQRA<span class="text-blue-600"> Monolith</span></span>
                <span class="text-[8px] font-black text-slate-400 tracking-[0.12em] uppercase mt-0.5">Control Grid</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5 no-scrollbar">
            
            <div class="nav-section">Overview</div>
            <div class="nav-section-divider"></div>
            <a href="#" @click.prevent="currentView = 'dashboard'"
               :class="currentView === 'dashboard' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="nav-label">Dashboard</span>
                <span class="nav-tooltip">Dashboard</span>
            </a>

            <div class="nav-section">Academic Hierarchy</div>
            <div class="nav-section-divider"></div>
            <a href="#" @click.prevent="loadBoards()"
               :class="currentView === 'boards' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-building-columns"></i></span>
                <span class="nav-label">Boards</span>
                <span class="nav-tooltip">Boards</span>
            </a>
            <a href="#" @click.prevent="loadSubjects()"
               :class="currentView === 'subjects' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-book-open"></i></span>
                <span class="nav-label">Subjects</span>
                <span class="nav-tooltip">Subjects</span>
            </a>
            <a href="#" @click.prevent="loadChapters()"
               :class="currentView === 'chapters' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-bookmark"></i></span>
                <span class="nav-label">Chapters</span>
                <span class="nav-tooltip">Chapters</span>
            </a>

            <div class="nav-section">Content Pipeline</div>
            <div class="nav-section-divider"></div>
            <a href="#" @click.prevent="openUploaderView()"
               :class="currentView === 'uploader' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                <span class="nav-label">Ingestion &amp; OCR</span>
                <span class="nav-tooltip">Ingestion &amp; OCR</span>
            </a>
            <a href="#" @click.prevent="openJobsView()"
               :class="currentView === 'jobs' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-list-check"></i></span>
                <span class="nav-label">Jobs &amp; Queue</span>
                <span class="nav-badge bg-rose-100 text-rose-600"
                      x-show="jobsData.counts?.pending > 0 || jobsData.counts?.processing > 0"
                      x-text="(jobsData.counts?.pending || 0) + (jobsData.counts?.processing || 0)"></span>
                <span class="nav-tooltip">Jobs &amp; Queue</span>
            </a>
            <a href="#" @click.prevent="openScraperView()"
               :class="currentView === 'scraper' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-spider"></i></span>
                <span class="nav-label">Web Importer</span>
                <span class="nav-tooltip">Web Importer</span>
            </a>
            <a href="#" @click.prevent="openLibraryView()"
               :class="currentView === 'library' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-folder-open"></i></span>
                <span class="nav-label">Notes &amp; Materials</span>
                <span class="nav-tooltip">Notes &amp; Materials</span>
            </a>

            <div class="nav-section">Examinations</div>
            <div class="nav-section-divider"></div>
            <a href="#" @click.prevent="loadQuestions()"
               :class="currentView === 'questions' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span>
                <span class="nav-label">Question Bank</span>
                <span class="nav-tooltip">Question Bank</span>
            </a>
            <a href="#" @click.prevent="openPaperGeneratorView()"
               :class="currentView === 'papers' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-file-invoice"></i></span>
                <span class="nav-label">Paper Generator</span>
                <span class="nav-tooltip">Paper Generator</span>
            </a>

            <div class="nav-section">System</div>
            <div class="nav-section-divider"></div>
            <a href="#" @click.prevent="openLogsView()"
               :class="currentView === 'logs' ? 'active' : ''"
               class="nav-item">
                <span class="nav-icon"><i class="fa-solid fa-fingerprint"></i></span>
                <span class="nav-label">Logs &amp; Reports</span>
                <span class="nav-tooltip">Logs &amp; Reports</span>
            </a>

        </nav>

        <!-- User Footer profile card -->
        <div class="border-t border-slate-100 p-3 shrink-0">
            <div class="flex items-center gap-3 px-1">
                <div class="flex items-center gap-3 flex-1 min-w-0 p-1 rounded-xl transition-colors group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-sm shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0 sidebar-user-label flex-1">
                        <span class="text-xs font-black text-navy-900 tracking-tight truncate" x-text="user?.name || 'IQRA Administrator'"></span>
                        <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest mt-0.5" x-text="user?.roles?.[0] || 'Super Admin'"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</aside>
