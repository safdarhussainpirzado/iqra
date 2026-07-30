<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQRA — Enterprise Educational Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body {
            background: radial-gradient(circle at top left, #1e1b4b, #0f172a, #020617);
        }
    </style>
</head>
<body class="h-full text-slate-100 antialiased selection:bg-indigo-500 selection:text-white" x-data="appState()" x-init="initApp()" x-cloak>

    <!-- Global Background Gradients -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute top-1/3 right-10 h-80 w-80 rounded-full bg-purple-500/10 blur-3xl"></div>
        <div class="absolute -bottom-20 left-1/3 h-96 w-96 rounded-full bg-pink-500/5 blur-3xl"></div>
    </div>

    <!-- Login screen -->
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
                            <div class="mt-1">
                                <input id="email" x-model="loginForm.email" type="email" required class="block w-full rounded-xl border-0 bg-slate-800/80 py-2.5 text-slate-100 shadow-sm ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                            <div class="mt-1">
                                <input id="password" x-model="loginForm.password" type="password" required class="block w-full rounded-xl border-0 bg-slate-800/80 py-2.5 text-slate-100 shadow-sm ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <template x-if="loginError">
                            <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400" x-text="loginError"></div>
                        </template>

                        <div>
                            <button type="submit" class="flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-200">
                                Sign In
                            </button>
                        </div>
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
                    <a href="#" @click.prevent="currentView = 'dashboard'" :class="currentView === 'dashboard' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Dashboard
                    </a>
                    <a href="#" @click.prevent="loadBoards()" :class="currentView === 'boards' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Boards
                    </a>
                    <a href="#" @click.prevent="loadSubjects()" :class="currentView === 'subjects' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Subjects
                    </a>
                    <a href="#" @click.prevent="loadChapters()" :class="currentView === 'chapters' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Chapters
                    </a>
                    <a href="#" @click.prevent="openUploaderView()" :class="currentView === 'uploader' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Ingestion & OCR
                    </a>
                    <a href="#" @click.prevent="openScraperView()" :class="currentView === 'scraper' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Web Importer
                    </a>
                    <a href="#" @click.prevent="loadQuestions()" :class="currentView === 'questions' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Question Bank
                    </a>
                    <a href="#" @click.prevent="openPaperGeneratorView()" :class="currentView === 'papers' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Paper Generator
                    </a>
                    <a href="#" @click.prevent="openLogsView()" :class="currentView === 'logs' ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-150">
                        Logs & Reports
                    </a>
                </nav>
                <div class="p-4 border-t border-slate-800 text-xs text-slate-500 flex-shrink-0">
                    Logged in as: <span class="text-slate-300 block font-medium mt-0.5" x-text="user?.email"></span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                <!-- Topbar -->
                <header class="h-16 backdrop-blur-md bg-slate-900/60 border-b border-slate-800 px-8 flex items-center justify-between flex-shrink-0">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-100 capitalize" x-text="currentView === 'papers' ? 'Question Paper Generator' : currentView"></h2>
                    <div class="flex items-center space-x-4">
                        <button @click="logout()" class="px-3.5 py-1.5 text-xs font-semibold text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 rounded-lg transition duration-150">
                            Sign Out
                        </button>
                    </div>
                </header>

                <!-- Main Content Panels -->
                <main class="flex-1 p-8">
                    <!-- Dashboard Panel -->
                    <div x-show="currentView === 'dashboard'" class="space-y-6">
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <span class="text-sm font-medium text-slate-400">Classes Supported</span>
                                <div class="text-3xl font-extrabold mt-2 text-indigo-400">12</div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <span class="text-sm font-medium text-slate-400">Total Boards</span>
                                <div class="text-3xl font-extrabold mt-2 text-purple-400" x-text="boards.length || 3"></div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <span class="text-sm font-medium text-slate-400">Subjects Indexed</span>
                                <div class="text-3xl font-extrabold mt-2 text-pink-400" x-text="subjects.length || 8"></div>
                            </div>
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
                                <span class="text-sm font-medium text-slate-400">Chapters Registered</span>
                                <div class="text-3xl font-extrabold mt-2 text-emerald-400" x-text="chapters.length || 12"></div>
                            </div>
                        </div>

                        <!-- System Configuration Panel -->
                        <div class="backdrop-blur-md bg-slate-900/40 border border-slate-800 p-6 rounded-2xl shadow-xl">
                            <h3 class="text-lg font-bold text-indigo-400">Platform Specifications</h3>
                            <p class="text-slate-300 text-sm mt-2">IQRA is running on local PHP 8.4 runtime with Redis queue workers ready for OCR and document ingestion processing.</p>
                            <div class="mt-4 flex space-x-2">
                                <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">PHP 8.4-FPM</span>
                                <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">Laravel Monolith</span>
                                <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">MySQL 8.0</span>
                                <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs rounded-full">Redis Cache</span>
                            </div>
                        </div>
                    </div>

                    <!-- Boards CRUD Panel -->
                    <div x-show="currentView === 'boards'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-100">Configured Boards</h3>
                            <button @click="showBoardModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                Add Board
                            </button>
                        </div>

                        <!-- Board Modal Form -->
                        <div x-show="showBoardModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                            <div class="backdrop-blur-md bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl">
                                <h3 class="text-lg font-bold mb-4" x-text="boardForm.id ? 'Edit Board' : 'Add Board'"></h3>
                                <form @submit.prevent="saveBoard()">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Board Name</label>
                                            <input type="text" x-model="boardForm.name" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Board Code</label>
                                            <input type="text" x-model="boardForm.code" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end space-x-2">
                                        <button type="button" @click="showBoardModal = false" class="px-4 py-2 text-xs font-medium text-slate-400 hover:text-white">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold rounded-xl">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Boards Datatable -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/40 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-850">
                                    <tr>
                                        <th class="px-6 py-4">ID</th>
                                        <th class="px-6 py-4">Name</th>
                                        <th class="px-6 py-4">Code</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="board in boards" :key="board.id">
                                        <tr class="border-b border-slate-850 hover:bg-slate-800/20">
                                            <td class="px-6 py-4" x-text="board.id"></td>
                                            <td class="px-6 py-4 font-medium" x-text="board.name"></td>
                                            <td class="px-6 py-4" x-text="board.code"></td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <button @click="editBoard(board)" class="text-xs text-indigo-400 hover:text-indigo-300">Edit</button>
                                                <button @click="deleteBoard(board.id)" class="text-xs text-red-400 hover:text-red-300">Delete</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Subjects List Panel -->
                    <div x-show="currentView === 'subjects'" class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-100">Registered Subjects</h3>
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/40 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-850">
                                    <tr>
                                        <th class="px-6 py-4">Subject Name</th>
                                        <th class="px-6 py-4">Subject Code</th>
                                        <th class="px-6 py-4">Class Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="subj in subjects" :key="subj.id">
                                        <tr class="border-b border-slate-850 hover:bg-slate-800/20">
                                            <td class="px-6 py-4 font-medium" x-text="subj.name"></td>
                                            <td class="px-6 py-4" x-text="subj.code"></td>
                                            <td class="px-6 py-4" x-text="subj.class?.name || 'Class 9'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Chapters CRUD Panel -->
                    <div x-show="currentView === 'chapters'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-100">Manage Chapters</h3>
                            <button @click="openChapterCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                Add Chapter
                            </button>
                        </div>

                        <!-- Chapter Modal -->
                        <div x-show="showChapterModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                            <div class="backdrop-blur-md bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl">
                                <h3 class="text-lg font-bold mb-4" x-text="chapterForm.id ? 'Edit Chapter' : 'Add Chapter'"></h3>
                                <form @submit.prevent="saveChapter()">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Board</label>
                                            <select x-model="chapterForm.board_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                                                <option value="">Select Board</option>
                                                <template x-for="board in boards" :key="board.id">
                                                    <option :value="board.id" x-text="board.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Subject</label>
                                            <select x-model="chapterForm.subject_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                                                <option value="">Select Subject</option>
                                                <template x-for="subj in subjects" :key="subj.id">
                                                    <option :value="subj.id" x-text="subj.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Chapter Title</label>
                                            <input type="text" x-model="chapterForm.title" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Chapter Number</label>
                                            <input type="number" x-model="chapterForm.chapter_number" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                    <template x-if="chapterError">
                                        <div class="mt-3 rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400" x-text="chapterError"></div>
                                    </template>
                                    <div class="mt-6 flex justify-end space-x-2">
                                        <button type="button" @click="showChapterModal = false" class="px-4 py-2 text-xs font-medium text-slate-400 hover:text-white">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold rounded-xl">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Chapters list -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/40 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-850">
                                    <tr>
                                        <th class="px-6 py-4">Ch No.</th>
                                        <th class="px-6 py-4">Title</th>
                                        <th class="px-6 py-4">Subject</th>
                                        <th class="px-6 py-4">Board</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="ch in chapters" :key="ch.id">
                                        <tr class="border-b border-slate-850 hover:bg-slate-800/20">
                                            <td class="px-6 py-4" x-text="ch.chapter_number"></td>
                                            <td class="px-6 py-4 font-medium" x-text="ch.title"></td>
                                            <td class="px-6 py-4" x-text="ch.subject?.name"></td>
                                            <td class="px-6 py-4" x-text="ch.board?.name"></td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <button @click="editChapter(ch)" class="text-xs text-indigo-400 hover:text-indigo-300">Edit</button>
                                                <button @click="deleteChapter(ch.id)" class="text-xs text-red-400 hover:text-red-300">Delete</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ingestion / Uploader Panel -->
                    <div x-show="currentView === 'uploader'" class="space-y-6">
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-6">
                            <h3 class="text-lg font-semibold text-indigo-400">Document Ingestion & Text Corpus Extractor</h3>
                            
                            <form @submit.prevent="submitUpload()" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Classification Level</label>
                                        <div class="grid grid-cols-2 gap-2 mt-1">
                                            <select x-model="uploadForm.board_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Board</option>
                                                <template x-for="board in boards" :key="board.id">
                                                    <option :value="board.id" x-text="board.name"></option>
                                                </template>
                                            </select>
                                            <select x-model="uploadForm.class_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Class</option>
                                                <template x-for="cls in classesList" :key="cls.id">
                                                    <option :value="cls.id" x-text="cls.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Subject</label>
                                            <select x-model="uploadForm.subject_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Subject</option>
                                                <template x-for="subj in subjects" :key="subj.id">
                                                    <option :value="subj.id" x-text="subj.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Chapter</label>
                                            <select x-model="uploadForm.chapter_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Chapter</option>
                                                <template x-for="ch in chapters" :key="ch.id">
                                                    <option :value="ch.id" x-text="ch.title"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Target Category</label>
                                            <select x-model="uploadForm.target_type" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="note">Notes</option>
                                                <option value="material">Books/Material</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Document Title</label>
                                            <input type="text" x-model="uploadForm.title" required placeholder="E.g., Physics Notes Unit 1" class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 flex flex-col justify-between">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Choose File (.pdf, .doc, .docx, .txt, .csv, .xlsx, .json, .html)</label>
                                        <input type="file" @change="uploadForm.file = $event.target.files[0]" required class="mt-1 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700">
                                    </div>
                                    <div class="flex items-center space-x-3 bg-slate-800/40 p-3 rounded-xl border border-slate-700/50">
                                        <input id="run-ocr" type="checkbox" x-model="uploadForm.run_ocr" class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                                        <label for="run-ocr" class="text-xs text-slate-300">Run Optical Character Recognition (OCR) for scanned PDFs</label>
                                    </div>
                                    <button type="submit" :disabled="uploading" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                        <span x-text="uploading ? 'Extracting Text & Saving...' : 'Upload & Process'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Extracted text preview & Edit Panel -->
                        <div x-show="extractedTextPreview" class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h4 class="text-sm font-semibold text-emerald-400">Extracted Preview (Direct Corpus Database Storage)</h4>
                                <span class="text-xs text-slate-400">Length: <span x-text="extractedTextPreview.length"></span> characters</span>
                            </div>
                            <textarea x-model="extractedTextPreview" rows="12" class="w-full rounded-xl border-0 bg-slate-850 p-4 text-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500"></textarea>
                            <div class="flex justify-end">
                                <button @click="updateIngestedText()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                    Save Edited Text
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Web Importer / Scraper Panel -->
                    <div x-show="currentView === 'scraper'" class="space-y-6">
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-6">
                            <h3 class="text-lg font-semibold text-indigo-400">Website Scraping Importer</h3>
                            
                            <form @submit.prevent="submitScrape()" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <select x-model="scrapeForm.board_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Board</option>
                                        <template x-for="board in boards" :key="board.id">
                                            <option :value="board.id" x-text="board.name"></option>
                                        </template>
                                    </select>
                                    <select x-model="scrapeForm.class_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Class</option>
                                        <template x-for="cls in classesList" :key="cls.id">
                                            <option :value="cls.id" x-text="cls.name"></option>
                                        </template>
                                    </select>
                                    <select x-model="scrapeForm.subject_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Subject</option>
                                        <template x-for="subj in subjects" :key="subj.id">
                                            <option :value="subj.id" x-text="subj.name"></option>
                                        </template>
                                    </select>
                                    <select x-model="scrapeForm.chapter_id" required class="rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        <option value="">Select Chapter</option>
                                        <template x-for="ch in chapters" :key="ch.id">
                                            <option :value="ch.id" x-text="ch.title"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <input type="url" x-model="scrapeForm.url" required placeholder="Enter Scrape Target URL (E.g., https://example-education.com/notes-1)" class="w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                    </div>
                                    <div>
                                        <input type="text" x-model="scrapeForm.title" required placeholder="Scraped Document Title" class="w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" :disabled="scraping" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                        <span x-text="scraping ? 'Scraping Page...' : 'Scrape Website'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Question Bank CRUD Panel -->
                    <div x-show="currentView === 'questions'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-100">Centralized Question Bank</h3>
                            <button @click="openQuestionCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                Add Question
                            </button>
                        </div>

                        <!-- Question Modal -->
                        <div x-show="showQuestionModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                            <div class="backdrop-blur-md bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-2xl shadow-2xl">
                                <h3 class="text-lg font-bold mb-4" x-text="questionForm.id ? 'Edit Question' : 'Add Question'"></h3>
                                <form @submit.prevent="saveQuestion()">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Board</label>
                                            <select x-model="questionForm.board_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Board</option>
                                                <template x-for="board in boards" :key="board.id">
                                                    <option :value="board.id" x-text="board.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Class</label>
                                            <select x-model="questionForm.class_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Class</option>
                                                <template x-for="cls in classesList" :key="cls.id">
                                                    <option :value="cls.id" x-text="cls.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Subject</label>
                                            <select x-model="questionForm.subject_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Subject</option>
                                                <template x-for="subj in subjects" :key="subj.id">
                                                    <option :value="subj.id" x-text="subj.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Chapter</label>
                                            <select x-model="questionForm.chapter_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="">Select Chapter</option>
                                                <template x-for="ch in chapters" :key="ch.id">
                                                    <option :value="ch.id" x-text="ch.title"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Language</label>
                                            <select x-model="questionForm.language" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="English">English</option>
                                                <option value="Urdu">Urdu</option>
                                                <option value="Sindhi">Sindhi</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Difficulty</label>
                                            <select x-model="questionForm.difficulty" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="Easy">Easy</option>
                                                <option value="Medium">Medium</option>
                                                <option value="Hard">Hard</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Question Type</label>
                                            <select x-model="questionForm.type" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                                <option value="MCQ">MCQ</option>
                                                <option value="Short">Short Question</option>
                                                <option value="Long">Long Question</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Marks</label>
                                            <input type="number" x-model="questionForm.marks" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-xs font-medium text-slate-400">Question Text</label>
                                        <textarea x-model="questionForm.question_text" required rows="3" class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs"></textarea>
                                    </div>

                                    <!-- MCQ Options Panel -->
                                    <div x-show="questionForm.type === 'MCQ'" class="mt-4 space-y-2">
                                        <label class="block text-xs font-medium text-slate-400">MCQ Options (Select the correct one)</label>
                                        <template x-for="(opt, index) in questionForm.options" :key="index">
                                            <div class="flex items-center space-x-2">
                                                <input type="radio" :name="'mcq_correct'" :checked="opt.is_correct" @change="setMcqCorrect(index)" class="h-4 w-4 bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                                                <input type="text" x-model="opt.option_text" placeholder="Enter option text" required class="flex-1 rounded-xl border-0 bg-slate-800 py-1.5 px-3 text-slate-100 text-xs">
                                            </div>
                                        </template>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-2">
                                        <button type="button" @click="showQuestionModal = false" class="px-4 py-2 text-xs font-medium text-slate-400 hover:text-white">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold rounded-xl">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Questions list -->
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/40 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-850">
                                    <tr>
                                        <th class="px-6 py-4">Type</th>
                                        <th class="px-6 py-4">Question</th>
                                        <th class="px-6 py-4">Board/Class/Subject</th>
                                        <th class="px-6 py-4">Marks</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="q in questions" :key="q.id">
                                        <tr class="border-b border-slate-850 hover:bg-slate-800/20">
                                            <td class="px-6 py-4"><span :class="q.type === 'MCQ' ? 'bg-indigo-500/20 text-indigo-300' : 'bg-purple-500/20 text-purple-300'" class="px-2 py-0.5 rounded-full text-xs font-medium" x-text="q.type"></span></td>
                                            <td class="px-6 py-4 font-medium" x-text="q.question_text"></td>
                                            <td class="px-6 py-4 text-xs text-slate-400" x-text="`${q.board?.code} / Class ${q.class_id} / ${q.subject?.name}`"></td>
                                            <td class="px-6 py-4" x-text="q.marks"></td>
                                            <td class="px-6 py-4 text-right">
                                                <button @click="deleteQuestion(q.id)" class="text-xs text-red-400 hover:text-red-300">Delete</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paper Generator Workspace Panel -->
                    <div x-show="currentView === 'papers'" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Criteria Selectors Card -->
                            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl h-fit">
                                <h3 class="text-sm font-semibold text-indigo-400 mb-4">Paper Criteria Synthesis</h3>
                                <form @submit.prevent="generatePaper()" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Exam Title</label>
                                        <input type="text" x-model="paperForm.title" required placeholder="E.g., Class 9 Midterm Exam" class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Board</label>
                                        <select x-model="paperForm.board_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                            <option value="">Select Board</option>
                                            <template x-for="board in boards" :key="board.id">
                                                <option :value="board.id" x-text="board.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Class</label>
                                        <select x-model="paperForm.class_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                            <option value="">Select Class</option>
                                            <template x-for="cls in classesList" :key="cls.id">
                                                <option :value="cls.id" x-text="cls.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Subject</label>
                                        <select x-model="paperForm.subject_id" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                            <option value="">Select Subject</option>
                                            <template x-for="subj in subjects" :key="subj.id">
                                                <option :value="subj.id" x-text="subj.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Select Chapters (Hold Ctrl to select multiple)</label>
                                        <select x-model="paperForm.chapter_ids" multiple required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs h-24">
                                            <template x-for="ch in chapters" :key="ch.id">
                                                <option :value="ch.id" x-text="ch.title"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Difficulty</label>
                                            <select x-model="paperForm.difficulty" class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                                <option value="All">All</option>
                                                <option value="Easy">Easy</option>
                                                <option value="Medium">Medium</option>
                                                <option value="Hard">Hard</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-400">Total Marks</label>
                                            <input type="number" x-model="paperForm.total_marks" required class="mt-1 block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 text-xs">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition duration-150">
                                        Generate Exam Paper
                                    </button>
                                </form>
                            </div>

                            <!-- Generated Paper Preview Pane -->
                            <div class="lg:col-span-2 space-y-6">
                                <template x-if="generatedPaper">
                                    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-8 rounded-2xl shadow-xl space-y-8" id="paper-print-section">
                                        <!-- Paper Header -->
                                        <div class="text-center border-b border-slate-800 pb-4 space-y-1">
                                            <h2 class="text-xl font-bold tracking-wider" x-text="generatedPaper.paper_structure_json.criteria.title"></h2>
                                            <p class="text-xs text-slate-400">Time Allowed: 3 Hours | Total Marks: <span x-text="generatedPaper.paper_structure_json.total_marks_reached"></span></p>
                                        </div>

                                        <!-- Questions List -->
                                        <div class="space-y-6">
                                            <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                                                <div class="space-y-2">
                                                    <div class="flex justify-between text-sm">
                                                        <span class="font-medium" x-text="`${index + 1}. ${q.question_text}`"></span>
                                                        <span class="text-slate-400 text-xs" x-text="`[Marks: ${q.marks}]`"></span>
                                                    </div>
                                                    <!-- MCQ Options list -->
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

                                        <!-- Answer Key Box -->
                                        <div class="border-t border-slate-800 pt-6 space-y-4">
                                            <h3 class="text-md font-bold text-emerald-400">Answer Key (For Teachers)</h3>
                                            <div class="space-y-2 text-xs text-slate-300">
                                                <template x-for="(q, index) in generatedPaper.paper_structure_json.questions" :key="q.id">
                                                    <div>
                                                        <span x-text="`${index + 1}. `"></span>
                                                        <template x-if="q.type === 'MCQ'">
                                                            <span class="font-semibold text-emerald-400" x-text="`Correct Option: ${getCorrectOptionLabel(q.options)}`"></span>
                                                        </template>
                                                        <template x-if="q.type !== 'MCQ'">
                                                            <span class="text-slate-400">[Evaluated based on standard rubric]</span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!generatedPaper">
                                    <div class="h-64 border border-dashed border-slate-800 rounded-2xl flex items-center justify-center text-slate-500 text-sm">
                                        Generate a question paper to view its preview and key details here.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Logs & Reports Panel -->
                    <div x-show="currentView === 'logs'" class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-100">System Logs & Activities</h3>
                        <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-800/40 text-xs text-slate-400 uppercase tracking-wider border-b border-slate-850">
                                    <tr>
                                        <th class="px-6 py-4">Timestamp</th>
                                        <th class="px-6 py-4">User</th>
                                        <th class="px-6 py-4">Action</th>
                                        <th class="px-6 py-4">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="log in systemLogs" :key="log.id">
                                        <tr class="border-b border-slate-850 hover:bg-slate-800/20">
                                            <td class="px-6 py-4 text-xs text-slate-400" x-text="log.created_at"></td>
                                            <td class="px-6 py-4 font-medium text-xs" x-text="log.user?.name || 'System'"></td>
                                            <td class="px-6 py-4"><span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-800 border border-slate-700" x-text="log.action"></span></td>
                                            <td class="px-6 py-4 text-xs text-slate-300" x-text="log.description"></td>
                                        </tr>
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
                token: localStorage.getItem('token') || '',
                user: null,
                currentView: 'dashboard',
                loginForm: { email: '', password: '' },
                loginError: '',
                
                // Academics
                boards: [],
                subjects: [],
                chapters: [],
                classesList: Array.from({length: 12}, (_, i) => ({id: i + 1, name: `Class ${i + 1}`})),
                showBoardModal: false,
                boardForm: { id: null, name: '', code: '' },
                showChapterModal: false,
                chapterForm: { id: null, board_id: '', subject_id: '', title: '', chapter_number: '' },
                chapterError: '',

                // Ingestion & Scraper
                uploading: false,
                uploadForm: { file: null, target_type: 'note', board_id: '', class_id: '', subject_id: '', chapter_id: '', title: '', run_ocr: false },
                extractedTextPreview: '',
                activeIngestedItem: null,
                scraping: false,
                scrapeForm: { url: '', target_type: 'note', board_id: '', class_id: '', subject_id: '', chapter_id: '', title: '' },

                // Question Bank
                questions: [],
                showQuestionModal: false,
                questionForm: { id: null, board_id: '', class_id: '', subject_id: '', chapter_id: '', type: 'MCQ', question_text: '', difficulty: 'Medium', marks: 1, language: 'English', options: [{ option_text: '', is_correct: false }, { option_text: '', is_correct: false }, { option_text: '', is_correct: false }, { option_text: '', is_correct: false }] },

                // Paper Gen
                paperForm: { title: '', board_id: '', class_id: '', subject_id: '', chapter_ids: [], difficulty: 'All', total_marks: 50 },
                generatedPaper: null,
                systemLogs: [],

                initApp() {
                    if (this.token) {
                        this.fetchUser();
                        this.fetchInitData();
                    }
                },

                async apiCall(endpoint, method = 'GET', data = null, isMultipart = false) {
                    const headers = {
                        'Accept': 'application/json',
                    };
                    if (!isMultipart) {
                        headers['Content-Type'] = 'application/json';
                    }
                    if (this.token) {
                        headers['Authorization'] = `Bearer ${this.token}`;
                    }
                    
                    const options = { method, headers };
                    if (data) {
                        options.body = isMultipart ? data : JSON.stringify(data);
                    }
                    
                    const res = await fetch(`/api/${endpoint}`, options);
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || 'API request failed');
                    }
                    return res.json();
                },

                async login() {
                    this.loginError = '';
                    try {
                        const data = await this.apiCall('login', 'POST', this.loginForm);
                        this.token = data.access_token;
                        localStorage.setItem('token', this.token);
                        this.user = data.user;
                        this.fetchInitData();
                        this.currentView = 'dashboard';
                    } catch (e) {
                        this.loginError = e.message;
                    }
                },

                logout() {
                    this.apiCall('logout', 'POST').catch(() => {});
                    this.token = '';
                    localStorage.removeItem('token');
                    this.user = null;
                    this.currentView = 'login';
                },

                async fetchUser() {
                    try {
                        this.user = await this.apiCall('me');
                    } catch (e) {
                        this.logout();
                    }
                },

                fetchInitData() {
                    this.loadBoards();
                    this.loadSubjects();
                    this.loadChapters();
                },

                async loadBoards() {
                    try {
                        this.boards = await this.apiCall('boards');
                    } catch (e) {
                        console.error('Failed to load boards', e);
                    }
                },

                async saveBoard() {
                    try {
                        if (this.boardForm.id) {
                            await this.apiCall(`boards/${this.boardForm.id}`, 'PUT', this.boardForm);
                        } else {
                            await this.apiCall('boards', 'POST', this.boardForm);
                        }
                        this.showBoardModal = false;
                        this.boardForm = { id: null, name: '', code: '' };
                        this.loadBoards();
                    } catch (e) {
                        alert(e.message);
                    }
                },

                editBoard(board) {
                    this.boardForm = { ...board };
                    this.showBoardModal = true;
                },

                async deleteBoard(id) {
                    if (confirm('Are you sure you want to delete this board?')) {
                        try {
                            await this.apiCall(`boards/${id}`, 'DELETE');
                            this.loadBoards();
                        } catch (e) {
                            alert(e.message);
                        }
                    }
                },

                async loadSubjects() {
                    try {
                        this.subjects = await this.apiCall('subjects');
                    } catch (e) {
                        console.error('Failed to load subjects', e);
                    }
                },

                async loadChapters() {
                    try {
                        this.chapters = await this.apiCall('chapters');
                    } catch (e) {
                        console.error('Failed to load chapters', e);
                    }
                },

                openChapterCreateModal() {
                    this.chapterForm = { id: null, board_id: '', subject_id: '', title: '', chapter_number: '' };
                    this.chapterError = '';
                    this.showChapterModal = true;
                },

                async saveChapter() {
                    this.chapterError = '';
                    try {
                        if (this.chapterForm.id) {
                            await this.apiCall(`chapters/${this.chapterForm.id}`, 'PUT', this.chapterForm);
                        } else {
                            await this.apiCall('chapters', 'POST', this.chapterForm);
                        }
                        this.showChapterModal = false;
                        this.loadChapters();
                    } catch (e) {
                        this.chapterError = e.message;
                    }
                },

                editChapter(ch) {
                    this.chapterForm = { ...ch };
                    this.chapterError = '';
                    this.showChapterModal = true;
                },

                async deleteChapter(id) {
                    if (confirm('Are you sure you want to delete this chapter?')) {
                        try {
                            await this.apiCall(`chapters/${id}`, 'DELETE');
                            this.loadChapters();
                        } catch (e) {
                            alert(e.message);
                        }
                    }
                },

                // Ingestion & Uploader Tab
                openUploaderView() {
                    this.currentView = 'uploader';
                    this.extractedTextPreview = '';
                    this.activeIngestedItem = null;
                },

                async submitUpload() {
                    this.uploading = true;
                    this.extractedTextPreview = '';
                    const formData = new FormData();
                    formData.append('file', this.uploadForm.file);
                    formData.append('target_type', this.uploadForm.target_type);
                    formData.append('board_id', this.uploadForm.board_id);
                    formData.append('class_id', this.uploadForm.class_id);
                    formData.append('subject_id', this.uploadForm.subject_id);
                    formData.append('chapter_id', this.uploadForm.chapter_id);
                    formData.append('title', this.uploadForm.title);
                    formData.append('run_ocr', this.uploadForm.run_ocr ? 'true' : 'false');

                    try {
                        const data = await this.apiCall('ingest', 'POST', formData, true);
                        this.uploading = false;
                        if (data.status === 'queued') {
                            alert(data.message);
                        } else {
                            this.extractedTextPreview = data.text;
                            this.activeIngestedItem = { id: data.item.id, type: this.uploadForm.target_type };
                        }
                    } catch (e) {
                        this.uploading = false;
                        alert(e.message);
                    }
                },

                async updateIngestedText() {
                    if (!this.activeIngestedItem) return;
                    const endpoint = this.activeIngestedItem.type === 'note' ? `notes/${this.activeIngestedItem.id}` : `materials/${this.activeIngestedItem.id}`;
                    try {
                        await this.apiCall(endpoint, 'PUT', { extracted_text: this.extractedTextPreview });
                        alert('Text corpus successfully updated in database.');
                    } catch (e) {
                        alert(e.message);
                    }
                },

                // Web Scraper Tab
                openScraperView() {
                    this.currentView = 'scraper';
                },

                async submitScrape() {
                    this.scraping = true;
                    try {
                        const data = await this.apiCall('scrape', 'POST', this.scrapeForm);
                        this.scraping = false;
                        alert(data.message);
                    } catch (e) {
                        this.scraping = false;
                        alert(e.message);
                    }
                },

                // Question Bank Tab
                async loadQuestions() {
                    try {
                        this.questions = await this.apiCall('questions');
                        this.currentView = 'questions';
                    } catch (e) {
                        console.error('Failed to load questions', e);
                    }
                },

                openQuestionCreateModal() {
                    this.questionForm = { id: null, board_id: '', class_id: '', subject_id: '', chapter_id: '', type: 'MCQ', question_text: '', difficulty: 'Medium', marks: 1, language: 'English', options: [{ option_text: '', is_correct: false }, { option_text: '', is_correct: false }, { option_text: '', is_correct: false }, { option_text: '', is_correct: false }] };
                    this.showQuestionModal = true;
                },

                setMcqCorrect(index) {
                    this.questionForm.options.forEach((opt, i) => {
                        opt.is_correct = (i === index);
                    });
                },

                async saveQuestion() {
                    try {
                        await this.apiCall('questions', 'POST', this.questionForm);
                        this.showQuestionModal = false;
                        this.loadQuestions();
                    } catch (e) {
                        alert(e.message);
                    }
                },

                async deleteQuestion(id) {
                    if (confirm('Are you sure you want to delete this question?')) {
                        try {
                            await this.apiCall(`questions/${id}`, 'DELETE');
                            this.loadQuestions();
                        } catch (e) {
                            alert(e.message);
                        }
                    }
                },

                // Paper Generator Tab
                openPaperGeneratorView() {
                    this.currentView = 'papers';
                    this.generatedPaper = null;
                },

                async generatePaper() {
                    try {
                        this.generatedPaper = await this.apiCall('generate-paper', 'POST', this.paperForm);
                    } catch (e) {
                        alert(e.message);
                    }
                },

                getCorrectOptionLabel(options) {
                    const letters = ['A', 'B', 'C', 'D'];
                    const index = options.findIndex(opt => opt.is_correct);
                    return index !== -1 ? letters[index] : 'N/A';
                },

                async openLogsView() {
                    this.currentView = 'logs';
                    try {
                        this.systemLogs = await this.apiCall('logs');
                    } catch (e) {
                        console.error('Failed to load activity logs', e);
                    }
                }
            };
        }
    </script>
</body>
</html>
