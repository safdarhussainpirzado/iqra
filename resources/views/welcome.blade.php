<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQRA — Enterprise Educational Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Local fallback styling if tailwind v4 is building -->
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
                <nav class="flex-1 px-4 py-6 space-y-1">
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
                </nav>
                <div class="p-4 border-t border-slate-800 text-xs text-slate-500">
                    Logged in as: <span class="text-slate-300 block font-medium mt-0.5" x-text="user?.email"></span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                <!-- Topbar -->
                <header class="h-16 backdrop-blur-md bg-slate-900/60 border-b border-slate-800 px-8 flex items-center justify-between">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-100 capitalize" x-text="currentView"></h2>
                    <div class="flex items-center space-x-4">
                        <button @click="logout()" class="px-3.5 py-1.5 text-xs font-semibold text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 rounded-lg transition duration-150">
                            Sign Out
                        </button>
                    </div>
                </header>

                <!-- Dashboard Content Panels -->
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
                boards: [],
                subjects: [],
                chapters: [],
                showBoardModal: false,
                boardForm: { id: null, name: '', code: '' },
                showChapterModal: false,
                chapterForm: { id: null, board_id: '', subject_id: '', title: '', chapter_number: '' },
                chapterError: '',

                initApp() {
                    if (this.token) {
                        this.fetchUser();
                        this.fetchInitData();
                    }
                },

                async apiCall(endpoint, method = 'GET', data = null) {
                    const headers = {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    };
                    if (this.token) {
                        headers['Authorization'] = `Bearer ${this.token}`;
                    }
                    const options = { method, headers };
                    if (data) {
                        options.body = JSON.stringify(data);
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
                        this.currentView = 'boards';
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
                        this.currentView = 'subjects';
                    } catch (e) {
                        console.error('Failed to load subjects', e);
                    }
                },

                async loadChapters() {
                    try {
                        this.chapters = await this.apiCall('chapters');
                        this.currentView = 'chapters';
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
                }
            };
        }
    </script>
</body>
</html>
