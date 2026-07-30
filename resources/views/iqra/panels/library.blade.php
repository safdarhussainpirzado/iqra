{{-- Notes & Materials Library Panel --}}
<div x-show="currentView === 'library'" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-100">Indexed Notes &amp; Books Library</h3>
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
                <input type="text" x-model="librarySearch" placeholder="Search documents…"
                       class="pl-8 pr-4 py-2 rounded-xl border-0 bg-slate-800 text-slate-100 text-xs focus:ring-2 focus:ring-indigo-500 w-44 placeholder:text-slate-500">
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Item List --}}
        <div class="space-y-3 max-h-[680px] overflow-y-auto pr-1">
            <template x-for="item in filteredLibraryItems()" :key="item.unique_id">
                <div @click="selectLibraryItem(item)"
                     :class="activeLibraryItem?.unique_id === item.unique_id ? 'border-indigo-500/60 bg-indigo-600/15' : 'border-slate-700/50 bg-slate-900/60 hover:border-slate-600'"
                     class="p-4 rounded-xl border cursor-pointer transition duration-150">
                    <div class="flex items-center justify-between mb-2">
                        <span :class="item.type === 'note' ? 'bg-indigo-500/20 text-indigo-300' : 'bg-pink-500/20 text-pink-300'"
                              class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" x-text="item.type"></span>
                        <span class="text-[10px] text-slate-500 font-mono" x-text="item.file_type"></span>
                    </div>
                    <h5 class="text-sm font-bold text-slate-100 leading-snug" x-text="item.title"></h5>
                    <div class="text-[10px] text-slate-500 mt-1"
                         x-text="`${item.board?.name || '—'} / ${item.subject?.name || '—'} / ${item.chapter?.title || '—'}`"></div>
                    <div class="text-[10px] text-slate-600 mt-1" x-text="`${(item.extracted_text || '').length} chars`"></div>
                </div>
            </template>
            <template x-if="filteredLibraryItems().length === 0">
                <div class="text-xs text-slate-500 py-8 text-center border border-dashed border-slate-800 rounded-xl">
                    No documents found. Upload via Ingestion &amp; OCR.
                </div>
            </template>
        </div>
        {{-- Right: Editor Pane --}}
        <div class="lg:col-span-2">
            <template x-if="activeLibraryItem">
                <div class="backdrop-blur-md bg-slate-900/60 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 px-6 py-4 border-b border-slate-800">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-indigo-400" x-text="activeLibraryItem.title"></h4>
                                <p class="text-[10px] text-slate-400 mt-0.5"
                                   x-text="`${activeLibraryItem.board?.name} › ${activeLibraryItem.subject?.name} › ${activeLibraryItem.chapter?.title}`"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-500 font-mono" x-text="activeLibraryItem.file_type"></span>
                                <button @click="confirmDeleteLibraryItem(activeLibraryItem)"
                                        class="w-8 h-8 rounded-lg bg-rose-600/80 text-white flex items-center justify-center text-xs hover:bg-rose-500 transition active:scale-95">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-2">Document Text Corpus (Editable)</label>
                            <textarea x-model="activeLibraryItem.extracted_text" rows="16"
                                      class="w-full rounded-xl border border-slate-700 p-4 text-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500 leading-relaxed"></textarea>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-slate-500">Character length: <strong class="text-slate-400" x-text="activeLibraryItem.extracted_text?.length || 0"></strong></span>
                            <button @click="saveLibraryItemUpdates()"
                                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Document Changes
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
