{{-- Ingestion & OCR Panel — Premium ZIWO Light Theme --}}
<div x-show="currentView === 'uploader'" class="space-y-6" x-transition>
    <div class="bg-white rounded-[2rem] border border-slate-150 p-8 shadow-[0_10px_40px_rgba(0,0,0,0.02)]">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-cloud-arrow-up text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-blue-900 tracking-tight">Document Ingestion &amp; Text Corpus Extractor</h3>
                <p class="text-xs text-slate-500 font-bold mt-0.5">Upload files to extract and index text. Enable OCR to process scanned pages in the background queue.</p>
            </div>
        </div>
        
        <form @submit.prevent="submitUpload()" class="grid grid-cols-1 md:grid-cols-2 gap-8" data-no-pjax>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Associated Board</label>
                        <select x-model="uploadForm.board_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                            <option value="">Select Board</option>
                            <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Target Class</label>
                        <select x-model="uploadForm.class_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                            <option value="">Select Class</option>
                            <template x-for="cls in classesList" :key="cls.id"><option :value="cls.id" x-text="cls.name"></option></template>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Subject</label>
                        <select x-model="uploadForm.subject_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                            <option value="">Select Subject</option>
                            <template x-for="subj in subjects" :key="subj.id"><option :value="subj.id" x-text="subj.name"></option></template>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Chapter</label>
                        <select x-model="uploadForm.chapter_id" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                            <option value="">Select Chapter</option>
                            <template x-for="ch in chapters" :key="ch.id"><option :value="ch.id" x-text="ch.title"></option></template>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Corpus Category</label>
                        <select x-model="uploadForm.target_type" required class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                            <option value="note">Notes</option>
                            <option value="material">Books / Material</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Document Title</label>
                        <input type="text" x-model="uploadForm.title" required placeholder="E.g., Physics Notes Unit 1"
                               class="w-full rounded-xl border border-slate-200 bg-white py-3 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                    </div>
                </div>
            </div>
            
            <div class="space-y-6 flex flex-col justify-between">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Choose Payload File</label>
                    <p class="text-[10px] text-slate-400 font-bold">Supported: .pdf, .docx, .txt, .csv, .xlsx, .json, .html (max 25MB)</p>
                    <input type="file" @change="uploadForm.file = $event.target.files[0]" required
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-black file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 transition shadow-sm">
                </div>
                
                <div class="flex items-start space-x-3 bg-amber-500/5 border border-amber-500/15 p-5 rounded-2xl">
                    <input id="run-ocr" type="checkbox" x-model="uploadForm.run_ocr"
                           class="h-4 w-4 mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <label for="run-ocr" class="text-xs font-black text-amber-700 uppercase tracking-wider">Enable OCR (Tesseract)</label>
                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">Required for scanned image PDFs. Runs in background queue. Check Jobs &amp; Queue for progress.</p>
                    </div>
                </div>
                
                <button type="submit" :disabled="uploading"
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all active:scale-95 disabled:opacity-60 flex items-center justify-center gap-2">
                    <i class="fas fa-cloud-arrow-up"></i>
                    <span x-text="uploading ? 'Processing Ingestion…' : 'Upload & Process'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- Extracted text preview --}}
    <div x-show="extractedTextPreview" class="bg-white rounded-[2rem] border border-emerald-150 p-8 shadow-[0_10px_40px_rgba(0,0,0,0.02)] space-y-4" x-transition>
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h4 class="text-sm font-black text-emerald-600 flex items-center gap-2">
                <i class="fas fa-file-lines"></i> Extracted Text Preview
            </h4>
            <span class="text-xs font-bold text-slate-400"><span x-text="extractedTextPreview.length"></span> characters extracted</span>
        </div>
        <textarea x-model="extractedTextPreview" rows="10"
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-700 text-xs font-mono focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none shadow-sm"></textarea>
    </div>
</div>
