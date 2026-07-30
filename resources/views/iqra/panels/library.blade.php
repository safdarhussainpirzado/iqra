{{-- Notes & Materials Library Panel — Premium ZIWO Light Theme --}}
<div x-show="currentView === 'library'" class="space-y-6" x-transition>
    
    {{-- Toolbar Header --}}
    <div class="bg-white rounded-3xl border border-slate-150 p-6 shadow-[0_10px_40px_rgba(0,0,0,0.02)] flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-folder-open text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-blue-900 tracking-tight">Indexed Notes &amp; Books Library</h3>
                <p class="text-xs text-slate-500 font-bold mt-0.5" x-text="`${filteredLibraryItems().length} documents indexed`"></p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <select x-model="libraryFilterType" class="rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                <option value="">All Types</option>
                <option value="note">Notes</option>
                <option value="material">Materials</option>
            </select>
            <select x-model="libraryFilterBoard" class="rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-800 focus:ring-2 focus:ring-blue-500 text-xs outline-none shadow-sm">
                <option value="">All Boards</option>
                <template x-for="board in boards" :key="board.id"><option :value="board.id" x-text="board.name"></option></template>
            </select>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="librarySearch" placeholder="Search documents…"
                       class="pl-8 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs focus:ring-2 focus:ring-blue-500 w-44 placeholder:text-slate-400 outline-none shadow-sm">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Item List --}}
        <div class="space-y-3 max-h-[680px] overflow-y-auto pr-1">
            <template x-for="item in filteredLibraryItems()" :key="item.unique_id">
                <div @click="selectLibraryItem(item)"
                     :class="activeLibraryItem?.unique_id === item.unique_id ? 'border-blue-500/60 bg-blue-50/50' : 'border-slate-150 bg-white hover:bg-slate-50/50'"
                     class="p-5 rounded-2xl border cursor-pointer transition shadow-[0_4px_15px_rgba(0,0,0,0.01)]">
                    <div class="flex items-center justify-between mb-3">
                        <span :class="item.type === 'note' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 'bg-pink-50 text-pink-600 border-pink-100'"
                              class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border" x-text="item.type"></span>
                        <span class="text-[10px] text-slate-400 font-mono" x-text="item.file_type"></span>
                    </div>
                    <h5 class="text-sm font-extrabold text-blue-900 leading-snug" x-text="item.title"></h5>
                    <div class="text-[10px] text-slate-500 mt-2 font-bold"
                         x-text="`${item.board?.name || '—'} › ${item.subject?.name || '—'} › ${item.chapter?.title || '—'}`"></div>
                    <div class="text-[10px] text-slate-400 mt-1 font-mono" x-text="`${(item.extracted_text || '').length} characters`"></div>
                </div>
            </template>
            <template x-if="filteredLibraryItems().length === 0">
                <div class="text-xs text-slate-400 py-12 text-center border-2 border-dashed border-slate-200 bg-white rounded-2xl font-bold">
                    No documents found. Upload via Ingestion &amp; OCR.
                </div>
            </template>
        </div>

        {{-- Right: Editor Pane --}}
        <div class="lg:col-span-2">
            <template x-if="activeLibraryItem">
                <div class="bg-white rounded-[2rem] border border-slate-150 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
                    <div class="bg-blue-50/30 px-8 py-5 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-extrabold text-blue-900" x-text="activeLibraryItem.title"></h4>
                                <p class="text-[10px] text-slate-400 font-bold mt-0.5"
                                   x-text="`${activeLibraryItem.board?.name} › ${activeLibraryItem.subject?.name} › ${activeLibraryItem.chapter?.title}`"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 font-mono" x-text="activeLibraryItem.file_type"></span>
                                <button @click="confirmDeleteLibraryItem(activeLibraryItem)"
                                        class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-700 active:scale-95 transition-all shadow-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Document Text Corpus (Editable)</label>
                            <textarea x-model="activeLibraryItem.extracted_text" rows="16"
                                      class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-slate-700 text-xs font-mono focus:ring-2 focus:ring-blue-500 focus:bg-white leading-relaxed outline-none shadow-sm"></textarea>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400">Character length: <strong class="text-slate-700" x-text="activeLibraryItem.extracted_text?.length || 0"></strong></span>
                            <button @click="saveLibraryItemUpdates()"
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-[0_8px_20px_rgba(37,99,235,0.25)] transition-all flex items-center gap-2 active:scale-95">
                                <i class="fas fa-save"></i> Save Document Changes
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!activeLibraryItem">
                <div class="h-96 border-2 border-dashed border-slate-200 bg-white rounded-[2rem] flex flex-col items-center justify-center text-slate-400 gap-3 shadow-[0_10px_40px_rgba(0,0,0,0.01)]">
                    <i class="fas fa-folder-open text-3xl"></i>
                    <p class="text-xs font-bold">Select a document on the left to view or edit its corpus text.</p>
                </div>
            </template>
        </div>
    </div>
</div>
