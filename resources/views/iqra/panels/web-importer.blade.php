{{-- Web Importer Panel --}}
<div x-show="currentView === 'scraper'" class="space-y-6">
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                <i class="fas fa-spider text-indigo-400"></i>
            </div>
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
                    <input type="url" x-model="scrapeForm.url" required placeholder="https://example-education.com/notes/chapter-1"
                           class="w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                </div>
                <input type="text" x-model="scrapeForm.title" required placeholder="Document Title"
                       class="w-full rounded-xl border-0 bg-slate-800 py-2.5 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
            </div>
            <div class="flex justify-end">
                <button type="submit" :disabled="scraping"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2 disabled:opacity-60">
                    <i class="fas fa-spider"></i>
                    <span x-text="scraping ? 'Scraping Page…' : 'Scrape & Index Website'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
