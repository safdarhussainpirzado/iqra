{{-- Global Toast Notification --}}
<div x-show="toastVisible"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     :class="toastType === 'success' ? 'bg-emerald-600 border-emerald-500' : 'bg-rose-600 border-rose-500'"
     class="fixed bottom-6 right-6 z-[200] px-5 py-3 rounded-xl border text-white text-sm font-semibold shadow-2xl flex items-center gap-3">
    <i :class="toastType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'" class="fas"></i>
    <span x-text="toastMessage"></span>
</div>
