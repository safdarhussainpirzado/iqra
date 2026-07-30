{{-- Web Importer Panel — Premium ZIWO Light Theme --}}
<div x-show="currentView === 'scraper'" class="space-y-6" x-transition>
    <div class="bg-white rounded-[2rem] border border-slate-150 p-8 shadow-[0_10px_40px_rgba(0,0,0,0.02)] space-y-6">
        <div class="flex items-center gap-3 mb-2 pb-4 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-spider text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-blue-900 tracking-tight">Web Page Scraping Importer</h3>
                <p class="text-xs text-slate-500 font-bold mt-0.5">Enter any public URL and classify it to crawl and import content into the database.</p>
            </div>
        </div>
        
        <form @submit.prevent="submitScrape()" class="space-y-5" data-no-pjax>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Board</label>
                    <select x-model="scrapeForm.board_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Board</option>
                        <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Class</label>
                    <select x-model="scrapeForm.class_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Class</option>
                        <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subject</label>
                    <select x-model="scrapeForm.subject_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                        <option value="">Select Subject</option>
                        <template x-for="subj in getFilteredSubjects(scrapeForm.board_id, scrapeForm.class_id)" :key="subj.id">
                            <option :value="subj.id" x-text="subj.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Chapter</label>
                    <input type="text" x-model="scrapeForm.chapter_title" required placeholder="E.g., Chapter 1: Introduction"
                           class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Target URL</label>
                    <input type="url" x-model="scrapeForm.url" required placeholder="https://example-education.com/notes/chapter-1"
                           class="w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Document Title</label>
                    <input type="text" x-model="scrapeForm.title" required placeholder="Document Title"
                           class="w-full rounded-xl border border-slate-200 bg-white py-3 px-4 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                </div>
            </div>
            
            <div class="flex justify-end pt-3">
                <button type="submit" :disabled="scraping"
                        class="flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all active:scale-95 disabled:opacity-60">
                    <i class="fas fa-spider"></i>
                    <span x-text="scraping ? 'Crawl & Ingesting Page…' : 'Scrape & Index Website'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
