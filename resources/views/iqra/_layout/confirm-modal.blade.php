{{-- Universal Confirmation Modal --}}
<div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-sm shadow-2xl p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div :class="confirmConfig.isDanger ? 'bg-rose-500/20 text-rose-400' : 'bg-amber-500/20 text-amber-400'"
                 class="w-10 h-10 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-base font-bold text-slate-100" x-text="confirmConfig.title"></h3>
        </div>
        <p class="text-sm text-slate-400 leading-relaxed" x-html="confirmConfig.message"></p>
        <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
            <button @click="showConfirmModal = false"
                    class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl transition">
                Cancel
            </button>
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
