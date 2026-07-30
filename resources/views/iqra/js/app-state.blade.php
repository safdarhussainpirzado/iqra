{{--
    IQRA App State — Alpine.js Controller
    ──────────────────────────────────────
    This is the single source of truth for all client-side state.
    It is included ONCE at the bottom of welcome.blade.php and
    referenced by every panel via x-data="appState()" on <body>.

    Sections:
        1. State declarations
        2. Init
        3. Auth
        4. API helper
        5. Confirm modal
        6. Boards
        7. Subjects
        8. Chapters
        9. Ingestion / Scraper
       10. Jobs & Queue
       11. Library
       12. Questions
       13. Paper Generator
       14. Logs & Reports
--}}
<script>
    // ─── Safe localStorage wrapper (guards against Edge Tracking Prevention) ───
    const store = {
        get(key)        { try { return localStorage.getItem(key); }   catch(e) { return null; } },
        set(key, value) { try { localStorage.setItem(key, value); }    catch(e) {} },
        del(key)        { try { localStorage.removeItem(key); }        catch(e) {} },
    };
    function appState() {
        return {
            // ─── 1. Auth & Navigation ─────────────────────────────────────
            token:       store.get('token') || '',
            user:        null,
            currentView: 'dashboard',
            loginForm:   { email: '', password: '' },
            loginError:  '',

            // ─── Toast ────────────────────────────────────────────────────
            toastMessage: '', toastType: 'success', toastVisible: false,
            showSuccess(msg) { this.toastMessage = msg; this.toastType = 'success'; this.toastVisible = true; setTimeout(() => this.toastVisible = false, 3500); },
            showError(msg)   { this.toastMessage = msg; this.toastType = 'error';   this.toastVisible = true; setTimeout(() => this.toastVisible = false, 4500); },

            // ─── Confirm Modal ────────────────────────────────────────────
            showConfirmModal: false,
            confirmLoading:   false,
            confirmConfig:    { title: '', message: '', isDanger: false, action: null },

            // ─── 2. Academic Data ─────────────────────────────────────────
            boards:    [],
            subjects:  [],
            chapters:  [],
            classesList: Array.from({ length: 12 }, (_, i) => ({ id: i + 1, name: `Class ${i + 1}` })),

            // ─── Board UI ─────────────────────────────────────────────────
            showBoardModal: false,
            boardForm:      { id: null, name: '', code: '' },
            boardSearch:    '',

            // ─── Subject UI ───────────────────────────────────────────────
            subjectSearch:      '',
            subjectFilterBoard: '',

            // ─── Chapter UI ───────────────────────────────────────────────
            showChapterModal:    false,
            chapterForm:         { id: null, board_id: '', subject_id: '', title: '', chapter_number: '' },
            chapterError:        '',
            chapterSearch:       '',
            chapterFilterBoard:  '',
            chapterFilterSubject: '',

            // ─── Ingestion ────────────────────────────────────────────────
            uploading:            false,
            uploadForm:           { file: null, target_type: 'note', board_id: '', class_id: '', subject_id: '', chapter_id: '', title: '', run_ocr: false },
            extractedTextPreview: '',
            activeIngestedItem:   null,
            scraping:             false,
            scrapeForm:           { url: '', target_type: 'note', board_id: '', class_id: '', subject_id: '', chapter_id: '', title: '' },

            // ─── Jobs & Queue ─────────────────────────────────────────────
            jobsData:            { pending: [], failed: [], counts: { pending: 0, processing: 0, failed: 0 } },
            jobsRefreshInterval: null,

            // ─── Question Bank ────────────────────────────────────────────
            questions:       [],
            showQuestionModal: false,
            questionForm: {
                id: null, board_id: '', class_id: '', subject_id: '', chapter_id: '',
                type: 'MCQ', question_text: '', difficulty: 'Medium', marks: 1, language: 'English',
                options: [
                    { option_text: '', is_correct: false },
                    { option_text: '', is_correct: false },
                    { option_text: '', is_correct: false },
                    { option_text: '', is_correct: false },
                ],
            },
            qSearch: '', qFilterBoard: '', qFilterSubject: '', qFilterType: '', qFilterDifficulty: '',

            // ─── Paper Generator ──────────────────────────────────────────
            paperForm:     { title: '', board_id: '', class_id: '', subject_id: '', chapter_ids: [], difficulty: 'All', total_marks: 50 },
            generatedPaper: null,

            // ─── Library ──────────────────────────────────────────────────
            libraryItems:       [],
            activeLibraryItem:  null,
            librarySearch:      '',
            libraryFilterBoard: '',
            libraryFilterType:  '',

            // ─── Logs ─────────────────────────────────────────────────────
            systemLogs:      [],
            logSearch:       '',
            logFilterAction: '',

            // ═════════════════════════════════════════════════════════════
            // 3. Init
            // ═════════════════════════════════════════════════════════════
            initApp() {
                if (this.token) {
                    // fetchUser first — if the token is expired/invalid it clears state
                    // before fetchInitData fires so we don't flood the server with 401s
                    this.fetchUser().then(ok => { if (ok) this.fetchInitData(); });
                }
            },

            async fetchUser() {
                try {
                    this.user = await this.apiCall('me');
                    return true; // signal: auth ok
                } catch (e) {
                    this.token = '';
                    store.del('token');
                    this.user = null;
                    return false; // signal: auth failed, stop init chain
                }
            },

            async fetchInitData() {
                try {
                    [this.boards, this.subjects, this.chapters, this.questions] = await Promise.all([
                        this.apiCall('boards'),
                        this.apiCall('subjects'),
                        this.apiCall('chapters'),
                        this.apiCall('questions'),
                    ]);
                } catch (e) { console.error('Init data fetch failed', e); }
            },

            // ═════════════════════════════════════════════════════════════
            // 4. Auth
            // ═════════════════════════════════════════════════════════════
            async login() {
                this.loginError = '';
                try {
                    const res  = await fetch('/api/login', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body:    JSON.stringify(this.loginForm),
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        // Surface the actual validation message from Laravel
                        const msg = data.errors?.email?.[0] || data.errors?.password?.[0] || data.message || 'Login failed';
                        throw new Error(msg);
                    }
                    // API returns { access_token, token_type, user }
                    const token = data.access_token || data.token;
                    if (!token) throw new Error('No token received from server.');
                    this.token = token;
                    store.set('token', token);
                    this.user = data.user || null;
                    this.fetchInitData();
                } catch (e) { this.loginError = e.message; }
            },

            async logout() {
                try { await this.apiCall('logout', 'POST'); } catch (e) {}
                this.token = '';
                store.del('token');
                this.user = null;
            },

            // ═════════════════════════════════════════════════════════════
            // 5. API Helper
            // ═════════════════════════════════════════════════════════════
            async apiCall(endpoint, method = 'GET', body = null) {
                const opts = {
                    method,
                    headers: {
                        'Authorization':  'Bearer ' + this.token,
                        'Content-Type':   'application/json',
                        'Accept':         'application/json',
                    },
                };
                if (body && method !== 'GET') opts.body = JSON.stringify(body);
                const res  = await fetch('/api/' + endpoint, opts);
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'API error');
                return data;
            },

            // ═════════════════════════════════════════════════════════════
            // 6. Confirm Modal
            // ═════════════════════════════════════════════════════════════
            async executeConfirmAction() {
                if (typeof this.confirmConfig.action === 'function') {
                    this.confirmLoading = true;
                    await this.confirmConfig.action();
                    this.confirmLoading   = false;
                    this.showConfirmModal = false;
                }
            },

            // ═════════════════════════════════════════════════════════════
            // 7. Boards
            // ═════════════════════════════════════════════════════════════
            async loadBoards() {
                this.boards      = await this.apiCall('boards');
                this.currentView = 'boards';
            },

            editBoard(board) {
                this.boardForm    = { ...board };
                this.showBoardModal = true;
            },

            async saveBoard() {
                try {
                    if (this.boardForm.id) {
                        const updated = await this.apiCall(`boards/${this.boardForm.id}`, 'PUT', this.boardForm);
                        this.boards   = this.boards.map(b => b.id === updated.id ? updated : b);
                        this.showSuccess('Board updated successfully.');
                    } else {
                        const created = await this.apiCall('boards', 'POST', this.boardForm);
                        this.boards.push(created);
                        this.showSuccess('Board created successfully.');
                    }
                    this.showBoardModal = false;
                    this.boardForm      = { id: null, name: '', code: '' };
                } catch (e) { this.showError(e.message); }
            },

            // Generic delete helper used by Boards (and any simple array-backed resource)
            confirmDeleteItem(item, endpoint, arrayName, labelField = 'name') {
                this.confirmConfig = {
                    title:    'Delete Record',
                    message:  `Are you sure you want to permanently delete <strong>${item[labelField]}</strong>? This action cannot be undone.`,
                    isDanger: true,
                    action:   async () => {
                        try {
                            await this.apiCall(`${endpoint}/${item.id}`, 'DELETE');
                            this[arrayName] = this[arrayName].filter(i => i.id !== item.id);
                            this.showSuccess(`${item[labelField]} deleted.`);
                        } catch (e) { this.showError(e.message); }
                    },
                };
                this.showConfirmModal = true;
            },

            // ═════════════════════════════════════════════════════════════
            // 8. Subjects
            // ═════════════════════════════════════════════════════════════
            async loadSubjects() {
                this.subjects    = await this.apiCall('subjects');
                this.currentView = 'subjects';
            },

            filteredSubjects() {
                return this.subjects.filter(s =>
                    (this.subjectFilterBoard === '' || String(s.board_id) === String(this.subjectFilterBoard)) &&
                    s.name.toLowerCase().includes(this.subjectSearch.toLowerCase())
                );
            },

            // ═════════════════════════════════════════════════════════════
            // 9. Chapters
            // ═════════════════════════════════════════════════════════════
            async loadChapters() {
                this.chapters    = await this.apiCall('chapters');
                this.currentView = 'chapters';
            },

            openChapterCreateModal() {
                this.chapterForm  = { id: null, board_id: '', subject_id: '', title: '', chapter_number: '' };
                this.chapterError = '';
                this.showChapterModal = true;
            },

            editChapter(ch) {
                this.chapterForm  = { ...ch };
                this.chapterError = '';
                this.showChapterModal = true;
            },

            async saveChapter() {
                this.chapterError = '';
                try {
                    if (this.chapterForm.id) {
                        const updated  = await this.apiCall(`chapters/${this.chapterForm.id}`, 'PUT', this.chapterForm);
                        this.chapters  = this.chapters.map(c => c.id === updated.id ? updated : c);
                        this.showSuccess('Chapter updated.');
                    } else {
                        const created  = await this.apiCall('chapters', 'POST', this.chapterForm);
                        this.chapters.push(created);
                        this.showSuccess('Chapter created.');
                    }
                    this.showChapterModal = false;
                } catch (e) { this.chapterError = e.message; }
            },

            confirmDeleteChapter(ch) {
                this.confirmConfig = {
                    title:    'Delete Chapter',
                    message:  `Permanently delete chapter <strong>${ch.title}</strong>?`,
                    isDanger: true,
                    action:   async () => {
                        try {
                            await this.apiCall(`chapters/${ch.id}`, 'DELETE');
                            this.chapters = this.chapters.filter(c => c.id !== ch.id);
                            this.showSuccess('Chapter deleted.');
                        } catch (e) { this.showError(e.message); }
                    },
                };
                this.showConfirmModal = true;
            },

            filteredChapters() {
                return this.chapters.filter(c =>
                    (this.chapterFilterBoard   === '' || String(c.board_id)   === String(this.chapterFilterBoard)) &&
                    (this.chapterFilterSubject === '' || String(c.subject_id) === String(this.chapterFilterSubject)) &&
                    c.title.toLowerCase().includes(this.chapterSearch.toLowerCase())
                );
            },

            // ═════════════════════════════════════════════════════════════
            // 10. Ingestion & Scraper
            // ═════════════════════════════════════════════════════════════
            openUploaderView() { this.currentView = 'uploader'; this.extractedTextPreview = ''; },

            async submitUpload() {
                this.uploading = true;
                try {
                    const formData = new FormData();
                    formData.append('file',        this.uploadForm.file);
                    formData.append('target_type', this.uploadForm.target_type);
                    formData.append('board_id',    this.uploadForm.board_id);
                    formData.append('class_id',    this.uploadForm.class_id);
                    formData.append('subject_id',  this.uploadForm.subject_id);
                    formData.append('chapter_id',  this.uploadForm.chapter_id);
                    formData.append('title',       this.uploadForm.title);
                    formData.append('run_ocr',     this.uploadForm.run_ocr ? 'true' : 'false');

                    const res  = await fetch('/api/ingest', {
                        method:  'POST',
                        headers: { 'Authorization': 'Bearer ' + this.token, 'Accept': 'application/json' },
                        body:    formData,
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Upload failed');

                    if (data.status === 'queued') {
                        this.showSuccess('OCR job queued. Check Jobs & Queue for live progress!');
                    } else {
                        this.extractedTextPreview = data.text || '';
                        this.activeIngestedItem   = data.item;
                        this.showSuccess('Document ingested and text extracted successfully!');
                    }
                } catch (e) { this.showError(e.message); }
                this.uploading = false;
            },

            async openScraperView() { this.currentView = 'scraper'; },

            async submitScrape() {
                this.scraping = true;
                try {
                    await this.apiCall('scrape', 'POST', this.scrapeForm);
                    this.showSuccess('Website scraped and indexed successfully!');
                } catch (e) { this.showError(e.message); }
                this.scraping = false;
            },

            // ═════════════════════════════════════════════════════════════
            // 11. Jobs & Queue
            // ═════════════════════════════════════════════════════════════
            async openJobsView() {
                this.currentView = 'jobs';
                await Promise.all([this.loadJobs(), this.openLogsView(false)]);
                clearInterval(this.jobsRefreshInterval);
                this.jobsRefreshInterval = setInterval(() => {
                    if (this.currentView === 'jobs') this.loadJobs();
                }, 10000);
            },

            async loadJobs() {
                try { this.jobsData = await this.apiCall('jobs'); }
                catch (e) { console.error('Failed to load jobs', e); }
            },

            async retryJob(id) {
                try {
                    await this.apiCall(`jobs/failed/${id}/retry`, 'POST');
                    this.showSuccess('Job re-queued.');
                    await this.loadJobs();
                } catch (e) { this.showError(e.message); }
            },

            async deleteFailedJob(id) {
                try {
                    await this.apiCall(`jobs/failed/${id}`, 'DELETE');
                    this.showSuccess('Failed job purged.');
                    this.jobsData.failed         = this.jobsData.failed.filter(j => j.id !== id);
                    this.jobsData.counts.failed  = Math.max(0, (this.jobsData.counts.failed || 1) - 1);
                } catch (e) { this.showError(e.message); }
            },

            // ═════════════════════════════════════════════════════════════
            // 12. Library
            // ═════════════════════════════════════════════════════════════
            async openLibraryView() {
                this.currentView      = 'library';
                this.activeLibraryItem = null;
                try {
                    const [notes, materials] = await Promise.all([this.apiCall('notes'), this.apiCall('materials')]);
                    this.libraryItems = [
                        ...notes.map(n     => ({ ...n,     type: 'note',     unique_id: `note_${n.id}` })),
                        ...materials.map(m => ({ ...m, type: 'material', unique_id: `material_${m.id}` })),
                    ];
                } catch (e) { this.showError('Failed to load library: ' + e.message); }
            },

            filteredLibraryItems() {
                return this.libraryItems.filter(item =>
                    (this.libraryFilterType  === '' || item.type                    === this.libraryFilterType) &&
                    (this.libraryFilterBoard === '' || String(item.board_id)        === String(this.libraryFilterBoard)) &&
                    (item.title || '').toLowerCase().includes(this.librarySearch.toLowerCase())
                );
            },

            selectLibraryItem(item) { this.activeLibraryItem = { ...item }; },

            async saveLibraryItemUpdates() {
                if (!this.activeLibraryItem) return;
                const ep = this.activeLibraryItem.type === 'note'
                    ? `notes/${this.activeLibraryItem.id}`
                    : `materials/${this.activeLibraryItem.id}`;
                try {
                    await this.apiCall(ep, 'PUT', { extracted_text: this.activeLibraryItem.extracted_text });
                    this.showSuccess('Document corpus updated successfully.');
                    await this.openLibraryView();
                } catch (e) { this.showError(e.message); }
            },

            confirmDeleteLibraryItem(item) {
                this.confirmConfig = {
                    title:    'Delete Document',
                    message:  `Permanently delete <strong>${item.title}</strong> from the database?`,
                    isDanger: true,
                    action:   async () => {
                        try {
                            const ep = item.type === 'note' ? `notes/${item.id}` : `materials/${item.id}`;
                            await this.apiCall(ep, 'DELETE');
                            this.libraryItems      = this.libraryItems.filter(i => i.unique_id !== item.unique_id);
                            this.activeLibraryItem  = null;
                            this.showSuccess('Document deleted.');
                        } catch (e) { this.showError(e.message); }
                    },
                };
                this.showConfirmModal = true;
            },

            // ═════════════════════════════════════════════════════════════
            // 13. Questions
            // ═════════════════════════════════════════════════════════════
            async loadQuestions() {
                this.questions   = await this.apiCall('questions');
                this.currentView = 'questions';
            },

            filteredQuestions() {
                return this.questions.filter(q =>
                    (this.qFilterBoard      === '' || String(q.board_id)   === String(this.qFilterBoard)) &&
                    (this.qFilterSubject    === '' || String(q.subject_id) === String(this.qFilterSubject)) &&
                    (this.qFilterType       === '' || q.type               === this.qFilterType) &&
                    (this.qFilterDifficulty === '' || q.difficulty         === this.qFilterDifficulty) &&
                    (q.question_text || '').toLowerCase().includes(this.qSearch.toLowerCase())
                );
            },

            openQuestionCreateModal() {
                this.questionForm = {
                    id: null, board_id: '', class_id: '', subject_id: '', chapter_id: '',
                    type: 'MCQ', question_text: '', difficulty: 'Medium', marks: 1, language: 'English',
                    options: [
                        { option_text: '', is_correct: false },
                        { option_text: '', is_correct: false },
                        { option_text: '', is_correct: false },
                        { option_text: '', is_correct: false },
                    ],
                };
                this.showQuestionModal = true;
            },

            editQuestion(q) {
                this.questionForm = {
                    ...q,
                    options: q.options && q.options.length
                        ? [...q.options]
                        : [
                            { option_text: '', is_correct: false },
                            { option_text: '', is_correct: false },
                            { option_text: '', is_correct: false },
                            { option_text: '', is_correct: false },
                        ],
                };
                this.showQuestionModal = true;
            },

            setMcqCorrect(index) {
                this.questionForm.options.forEach((o, i) => o.is_correct = (i === index));
            },

            async saveQuestion() {
                try {
                    if (this.questionForm.id) {
                        const updated   = await this.apiCall(`questions/${this.questionForm.id}`, 'PUT', this.questionForm);
                        this.questions  = this.questions.map(q => q.id === updated.id ? updated : q);
                        this.showSuccess('Question updated.');
                    } else {
                        const created = await this.apiCall('questions', 'POST', this.questionForm);
                        this.questions.push(created);
                        this.showSuccess('Question added to bank.');
                    }
                    this.showQuestionModal = false;
                } catch (e) { this.showError(e.message); }
            },

            confirmDeleteQuestion(q) {
                this.confirmConfig = {
                    title:    'Delete Question',
                    message:  `Permanently delete this question: <em>"${q.question_text.substring(0, 60)}…"</em>?`,
                    isDanger: true,
                    action:   async () => {
                        try {
                            await this.apiCall(`questions/${q.id}`, 'DELETE');
                            this.questions = this.questions.filter(x => x.id !== q.id);
                            this.showSuccess('Question deleted.');
                        } catch (e) { this.showError(e.message); }
                    },
                };
                this.showConfirmModal = true;
            },

            // ═════════════════════════════════════════════════════════════
            // 14. Paper Generator
            // ═════════════════════════════════════════════════════════════
            openPaperGeneratorView() { this.currentView = 'papers'; this.generatedPaper = null; },

            async generatePaper() {
                try {
                    this.generatedPaper = await this.apiCall('generate-paper', 'POST', this.paperForm);
                    this.showSuccess('Paper generated successfully!');
                } catch (e) { this.showError(e.message); }
            },

            getCorrectOptionLabel(options) {
                const letters = ['A', 'B', 'C', 'D'];
                const index   = options.findIndex(opt => opt.is_correct);
                return index !== -1 ? letters[index] : 'N/A';
            },

            // ═════════════════════════════════════════════════════════════
            // 15. Logs & Reports
            // ═════════════════════════════════════════════════════════════
            async openLogsView(switchView = true) {
                if (switchView) this.currentView = 'logs';
                try { this.systemLogs = await this.apiCall('logs'); }
                catch (e) { console.error('Failed to load logs', e); }
            },

            filteredLogs() {
                return this.systemLogs.filter(l =>
                    (this.logFilterAction === '' || l.action.includes(this.logFilterAction)) &&
                    (
                        (l.description || '').toLowerCase().includes(this.logSearch.toLowerCase()) ||
                        (l.action      || '').toLowerCase().includes(this.logSearch.toLowerCase())
                    )
                );
            },

            exportLogsCSV() {
                const rows = [['Timestamp', 'User', 'Action', 'Description', 'IP']];
                this.filteredLogs().forEach(l => rows.push([
                    l.created_at,
                    l.user?.name || 'System',
                    l.action,
                    l.description,
                    l.ip_address,
                ]));
                const csv  = rows.map(r => r.map(c => `"${(c || '').toString().replace(/"/g, '""')}"`).join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv' });
                const a    = document.createElement('a');
                a.href     = URL.createObjectURL(blob);
                a.download = 'iqra_logs.csv';
                a.click();
            },
        };
    }
</script>
