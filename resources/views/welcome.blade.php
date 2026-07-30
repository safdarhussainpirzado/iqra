<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQRA — Enterprise Educational Platform</title>
    <meta name="description" content="IQRA is an enterprise educational content management platform for OCR ingestion, question bank management, and paper generation.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        body { background: radial-gradient(circle at top left, #1e1b4b, #0f172a, #020617); }
        .pulse-dot { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        textarea { background: #0f172a; }
    </style>
</head>
<body class="h-full text-slate-100 antialiased selection:bg-indigo-500 selection:text-white" x-data="appState()" x-init="initApp()" x-cloak>

    <!-- Global Background Gradients -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute top-1/3 right-10 h-80 w-80 rounded-full bg-purple-500/10 blur-3xl"></div>
        <div class="absolute -bottom-20 left-1/3 h-96 w-96 rounded-full bg-pink-500/5 blur-3xl"></div>
    </div>

    <!-- Global Toast Notification -->
    <div x-show="toastVisible" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        :class="toastType === 'success' ? 'bg-emerald-600 border-emerald-500' : 'bg-rose-600 border-rose-500'"
        class="fixed bottom-6 right-6 z-[200] px-5 py-3 rounded-xl border text-white text-sm font-semibold shadow-2xl flex items-center gap-3">
        <i :class="toastType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'" class="fas"></i>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Universal Confirmation Modal -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-sm shadow-2xl p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div :class="confirmConfig.isDanger ? 'bg-rose-500/20 text-rose-400' : 'bg-amber-500/20 text-amber-400'" class="w-10 h-10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-base font-bold text-slate-100" x-text="confirmConfig.title"></h3>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed" x-html="confirmConfig.message"></p>
            <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                <button @click="showConfirmModal = false" class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                <button @click="executeConfirmAction()" :disabled="confirmLoading"
                    :class="confirmConfig.isDanger ? 'bg-rose-600 hover:bg-rose-500' : 'bg-indigo-600 hover:bg-indigo-500'"
                    class="px-5 py-2 text-xs font-semibold text-white rounded-xl transition disabled:opacity-50 flex items-center gap-2">
                    <i x-show="confirmLoading" class="fas fa-spinner fa-spin"></i>
                    <span x-show="!confirmLoading">Confirm</span>
                    <span x-show="confirmLoading">Processing…</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Login Screen -->
    <template x-if="!token">
        <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
                <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">IQRA</h1>
                <p class="mt-2 text-sm text-slate-400">Enterprise Educational Content & Question Bank Monolith</p>
            </div>
            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="backdrop-blur-md bg-slate-900/60 border border-slate-700/50 px-4 py-8 shadow-2xl rounded-2xl sm:px-10">
                    <form class="space-y-6" @submit.prevent="login()">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300">Email Address</label>
                            <input id="email" x-model="loginForm.email" type="email" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800/80 py-2.5 text-slate-100 shadow-sm ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm px-3">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                            <input id="password" x-model="loginForm.password" type="password" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800/80 py-2.5 text-slate-100 shadow-sm ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm px-3">
                        </div>
                        <template x-if="loginError">
                            <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400" x-text="loginError"></div>
                        </template>
                        <button type="submit" class="flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-200">
                            Sign In
                        </button>
                    </form>
                    <div class="mt-6 text-center text-xs text-slate-500">
                        Use <code class="text-indigo-400">admin@iqra.edu</code> / <code class="text-indigo-400">Admin@12345</code>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Main Dashboard Shell -->
    <template x-if="token">
        <div class="flex h-full min-h-screen">
            <!-- Sidebar -->
            <div class="w-64 flex-shrink-0 backdrop-blur-md bg-slate-900/80 border-r border-slate-800 flex flex-col">
                <div class="h-16 flex items-center px-6 border-b border-slate-800">
                    <span class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">IQRA Platform</span>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pb-1">Overview</p>
                    <a href="#" @click.prevent="currentView = 'dashboard'" :class="currentView === 'dashboard' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-th-large w-4 text-center opacity-60"></i> Dashboard
                    </a>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">Academic Hierarchy</p>
                    <a href="#" @click.prevent="loadBoards()" :class="currentView === 'boards' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-building-columns w-4 text-center opacity-60"></i> Boards
                    </a>
                    <a href="#" @click.prevent="loadSubjects()" :class="currentView === 'subjects' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-book-open w-4 text-center opacity-60"></i> Subjects
                    </a>
                    <a href="#" @click.prevent="loadChapters()" :class="currentView === 'chapters' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-bookmark w-4 text-center opacity-60"></i> Chapters
                    </a>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">Content Pipeline</p>
                    <a href="#" @click.prevent="openUploaderView()" :class="currentView === 'uploader' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-upload w-4 text-center opacity-60"></i> Ingestion & OCR
                    </a>
                    <a href="#" @click.prevent="openJobsView()" :class="currentView === 'jobs' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-list-check w-4 text-center opacity-60"></i> Jobs & Queue
                        <span x-show="jobsData.counts?.pending > 0 || jobsData.counts?.processing > 0" class="ml-auto px-1.5 py-0.5 bg-amber-500 text-white text-[9px] font-black rounded-full" x-text="(jobsData.counts?.pending || 0) + (jobsData.counts?.processing || 0)"></span>
                    </a>
                    <a href="#" @click.prevent="openScraperView()" :class="currentView === 'scraper' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-spider w-4 text-center opacity-60"></i> Web Importer
                    </a>
                    <a href="#" @click.prevent="openLibraryView()" :class="currentView === 'library' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-folder-open w-4 text-center opacity-60"></i> Notes & Materials
                    </a>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">Examinations</p>
                    <a href="#" @click.prevent="loadQuestions()" :class="currentView === 'questions' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-circle-question w-4 text-center opacity-60"></i> Question Bank
                    </a>
                    <a href="#" @click.prevent="openPaperGeneratorView()" :class="currentView === 'papers' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-file-alt w-4 text-center opacity-60"></i> Paper Generator
                    </a>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-3 pt-3 pb-1">System</p>
                    <a href="#" @click.prevent="openLogsView()" :class="currentView === 'logs' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        <i class="fas fa-clipboard-list w-4 text-center opacity-60"></i> Logs & Reports
                    </a>
                </nav>
                <div class="p-4 border-t border-slate-800 text-xs text-slate-500 flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-indigo-600/30 flex items-center justify-center text-indigo-400 text-xs font-black" x-text="user?.name?.charAt(0) || 'A'"></div>
                        <span class="text-slate-300 font-medium truncate" x-text="user?.email"></span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                <!-- Topbar -->
                <header class="h-16 backdrop-blur-md bg-slate-900/60 border-b border-slate-800 px-8 flex items-center justify-between flex-shrink-0 sticky top-0 z-10">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-semibold tracking-tight text-slate-100 capitalize" x-text="currentView === 'papers' ? 'Paper Generator' : currentView === 'jobs' ? 'Jobs & Queue Monitor' : currentView === 'uploader' ? 'Ingestion & OCR' : currentView === 'library' ? 'Notes & Materials' : currentView"></h2>
                        <!-- Live status badge for jobs view -->
                        <span x-show="currentView === 'jobs' && (jobsData.counts?.pending > 0 || jobsData.counts?.processing > 0)" class="pulse-dot px-2 py-0.5 bg-amber-500/20 border border-amber-500/40 text-amber-300 text-[10px] font-black rounded-full uppercase tracking-widest">
                            Live
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-500" x-text="new Date().toLocaleDateString('en-PK', {weekday:'short', year:'numeric', month:'short', day:'numeric'})"></span>
                        <button @click="logout()" class="px-3.5 py-1.5 text-xs font-semibold text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 rounded-lg transition duration-150">
                            <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
                        </button>
                    </div>
                </header>

                <!-- Main Content Panels -->
                <main class="flex-1 p-8">

                    <!-- ============================== DASHBOARD ============================== -->
                    <div x-show="currentView === 'dashboard'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-400">Classes Supported</span>
                                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 flex items-center justify-center"><i class="fas fa-graduation-cap text-indigo-400 text-xs"></i></div>
                                </div>
                                <div class="text-3xl font-extrabold mt-2 text-indigo-400">12</div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-400">Boards</span>
                                    <div class="w-9 h-9 rounded-xl bg-purple-500/10 flex items-center justify-center"><i class="fas fa-building-columns text-purple-400 text-xs"></i></div>
                                </div>
                                <div class="text-3xl font-extrabold mt-2 text-purple-400" x-text="boards.length || '—'"></div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-400">Subjects Indexed</span>
                                    <div class="w-9 h-9 rounded-xl bg-pink-500/10 flex items-center justify-center"><i class="fas fa-book-open text-pink-400 text-xs"></i></div>
                                </div>
                                <div class="text-3xl font-extrabold mt-2 text-pink-400" x-text="subjects.length || '—'"></div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-400">Questions Banked</span>
                                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center"><i class="fas fa-circle-question text-emerald-400 text-xs"></i></div>
                                </div>
                                <div class="text-3xl font-extrabold mt-2 text-emerald-400" x-text="questions.length || '—'"></div>
                            </div>
                        </div>
                        <div class="backdrop-blur-md bg-slate-900/40 border border-slate-800 p-6 rounded-2xl shadow-xl">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-9 h-9 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i class="fas fa-server text-indigo-400"></i></div>
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

                    <!-- ============================== BOARDS ============================== -->
                    <div x-show="currentView === 'boards'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-100">Examination Boards</h3>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="`${boards.filter(b => b.name.toLowerCase().includes(boardSearch.toLowerCase())).length} boards registered`"></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                                    <input type="text" x-model="boardSearch" placeholder="Search boards…" class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-48 placeholder:text-slate-500">
                                </div>
                                <button @click="boardForm = {id:null,name:'',code:''}; showBoardModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition duration-150 flex items-center gap-2">
                                    <i class="fas fa-plus"></i> Add Board
                                </button>
                            </div>
                        </div>

                        <!-- Board Modal (Bento style) -->
                        <div x-show="showBoardModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
                                <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 px-6 py-5">
                                    <h3 class="text-lg font-bold text-white" x-text="boardForm.id ? 'Edit Board' : 'Add New Board'"></h3>
                                    <p class="text-indigo-200 text-xs mt-1">Configure examination board details below.</p>
                                </div>
                                <form @submit.prevent="saveBoard()" class="p-6 space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1">Board Name</label>
                                        <input type="text" x-model="boardForm.name" required placeholder="E.g., Punjab Board of Secondary Education" class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1">Board Code</label>
                                        <input type="text" x-model="boardForm.code" required placeholder="E.g., PBSE" class="block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                                        <button type="button" @click="showBoardModal = false" class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl transition flex items-center gap-2">
                                            <i class="fas fa-save"></i> Save Board
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Boards Table -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                                    <tr>
                                        <th class="px-6 py-4">#</th>
                                        <th class="px-6 py-4">Board Name</th>
                                        <th class="px-6 py-4">Code</th>
                                        <th class="px-6 py-4 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="board in boards.filter(b => b.name.toLowerCase().includes(boardSearch.toLowerCase()))" :key="board.id">
                                        <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition">
                                            <td class="px-6 py-3.5 text-xs text-slate-500" x-text="board.id"></td>
                                            <td class="px-6 py-3.5 font-semibold" x-text="board.name"></td>
                                            <td class="px-6 py-3.5"><span class="px-2.5 py-1 bg-indigo-500/15 text-indigo-300 text-xs rounded-lg font-mono font-bold" x-text="board.code"></span></td>
                                            <td class="px-6 py-3.5">
                                                <div class="flex items-center justify-center">
                                                    <div class="inline-grid grid-cols-2 gap-1.5">
                                                        <button @click="editBoard(board)" title="Edit" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition"><i class="fas fa-pencil"></i></button>
                                                        <button @click="confirmDeleteItem(board, 'boards', 'boards', 'name')" title="Delete" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition"><i class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="boards.length === 0">
                                        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">No boards configured yet. Click "Add Board" to get started.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ============================== SUBJECTS ============================== -->
                    <div x-show="currentView === 'subjects'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-100">Registered Subjects</h3>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredSubjects().length} subjects`"></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <select x-model="subjectFilterBoard" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                                    <option value="">All Boards</option>
                                    <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                                </select>
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                                    <input type="text" x-model="subjectSearch" placeholder="Search subjects…" class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
                                </div>
                            </div>
                        </div>
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                                    <tr>
                                        <th class="px-6 py-4">Subject Name</th>
                                        <th class="px-6 py-4">Code</th>
                                        <th class="px-6 py-4">Board</th>
                                        <th class="px-6 py-4">Class Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="subj in filteredSubjects()" :key="subj.id">
                                        <tr class="border-b border-slate-800/60 hover:bg-slate-800/30 transition">
                                            <td class="px-6 py-3.5 font-semibold" x-text="subj.name"></td>
                                            <td class="px-6 py-3.5"><span class="px-2.5 py-1 bg-purple-500/15 text-purple-300 text-xs rounded-lg font-mono font-bold" x-text="subj.code"></span></td>
                                            <td class="px-6 py-3.5 text-xs text-slate-400" x-text="subj.board?.name || '—'"></td>
                                            <td class="px-6 py-3.5 text-xs text-slate-400" x-text="subj.class ? 'Class ' + subj.class_id : '—'"></td>
                                        </tr>
                                    </template>
                                    <template x-if="filteredSubjects().length === 0">
                                        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">No subjects found.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ============================== CHAPTERS ============================== -->
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
                                    <input type="text" x-model="chapterSearch" placeholder="Search chapters…" class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
                                </div>
                                <button @click="openChapterCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                                    <i class="fas fa-plus"></i> Add Chapter
                                </button>
                            </div>
                        </div>

                        <!-- Chapter Modal (Bento) -->
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
                                        <button type="button" @click="showChapterModal = false" class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl transition flex items-center gap-2">
                                            <i class="fas fa-save"></i> Save Chapter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Chapters Table -->
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
                                                        <button @click="editChapter(ch)" title="Edit" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition"><i class="fas fa-pencil"></i></button>
                                                        <button @click="confirmDeleteChapter(ch)" title="Delete" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition"><i class="fas fa-trash-alt"></i></button>
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

                    <!-- ============================== INGESTION / UPLOADER ============================== -->
                    <div x-show="currentView === 'uploader'" class="space-y-6">
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i class="fas fa-upload text-indigo-400"></i></div>
                                <div>
                                    <h3 class="text-base font-bold text-indigo-400">Document Ingestion & Text Corpus Extractor</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Upload files to extract and index text. For scanned PDFs, enable OCR to run Tesseract in the background.</p>
                                </div>
                            </div>
                            <form @submit.prevent="submitUpload()" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 mb-1">Board</label>
                                            <select x-model="uploadForm.board_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Board</option>
                                                <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 mb-1">Class</label>
                                            <select x-model="uploadForm.class_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Class</option>
                                                <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 mb-1">Subject</label>
                                            <select x-model="uploadForm.subject_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Subject</option>
                                                <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 mb-1">Chapter</label>
                                            <select x-model="uploadForm.chapter_id" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Chapter</option>
                                                <template x-for="ch in chapters" :key="ch.id"><option :value="ch.id" x-text="ch.title"></option></template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 mb-1">Category</label>
                                            <select x-model="uploadForm.target_type" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="note">Notes</option>
                                                <option value="material">Books / Material</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 mb-1">Document Title</label>
                                            <input type="text" x-model="uploadForm.title" required placeholder="E.g., Physics Notes Unit 1" class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4 flex flex-col justify-between">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1">Choose File</label>
                                        <p class="text-[10px] text-slate-500 mb-2">Supported: .pdf, .docx, .txt, .csv, .xlsx, .json, .html (max 20MB)</p>
                                        <input type="file" @change="uploadForm.file = $event.target.files[0]" required class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600 transition">
                                    </div>
                                    <div class="flex items-start space-x-3 bg-amber-500/5 border border-amber-500/20 p-4 rounded-xl">
                                        <input id="run-ocr" type="checkbox" x-model="uploadForm.run_ocr" class="h-4 w-4 mt-0.5 rounded border-slate-700 bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <label for="run-ocr" class="text-xs font-semibold text-amber-300">Enable OCR (Tesseract)</label>
                                            <p class="text-[10px] text-slate-500 mt-0.5">Required for scanned image PDFs. Runs in background queue. Check <strong>Jobs & Queue</strong> for progress.</p>
                                        </div>
                                    </div>
                                    <button type="submit" :disabled="uploading" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-md transition duration-150 flex items-center justify-center gap-2 disabled:opacity-60">
                                        <i class="fas fa-upload"></i>
                                        <span x-text="uploading ? 'Processing…' : 'Upload & Process'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Extracted text preview -->
                        <div x-show="extractedTextPreview" class="backdrop-blur-md bg-slate-900/60 border border-emerald-800/50 p-6 rounded-2xl shadow-xl space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h4 class="text-sm font-semibold text-emerald-400 flex items-center gap-2"><i class="fas fa-file-lines"></i> Extracted Text Preview</h4>
                                <span class="text-xs text-slate-400"><span x-text="extractedTextPreview.length"></span> characters extracted</span>
                            </div>
                            <textarea x-model="extractedTextPreview" rows="10" class="w-full rounded-xl border border-slate-700 p-4 text-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>

                    <!-- ============================== JOBS & QUEUE MONITOR ============================== -->
                    <div x-show="currentView === 'jobs'" class="space-y-6">
                        <!-- Stats row -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-5 rounded-2xl shadow-xl flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center"><i class="fas fa-hourglass-half text-amber-400"></i></div>
                                <div>
                                    <div class="text-2xl font-black text-amber-400" x-text="jobsData.counts?.pending || 0"></div>
                                    <div class="text-xs text-slate-500 font-medium">Pending</div>
                                </div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-5 rounded-2xl shadow-xl flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center"><div class="pulse-dot w-3 h-3 rounded-full bg-blue-400"></div></div>
                                <div>
                                    <div class="text-2xl font-black text-blue-400" x-text="jobsData.counts?.processing || 0"></div>
                                    <div class="text-xs text-slate-500 font-medium">Processing</div>
                                </div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-5 rounded-2xl shadow-xl flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center"><i class="fas fa-circle-xmark text-rose-400"></i></div>
                                <div>
                                    <div class="text-2xl font-black text-rose-400" x-text="jobsData.counts?.failed || 0"></div>
                                    <div class="text-xs text-slate-500 font-medium">Failed</div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending / Active Jobs -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-200 flex items-center gap-2"><i class="fas fa-list-check text-indigo-400"></i> Active Queue</h4>
                                <button @click="loadJobs()" class="text-xs text-slate-400 hover:text-indigo-400 flex items-center gap-1 transition"><i class="fas fa-rotate-right text-xs"></i> Refresh</button>
                            </div>
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                                    <tr>
                                        <th class="px-6 py-3">ID</th>
                                        <th class="px-6 py-3">Job Type</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3">Attempts</th>
                                        <th class="px-6 py-3">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="job in jobsData.pending || []" :key="job.id">
                                        <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                                            <td class="px-6 py-3.5 text-xs font-mono text-slate-500" x-text="job.id"></td>
                                            <td class="px-6 py-3.5 font-medium text-xs" x-text="job.job_name"></td>
                                            <td class="px-6 py-3.5">
                                                <span :class="job.status === 'processing' ? 'bg-blue-500/20 text-blue-300' : 'bg-amber-500/20 text-amber-300'" class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide flex items-center gap-1.5 w-fit">
                                                    <span x-show="job.status === 'processing'" class="pulse-dot w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                                    <span x-text="job.status"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-xs text-slate-400" x-text="job.attempts"></td>
                                            <td class="px-6 py-3.5 text-xs text-slate-400" x-text="job.created_at"></td>
                                        </tr>
                                    </template>
                                    <template x-if="(jobsData.pending || []).length === 0">
                                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">No active jobs. Upload a PDF with OCR to see jobs appear here.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Failed Jobs -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-rose-900/30 rounded-2xl overflow-hidden shadow-xl">
                            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-rose-400 flex items-center gap-2"><i class="fas fa-circle-xmark"></i> Failed Jobs</h4>
                                <span class="text-xs text-slate-500">Retry or purge individual failed jobs below</span>
                            </div>
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                                    <tr>
                                        <th class="px-6 py-3">ID</th>
                                        <th class="px-6 py-3">Job Type</th>
                                        <th class="px-6 py-3">Error</th>
                                        <th class="px-6 py-3">Failed At</th>
                                        <th class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="job in jobsData.failed || []" :key="job.id">
                                        <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                                            <td class="px-6 py-3.5 text-xs font-mono text-slate-500" x-text="job.id"></td>
                                            <td class="px-6 py-3.5 font-medium text-xs" x-text="job.job_name"></td>
                                            <td class="px-6 py-3.5 text-xs text-rose-400 max-w-xs truncate" x-text="job.error"></td>
                                            <td class="px-6 py-3.5 text-xs text-slate-400" x-text="job.created_at"></td>
                                            <td class="px-6 py-3.5">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button @click="retryJob(job.id)" title="Retry Job" class="w-9 h-9 rounded-xl bg-amber-500 border border-amber-600 text-white flex items-center justify-center text-xs hover:bg-amber-400 active:scale-95 transition"><i class="fas fa-rotate-right"></i></button>
                                                    <button @click="deleteFailedJob(job.id)" title="Purge Job" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="(jobsData.failed || []).length === 0">
                                        <tr><td colspan="5" class="px-6 py-8 text-center text-emerald-500/70 text-sm"><i class="fas fa-check-circle mr-2"></i>No failed jobs — all clear!</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Activity log of completed OCR jobs -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-emerald-900/30 rounded-2xl overflow-hidden shadow-xl">
                            <div class="px-6 py-4 border-b border-slate-800">
                                <h4 class="text-sm font-bold text-emerald-400 flex items-center gap-2"><i class="fas fa-check-circle"></i> Completed OCR Jobs (Activity Log)</h4>
                            </div>
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                                    <tr>
                                        <th class="px-6 py-3">Timestamp</th>
                                        <th class="px-6 py-3">User</th>
                                        <th class="px-6 py-3">Action</th>
                                        <th class="px-6 py-3">Document</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="log in systemLogs.filter(l => l.action.includes('ingest') || l.action.includes('ocr'))" :key="log.id">
                                        <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                                            <td class="px-6 py-3.5 text-xs text-slate-400" x-text="log.created_at"></td>
                                            <td class="px-6 py-3.5 text-xs font-medium" x-text="log.user?.name || 'System'"></td>
                                            <td class="px-6 py-3.5"><span :class="log.action.includes('success') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300'" class="px-2 py-0.5 rounded-full text-[10px] font-black" x-text="log.action"></span></td>
                                            <td class="px-6 py-3.5 text-xs text-slate-300" x-text="log.description"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ============================== WEB IMPORTER ============================== -->
                    <div x-show="currentView === 'scraper'" class="space-y-6">
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i class="fas fa-spider text-indigo-400"></i></div>
                                <div>
                                    <h3 class="text-base font-bold text-indigo-400">Web Page Scraping Importer</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Enter any public URL and classify it for the database.</p>
                                </div>
                            </div>
                            <form @submit.prevent="submitScrape()" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <select x-model="scrapeForm.board_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Board</option>
                                        <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                                    </select>
                                    <select x-model="scrapeForm.class_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Class</option>
                                        <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                                    </select>
                                    <select x-model="scrapeForm.subject_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Subject</option>
                                        <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                                    </select>
                                    <select x-model="scrapeForm.chapter_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Chapter</option>
                                        <template x-for="ch in chapters" :key="ch.id"><option :value="ch.id" x-text="ch.title"></option></template>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <input type="url" x-model="scrapeForm.url" required placeholder="https://example-education.com/notes/chapter-1" class="w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                    </div>
                                    <input type="text" x-model="scrapeForm.title" required placeholder="Document Title" class="w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" :disabled="scraping" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2 disabled:opacity-60">
                                        <i class="fas fa-spider"></i>
                                        <span x-text="scraping ? 'Scraping Page…' : 'Scrape & Index Website'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ============================== NOTES & MATERIALS LIBRARY ============================== -->
                    <div x-show="currentView === 'library'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-100">Indexed Notes & Books Library</h3>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredLibraryItems().length} documents indexed`"></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <select x-model="libraryFilterType" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                                    <option value="">All Types</option>
                                    <option value="note">Notes</option>
                                    <option value="material">Materials</option>
                                </select>
                                <select x-model="libraryFilterBoard" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                                    <option value="">All Boards</option>
                                    <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                                </select>
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                                    <input type="text" x-model="librarySearch" placeholder="Search documents…" class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Left: Item List -->
                            <div class="space-y-3 max-h-[680px] overflow-y-auto pr-1">
                                <template x-for="item in filteredLibraryItems()" :key="item.unique_id">
                                    <div @click="selectLibraryItem(item)" :class="activeLibraryItem?.unique_id === item.unique_id ? 'border-indigo-500/60 bg-indigo-600/15' : 'border-slate-700/50 bg-slate-900/60 hover:border-slate-600'" class="p-4 rounded-xl border cursor-pointer transition duration-150">
                                        <div class="flex items-center justify-between mb-2">
                                            <span :class="item.type === 'note' ? 'bg-indigo-500/20 text-indigo-300' : 'bg-pink-500/20 text-pink-300'" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" x-text="item.type"></span>
                                            <span class="text-[10px] text-slate-500 font-mono" x-text="item.file_type"></span>
                                        </div>
                                        <h5 class="text-sm font-bold text-slate-100 leading-snug" x-text="item.title"></h5>
                                        <div class="text-[10px] text-slate-500 mt-1" x-text="`${item.board?.name || '—'} / ${item.subject?.name || '—'} / ${item.chapter?.title || '—'}`"></div>
                                        <div class="text-[10px] text-slate-600 mt-1" x-text="`${(item.extracted_text || '').length} chars`"></div>
                                    </div>
                                </template>
                                <template x-if="filteredLibraryItems().length === 0">
                                    <div class="text-xs text-slate-500 py-8 text-center border border-dashed border-slate-800 rounded-xl">No documents found. Upload via Ingestion & OCR.</div>
                                </template>
                            </div>
                            <!-- Right: Editor Pane -->
                            <div class="lg:col-span-2">
                                <template x-if="activeLibraryItem">
                                    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
                                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 px-6 py-4 border-b border-slate-800">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h4 class="text-sm font-bold text-indigo-400" x-text="activeLibraryItem.title"></h4>
                                                    <p class="text-[10px] text-slate-400 mt-0.5" x-text="`${activeLibraryItem.board?.name} › ${activeLibraryItem.subject?.name} › ${activeLibraryItem.chapter?.title}`"></p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] text-slate-500 font-mono" x-text="activeLibraryItem.file_type"></span>
                                                    <button @click="confirmDeleteLibraryItem(activeLibraryItem)" class="w-8 h-8 rounded-lg bg-rose-600/80 text-white flex items-center justify-center text-xs hover:bg-rose-500 transition active:scale-95"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-6 space-y-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 mb-2">Extracted Text Corpus (Editable)</label>
                                                <textarea x-model="activeLibraryItem.extracted_text" rows="16" class="w-full rounded-xl border border-slate-700 p-4 text-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500 leading-relaxed"></textarea>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-[10px] text-slate-500">Characters: <strong class="text-slate-400" x-text="activeLibraryItem.extracted_text?.length || 0"></strong></span>
                                                <button @click="saveLibraryItemUpdates()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                                                    <i class="fas fa-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!activeLibraryItem">
                                    <div class="h-96 border border-dashed border-slate-800 rounded-2xl flex flex-col items-center justify-center text-slate-500 gap-3">
                                        <i class="fas fa-folder-open text-3xl"></i>
                                        <p class="text-sm">Select a document on the left to view or edit its corpus text.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- ============================== QUESTION BANK ============================== -->
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
                                    <input type="text" x-model="qSearch" placeholder="Search questions…" class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-40 placeholder:text-slate-500">
                                </div>
                                <button @click="openQuestionCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                                    <i class="fas fa-plus"></i> Add Question
                                </button>
                            </div>
                        </div>

                        <!-- Question Modal (Bento) -->
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
                                            <input type="number" x-model="questionForm.marks" required min="1" class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1">Question Text</label>
                                        <textarea x-model="questionForm.question_text" required rows="3" class="block w-full rounded-xl border border-slate-700 p-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
                                    <!-- MCQ Options -->
                                    <div x-show="questionForm.type === 'MCQ'" class="space-y-2 p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                                        <label class="block text-xs font-semibold text-slate-400">MCQ Options — select the correct one</label>
                                        <template x-for="(opt, index) in questionForm.options" :key="index">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" :name="'mcq_correct'" :checked="opt.is_correct" @change="setMcqCorrect(index)" class="h-4 w-4 bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-xs text-slate-500 font-mono w-5" x-text="['A','B','C','D'][index]"></span>
                                                <input type="text" x-model="opt.option_text" :placeholder="`Option ${['A','B','C','D'][index]}`" class="flex-1 rounded-xl border-0 bg-slate-700 py-1.5 px-3 text-slate-100 text-xs focus:ring-1 focus:ring-indigo-500">
                                                <span x-show="opt.is_correct" class="text-[10px] text-emerald-400 font-black">✓ Correct</span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                                        <button type="button" @click="showQuestionModal = false" class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">Cancel</button>
                                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-xs font-semibold text-white rounded-xl transition flex items-center gap-2">
                                            <i class="fas fa-save"></i> Save Question
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Questions Table -->
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
                                                <span :class="q.type === 'MCQ' ? 'bg-indigo-500/20 text-indigo-300' : q.type === 'Short' ? 'bg-purple-500/20 text-purple-300' : 'bg-orange-500/20 text-orange-300'" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" x-text="q.type"></span>
                                            </td>
                                            <td class="px-6 py-3.5 text-xs max-w-xs" x-text="q.question_text.length > 80 ? q.question_text.substring(0,80) + '…' : q.question_text"></td>
                                            <td class="px-6 py-3.5 text-[10px] text-slate-400" x-text="`${q.board?.code || '—'} / Cl.${q.class_id} / ${q.subject?.name || '—'}`"></td>
                                            <td class="px-6 py-3.5">
                                                <span :class="q.difficulty === 'Easy' ? 'bg-emerald-500/20 text-emerald-300' : q.difficulty === 'Medium' ? 'bg-amber-500/20 text-amber-300' : 'bg-rose-500/20 text-rose-300'" class="px-2 py-0.5 rounded-full text-[10px] font-black" x-text="q.difficulty"></span>
                                            </td>
                                            <td class="px-6 py-3.5 text-xs font-mono text-slate-300" x-text="q.marks"></td>
                                            <td class="px-6 py-3.5">
                                                <div class="flex items-center justify-center">
                                                    <div class="inline-grid grid-cols-2 gap-1.5">
                                                        <button @click="editQuestion(q)" title="Edit" class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition"><i class="fas fa-pencil"></i></button>
                                                        <button @click="confirmDeleteQuestion(q)" title="Delete" class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition"><i class="fas fa-trash-alt"></i></button>
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

                    <!-- ============================== PAPER GENERATOR ============================== -->
                    <div x-show="currentView === 'papers'" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl h-fit">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i class="fas fa-file-alt text-indigo-400 text-xs"></i></div>
                                    <h3 class="text-sm font-bold text-indigo-400">Paper Criteria</h3>
                                </div>
                                <form @submit.prevent="generatePaper()" class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1">Exam Title</label>
                                        <input type="text" x-model="paperForm.title" required placeholder="E.g., Class 9 Midterm Exam" class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
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
                                        <label class="block text-xs font-semibold text-slate-400 mb-1">Chapters <span class="text-slate-600">(Ctrl+click to multi-select)</span></label>
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
                                            <input type="number" x-model="paperForm.total_marks" required class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center justify-center gap-2">
                                        <i class="fas fa-wand-magic-sparkles"></i> Generate Exam Paper
                                    </button>
                                </form>
                            </div>
                            <div class="lg:col-span-2 space-y-6">
                                <template x-if="generatedPaper">
                                    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-8 rounded-2xl shadow-xl space-y-8" id="paper-print-section">
                                        <div class="text-center border-b border-slate-800 pb-4 space-y-1">
                                            <h2 class="text-xl font-bold tracking-wider" x-text="generatedPaper.paper_structure_json.criteria.title"></h2>
                                            <p class="text-xs text-slate-400">Time Allowed: 3 Hours | Total Marks: <span x-text="generatedPaper.paper_structure_json.total_marks_reached"></span></p>
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
                                        <div class="border-t border-slate-800 pt-6 space-y-4">
                                            <h3 class="text-md font-bold text-emerald-400">Answer Key</h3>
                                            <div class="space-y-1 text-xs text-slate-300">
                                                <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                                                    <div>
                                                        <span x-text="`${index + 1}. `"></span>
                                                        <template x-if="q.type === 'MCQ'"><span class="font-semibold text-emerald-400" x-text="`Correct: ${getCorrectOptionLabel(q.options)}`"></span></template>
                                                        <template x-if="q.type !== 'MCQ'"><span class="text-slate-500">[Evaluated based on rubric]</span></template>
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

                    <!-- ============================== LOGS & REPORTS ============================== -->
                    <div x-show="currentView === 'logs'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-100">System Activity Logs</h3>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="`${filteredLogs().length} log entries`"></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <select x-model="logFilterAction" class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500">
                                    <option value="">All Actions</option>
                                    <option value="ingest">Ingestion</option>
                                    <option value="ocr">OCR</option>
                                    <option value="scrape">Scraping</option>
                                    <option value="question">Questions</option>
                                    <option value="paper">Papers</option>
                                </select>
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                                    <input type="text" x-model="logSearch" placeholder="Search logs…" class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
                                </div>
                                <button @click="exportLogsCSV()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-xl text-xs font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-download"></i> Export CSV
                                </button>
                            </div>
                        </div>
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/60 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-700">
                                    <tr>
                                        <th class="px-6 py-4">Timestamp</th>
                                        <th class="px-6 py-4">User</th>
                                        <th class="px-6 py-4">Action</th>
                                        <th class="px-6 py-4">Description</th>
                                        <th class="px-6 py-4">IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="log in filteredLogs()" :key="log.id">
                                        <tr class="border-b border-slate-800/60 hover:bg-slate-800/20 transition">
                                            <td class="px-6 py-3 text-xs text-slate-400 whitespace-nowrap" x-text="log.created_at"></td>
                                            <td class="px-6 py-3 text-xs font-medium" x-text="log.user?.name || 'System'"></td>
                                            <td class="px-6 py-3">
                                                <span :class="log.action.includes('success') || log.action.includes('create') ? 'bg-emerald-500/20 text-emerald-300' : log.action.includes('delete') || log.action.includes('fail') ? 'bg-rose-500/20 text-rose-300' : 'bg-slate-700 text-slate-300'" class="px-2 py-0.5 rounded-full text-[10px] font-black" x-text="log.action"></span>
                                            </td>
                                            <td class="px-6 py-3 text-xs text-slate-300 max-w-sm truncate" x-text="log.description"></td>
                                            <td class="px-6 py-3 text-[10px] text-slate-500 font-mono" x-text="log.ip_address"></td>
                                        </tr>
                                    </template>
                                    <template x-if="filteredLogs().length === 0">
                                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500 text-sm">No log entries match your filters.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </template>

    <!-- App State Alpine JS Controller -->
    <script>
        function appState() {
            return {
                // Auth
                token: localStorage.getItem('token') || '',
                user: null,
                currentView: 'dashboard',
                loginForm: { email: '', password: '' },
                loginError: '',

                // Toast
                toastMessage: '', toastType: 'success', toastVisible: false,
                showSuccess(msg) { this.toastMessage = msg; this.toastType = 'success'; this.toastVisible = true; setTimeout(() => this.toastVisible = false, 3500); },
                showError(msg)   { this.toastMessage = msg; this.toastType = 'error';   this.toastVisible = true; setTimeout(() => this.toastVisible = false, 4500); },

                // Confirm Modal
                showConfirmModal: false,
                confirmLoading: false,
                confirmConfig: { title: '', message: '', isDanger: false, action: null },

                // Academic data
                boards: [], subjects: [], chapters: [],
                classesList: Array.from({length: 12}, (_, i) => ({id: i + 1, name: `Class ${i + 1}`})),

                // Board UI
                showBoardModal: false,
                boardForm: { id: null, name: '', code: '' },
                boardSearch: '',

                // Subject UI
                subjectSearch: '', subjectFilterBoard: '',

                // Chapter UI
                showChapterModal: false,
                chapterForm: { id: null, board_id: '', subject_id: '', title: '', chapter_number: '' },
                chapterError: '',
                chapterSearch: '', chapterFilterBoard: '', chapterFilterSubject: '',

                // Ingestion
                uploading: false,
                uploadForm: { file: null, target_type: 'note', board_id: '', class_id: '', subject_id: '', chapter_id: '', title: '', run_ocr: false },
                extractedTextPreview: '',
                activeIngestedItem: null,
                scraping: false,
                scrapeForm: { url: '', target_type: 'note', board_id: '', class_id: '', subject_id: '', chapter_id: '', title: '' },

                // Jobs & Queue
                jobsData: { pending: [], failed: [], counts: { pending: 0, processing: 0, failed: 0 } },
                jobsRefreshInterval: null,

                // Question Bank
                questions: [],
                showQuestionModal: false,
                questionForm: { id: null, board_id: '', class_id: '', subject_id: '', chapter_id: '', type: 'MCQ', question_text: '', difficulty: 'Medium', marks: 1, language: 'English', options: [{option_text:'',is_correct:false},{option_text:'',is_correct:false},{option_text:'',is_correct:false},{option_text:'',is_correct:false}] },
                qSearch: '', qFilterBoard: '', qFilterSubject: '', qFilterType: '', qFilterDifficulty: '',

                // Paper
                paperForm: { title: '', board_id: '', class_id: '', subject_id: '', chapter_ids: [], difficulty: 'All', total_marks: 50 },
                generatedPaper: null,

                // Library
                libraryItems: [], activeLibraryItem: null,
                librarySearch: '', libraryFilterBoard: '', libraryFilterType: '',

                // Logs
                systemLogs: [],
                logSearch: '', logFilterAction: '',

                // ─── Init ─────────────────────────────────────────────────
                initApp() {
                    if (this.token) {
                        this.fetchUser();
                        this.fetchInitData();
                    }
                },

                async fetchUser() {
                    try { this.user = await this.apiCall('me'); } catch (e) { this.token = ''; localStorage.removeItem('token'); }
                },

                async fetchInitData() {
                    try {
                        [this.boards, this.subjects, this.chapters, this.questions] = await Promise.all([
                            this.apiCall('boards'), this.apiCall('subjects'), this.apiCall('chapters'), this.apiCall('questions')
                        ]);
                    } catch(e) { console.error('Init data fetch failed', e); }
                },

                // ─── Auth ─────────────────────────────────────────────────
                async login() {
                    this.loginError = '';
                    try {
                        const res = await fetch('/api/login', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(this.loginForm) });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Login failed');
                        this.token = data.token;
                        localStorage.setItem('token', data.token);
                        this.fetchUser(); this.fetchInitData();
                    } catch (e) { this.loginError = e.message; }
                },

                async logout() {
                    try { await this.apiCall('logout', 'POST'); } catch(e) {}
                    this.token = ''; localStorage.removeItem('token'); this.user = null;
                },

                // ─── API Helper ───────────────────────────────────────────
                async apiCall(endpoint, method = 'GET', body = null) {
                    const opts = { method, headers: { 'Authorization': 'Bearer ' + this.token, 'Content-Type': 'application/json', 'Accept': 'application/json' } };
                    if (body && method !== 'GET') opts.body = JSON.stringify(body);
                    const res = await fetch('/api/' + endpoint, opts);
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'API error');
                    return data;
                },

                // ─── Confirm Modal ────────────────────────────────────────
                async executeConfirmAction() {
                    if (typeof this.confirmConfig.action === 'function') {
                        this.confirmLoading = true;
                        await this.confirmConfig.action();
                        this.confirmLoading = false;
                        this.showConfirmModal = false;
                    }
                },

                // ─── Boards ───────────────────────────────────────────────
                async loadBoards() {
                    this.boards = await this.apiCall('boards');
                    this.currentView = 'boards';
                },
                editBoard(board) { this.boardForm = { ...board }; this.showBoardModal = true; },
                async saveBoard() {
                    try {
                        if (this.boardForm.id) {
                            const updated = await this.apiCall(`boards/${this.boardForm.id}`, 'PUT', this.boardForm);
                            this.boards = this.boards.map(b => b.id === updated.id ? updated : b);
                            this.showSuccess('Board updated successfully.');
                        } else {
                            const created = await this.apiCall('boards', 'POST', this.boardForm);
                            this.boards.push(created);
                            this.showSuccess('Board created successfully.');
                        }
                        this.showBoardModal = false;
                        this.boardForm = {id:null, name:'', code:''};
                    } catch(e) { this.showError(e.message); }
                },
                confirmDeleteItem(item, endpoint, arrayName, labelField = 'name') {
                    this.confirmConfig = {
                        title: 'Delete Record',
                        message: `Are you sure you want to permanently delete <strong>${item[labelField]}</strong>? This action cannot be undone.`,
                        isDanger: true,
                        action: async () => {
                            try {
                                await this.apiCall(`${endpoint}/${item.id}`, 'DELETE');
                                this[arrayName] = this[arrayName].filter(i => i.id !== item.id);
                                this.showSuccess(`${item[labelField]} deleted.`);
                            } catch(e) { this.showError(e.message); }
                        }
                    };
                    this.showConfirmModal = true;
                },

                // ─── Subjects ─────────────────────────────────────────────
                async loadSubjects() {
                    this.subjects = await this.apiCall('subjects');
                    this.currentView = 'subjects';
                },
                filteredSubjects() {
                    return this.subjects.filter(s =>
                        (this.subjectFilterBoard === '' || String(s.board_id) === String(this.subjectFilterBoard)) &&
                        s.name.toLowerCase().includes(this.subjectSearch.toLowerCase())
                    );
                },

                // ─── Chapters ─────────────────────────────────────────────
                async loadChapters() {
                    this.chapters = await this.apiCall('chapters');
                    this.currentView = 'chapters';
                },
                openChapterCreateModal() { this.chapterForm = {id:null, board_id:'', subject_id:'', title:'', chapter_number:''}; this.chapterError=''; this.showChapterModal=true; },
                editChapter(ch) { this.chapterForm = {...ch}; this.chapterError=''; this.showChapterModal=true; },
                async saveChapter() {
                    this.chapterError = '';
                    try {
                        if (this.chapterForm.id) {
                            const updated = await this.apiCall(`chapters/${this.chapterForm.id}`, 'PUT', this.chapterForm);
                            this.chapters = this.chapters.map(c => c.id === updated.id ? updated : c);
                            this.showSuccess('Chapter updated.');
                        } else {
                            const created = await this.apiCall('chapters', 'POST', this.chapterForm);
                            this.chapters.push(created);
                            this.showSuccess('Chapter created.');
                        }
                        this.showChapterModal = false;
                    } catch(e) { this.chapterError = e.message; }
                },
                confirmDeleteChapter(ch) {
                    this.confirmConfig = {
                        title: 'Delete Chapter',
                        message: `Permanently delete chapter <strong>${ch.title}</strong>?`,
                        isDanger: true,
                        action: async () => {
                            try {
                                await this.apiCall(`chapters/${ch.id}`, 'DELETE');
                                this.chapters = this.chapters.filter(c => c.id !== ch.id);
                                this.showSuccess('Chapter deleted.');
                            } catch(e) { this.showError(e.message); }
                        }
                    };
                    this.showConfirmModal = true;
                },
                filteredChapters() {
                    return this.chapters.filter(c =>
                        (this.chapterFilterBoard === '' || String(c.board_id) === String(this.chapterFilterBoard)) &&
                        (this.chapterFilterSubject === '' || String(c.subject_id) === String(this.chapterFilterSubject)) &&
                        c.title.toLowerCase().includes(this.chapterSearch.toLowerCase())
                    );
                },

                // ─── Ingestion ────────────────────────────────────────────
                openUploaderView() { this.currentView = 'uploader'; this.extractedTextPreview = ''; },
                async submitUpload() {
                    this.uploading = true;
                    try {
                        const formData = new FormData();
                        formData.append('file', this.uploadForm.file);
                        formData.append('target_type', this.uploadForm.target_type);
                        formData.append('board_id', this.uploadForm.board_id);
                        formData.append('class_id', this.uploadForm.class_id);
                        formData.append('subject_id', this.uploadForm.subject_id);
                        formData.append('chapter_id', this.uploadForm.chapter_id);
                        formData.append('title', this.uploadForm.title);
                        formData.append('run_ocr', this.uploadForm.run_ocr ? 'true' : 'false');
                        const res = await fetch('/api/ingest', { method: 'POST', headers: { 'Authorization': 'Bearer ' + this.token, 'Accept': 'application/json' }, body: formData });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Upload failed');
                        if (data.status === 'queued') {
                            this.showSuccess('OCR job queued. Check Jobs & Queue for live progress!');
                        } else {
                            this.extractedTextPreview = data.text || '';
                            this.activeIngestedItem = data.item;
                            this.showSuccess('Document ingested and text extracted successfully!');
                        }
                    } catch(e) { this.showError(e.message); }
                    this.uploading = false;
                },
                async openScraperView() { this.currentView = 'scraper'; },
                async submitScrape() {
                    this.scraping = true;
                    try {
                        const data = await this.apiCall('scrape', 'POST', this.scrapeForm);
                        this.showSuccess('Website scraped and indexed successfully!');
                    } catch(e) { this.showError(e.message); }
                    this.scraping = false;
                },

                // ─── Jobs & Queue ─────────────────────────────────────────
                async openJobsView() {
                    this.currentView = 'jobs';
                    // Also load logs for completed OCR display
                    await Promise.all([this.loadJobs(), this.openLogsView(false)]);
                    clearInterval(this.jobsRefreshInterval);
                    this.jobsRefreshInterval = setInterval(() => { if (this.currentView === 'jobs') this.loadJobs(); }, 10000);
                },
                async loadJobs() {
                    try { this.jobsData = await this.apiCall('jobs'); } catch(e) { console.error('Failed to load jobs', e); }
                },
                async retryJob(id) {
                    try { await this.apiCall(`jobs/failed/${id}/retry`, 'POST'); this.showSuccess('Job re-queued.'); await this.loadJobs(); } catch(e) { this.showError(e.message); }
                },
                async deleteFailedJob(id) {
                    try { await this.apiCall(`jobs/failed/${id}`, 'DELETE'); this.showSuccess('Failed job purged.'); this.jobsData.failed = this.jobsData.failed.filter(j => j.id !== id); this.jobsData.counts.failed = Math.max(0, (this.jobsData.counts.failed || 1) - 1); } catch(e) { this.showError(e.message); }
                },

                // ─── Library ──────────────────────────────────────────────
                async openLibraryView() {
                    this.currentView = 'library';
                    this.activeLibraryItem = null;
                    try {
                        const [notes, materials] = await Promise.all([this.apiCall('notes'), this.apiCall('materials')]);
                        this.libraryItems = [
                            ...notes.map(n => ({...n, type:'note', unique_id:`note_${n.id}`})),
                            ...materials.map(m => ({...m, type:'material', unique_id:`material_${m.id}`}))
                        ];
                    } catch(e) { this.showError('Failed to load library: ' + e.message); }
                },
                filteredLibraryItems() {
                    return this.libraryItems.filter(item =>
                        (this.libraryFilterType === '' || item.type === this.libraryFilterType) &&
                        (this.libraryFilterBoard === '' || String(item.board_id) === String(this.libraryFilterBoard)) &&
                        (item.title || '').toLowerCase().includes(this.librarySearch.toLowerCase())
                    );
                },
                selectLibraryItem(item) { this.activeLibraryItem = {...item}; },
                async saveLibraryItemUpdates() {
                    if (!this.activeLibraryItem) return;
                    const ep = this.activeLibraryItem.type === 'note' ? `notes/${this.activeLibraryItem.id}` : `materials/${this.activeLibraryItem.id}`;
                    try {
                        await this.apiCall(ep, 'PUT', {extracted_text: this.activeLibraryItem.extracted_text});
                        this.showSuccess('Document corpus updated successfully.');
                        await this.openLibraryView();
                    } catch(e) { this.showError(e.message); }
                },
                confirmDeleteLibraryItem(item) {
                    this.confirmConfig = {
                        title: 'Delete Document',
                        message: `Permanently delete <strong>${item.title}</strong> from the database?`,
                        isDanger: true,
                        action: async () => {
                            try {
                                const ep = item.type === 'note' ? `notes/${item.id}` : `materials/${item.id}`;
                                await this.apiCall(ep, 'DELETE');
                                this.libraryItems = this.libraryItems.filter(i => i.unique_id !== item.unique_id);
                                this.activeLibraryItem = null;
                                this.showSuccess('Document deleted.');
                            } catch(e) { this.showError(e.message); }
                        }
                    };
                    this.showConfirmModal = true;
                },

                // ─── Questions ────────────────────────────────────────────
                async loadQuestions() {
                    this.questions = await this.apiCall('questions');
                    this.currentView = 'questions';
                },
                filteredQuestions() {
                    return this.questions.filter(q =>
                        (this.qFilterBoard === '' || String(q.board_id) === String(this.qFilterBoard)) &&
                        (this.qFilterSubject === '' || String(q.subject_id) === String(this.qFilterSubject)) &&
                        (this.qFilterType === '' || q.type === this.qFilterType) &&
                        (this.qFilterDifficulty === '' || q.difficulty === this.qFilterDifficulty) &&
                        (q.question_text || '').toLowerCase().includes(this.qSearch.toLowerCase())
                    );
                },
                openQuestionCreateModal() {
                    this.questionForm = {id:null, board_id:'', class_id:'', subject_id:'', chapter_id:'', type:'MCQ', question_text:'', difficulty:'Medium', marks:1, language:'English', options:[{option_text:'',is_correct:false},{option_text:'',is_correct:false},{option_text:'',is_correct:false},{option_text:'',is_correct:false}]};
                    this.showQuestionModal = true;
                },
                editQuestion(q) { this.questionForm = {...q, options: q.options && q.options.length ? [...q.options] : [{option_text:'',is_correct:false},{option_text:'',is_correct:false},{option_text:'',is_correct:false},{option_text:'',is_correct:false}]}; this.showQuestionModal = true; },
                setMcqCorrect(index) { this.questionForm.options.forEach((o, i) => o.is_correct = (i === index)); },
                async saveQuestion() {
                    try {
                        if (this.questionForm.id) {
                            const updated = await this.apiCall(`questions/${this.questionForm.id}`, 'PUT', this.questionForm);
                            this.questions = this.questions.map(q => q.id === updated.id ? updated : q);
                            this.showSuccess('Question updated.');
                        } else {
                            const created = await this.apiCall('questions', 'POST', this.questionForm);
                            this.questions.push(created);
                            this.showSuccess('Question added to bank.');
                        }
                        this.showQuestionModal = false;
                    } catch(e) { this.showError(e.message); }
                },
                confirmDeleteQuestion(q) {
                    this.confirmConfig = {
                        title: 'Delete Question',
                        message: `Permanently delete this question: <em>"${q.question_text.substring(0,60)}…"</em>?`,
                        isDanger: true,
                        action: async () => {
                            try {
                                await this.apiCall(`questions/${q.id}`, 'DELETE');
                                this.questions = this.questions.filter(x => x.id !== q.id);
                                this.showSuccess('Question deleted.');
                            } catch(e) { this.showError(e.message); }
                        }
                    };
                    this.showConfirmModal = true;
                },

                // ─── Paper Generator ──────────────────────────────────────
                openPaperGeneratorView() { this.currentView = 'papers'; this.generatedPaper = null; },
                async generatePaper() {
                    try {
                        this.generatedPaper = await this.apiCall('generate-paper', 'POST', this.paperForm);
                        this.showSuccess('Paper generated successfully!');
                    } catch(e) { this.showError(e.message); }
                },
                getCorrectOptionLabel(options) {
                    const letters = ['A','B','C','D'];
                    const index = options.findIndex(opt => opt.is_correct);
                    return index !== -1 ? letters[index] : 'N/A';
                },

                // ─── Logs & Reports ───────────────────────────────────────
                async openLogsView(switchView = true) {
                    if (switchView) this.currentView = 'logs';
                    try { this.systemLogs = await this.apiCall('logs'); } catch(e) { console.error('Failed to load logs', e); }
                },
                filteredLogs() {
                    return this.systemLogs.filter(l =>
                        (this.logFilterAction === '' || l.action.includes(this.logFilterAction)) &&
                        ((l.description || '').toLowerCase().includes(this.logSearch.toLowerCase()) || (l.action || '').toLowerCase().includes(this.logSearch.toLowerCase()))
                    );
                },
                exportLogsCSV() {
                    const rows = [['Timestamp','User','Action','Description','IP']];
                    this.filteredLogs().forEach(l => rows.push([l.created_at, l.user?.name||'System', l.action, l.description, l.ip_address]));
                    const csv = rows.map(r => r.map(c => `"${(c||'').toString().replace(/"/g,'""')}"`).join(',')).join('\n');
                    const blob = new Blob([csv], {type:'text/csv'});
                    const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'iqra_logs.csv'; a.click();
                },
            };
        }
    </script>
</body>
</html>
