{{-- Ingestion & OCR Panel --}}
<div x-show="currentView === 'uploader'" class="space-y-6">
    <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                <i class="fas fa-upload text-indigo-400"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-indigo-400">Document Ingestion &amp; Text Corpus Extractor</h3>
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
                        <input type="text" x-model="uploadForm.title" required placeholder="E.g., Physics Notes Unit 1"
                               class="block w-full rounded-xl border-0 bg-slate-800 py-2 px-3 text-slate-100 focus:ring-2 focus:ring-indigo-500 text-xs">
                    </div>
                </div>
            </div>
            <div class="space-y-4 flex flex-col justify-between">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Choose File</label>
                    <p class="text-[10px] text-slate-500 mb-2">Supported: .pdf, .docx, .txt, .csv, .xlsx, .json, .html (max 25MB)</p>
                    <input type="file" @change="uploadForm.file = $event.target.files[0]" required
                           class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600 transition">
                </div>
                <div class="flex items-start space-x-3 bg-amber-500/5 border border-amber-500/20 p-4 rounded-xl">
                    <input id="run-ocr" type="checkbox" x-model="uploadForm.run_ocr"
                           class="h-4 w-4 mt-0.5 rounded border-slate-700 bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <label for="run-ocr" class="text-xs font-semibold text-amber-300">Enable OCR (Tesseract)</label>
                        <p class="text-[10px] text-slate-500 mt-0.5">Required for scanned image PDFs. Runs in background queue. Check <strong>Jobs &amp; Queue</strong> for progress.</p>
                    </div>
                </div>
                <button type="submit" :disabled="uploading"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-md transition duration-150 flex items-center justify-center gap-2 disabled:opacity-60">
                    <i class="fas fa-upload"></i>
                    <span x-text="uploading ? 'Processing…' : 'Upload & Process'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- Extracted text preview --}}
    <div x-show="extractedTextPreview" class="backdrop-blur-md bg-slate-900/60 border border-emerald-800/50 p-6 rounded-2xl shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h4 class="text-sm font-semibold text-emerald-400 flex items-center gap-2">
                <i class="fas fa-file-lines"></i> Extracted Text Preview
            </h4>
            <span class="text-xs text-slate-400"><span x-text="extractedTextPreview.length"></span> characters extracted</span>
        </div>
        <textarea x-model="extractedTextPreview" rows="10"
                  class="w-full rounded-xl border border-slate-700 p-4 text-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>
</div>
