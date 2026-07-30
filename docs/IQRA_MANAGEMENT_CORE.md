# IQRA Platform — Management Core UI/UX Architecture

> Adapted from `MANAGEMENT_CORE.md` for the IQRA project.
> Key differences: **Bearer-token auth** (Sanctum), **API-first** (no Blade CSRF), **SPA via Alpine.js** (no PJAX/bento-bridge), **Dark-mode first** design.

---

## Overview

IQRA uses a single-page Blade shell (`welcome.blade.php`) with **Alpine.js** as the reactive controller.
All data is fetched and mutated via `fetch()` calls to `/api/*` endpoints, authenticated with a Bearer token stored in `localStorage`.

Major components:
- A standardised **Data Table** per management entity (Boards, Subjects, Chapters, Questions, Notes, Materials, Logs).
- **Universal Confirmation Modal** for destructive actions.
- **Toast notifications** (global `showSuccess` / `showError` helpers).
- **Queue Monitor** panel showing live OCR job progress.

---

## 1. API Authentication Pattern

All API calls use the shared `apiCall()` helper in Alpine.js:

```javascript
async apiCall(endpoint, method = 'GET', body = null) {
    const opts = {
        method,
        headers: { 'Authorization': 'Bearer ' + this.token, 'Content-Type': 'application/json' }
    };
    if (body && method !== 'GET') opts.body = JSON.stringify(body);
    const res = await fetch('/api/' + endpoint, opts);
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'API error');
    return data;
},
```

**No CSRF token needed** — Sanctum token-based auth is used for all API routes.

---

## 2. Universal Confirmation Modal

All delete and status-toggle actions use a shared in-template modal to prevent accidental data loss.

### Alpine.js State

```javascript
showConfirmModal: false,
confirmLoading: false,
confirmConfig: { title: '', message: '', isDanger: false, action: null },
```

### Modal HTML Template

```html
<!-- Confirmation Modal -->
<div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-sm shadow-2xl p-6 space-y-4">
        <h3 class="text-base font-bold text-slate-100" x-text="confirmConfig.title"></h3>
        <p class="text-sm text-slate-400" x-html="confirmConfig.message"></p>
        <div class="flex justify-end gap-3 pt-2">
            <button @click="showConfirmModal = false" class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl">Cancel</button>
            <button @click="executeConfirmAction()" :disabled="confirmLoading"
                :class="confirmConfig.isDanger ? 'bg-rose-600 hover:bg-rose-500' : 'bg-indigo-600 hover:bg-indigo-500'"
                class="px-4 py-2 text-xs font-semibold text-white rounded-xl transition disabled:opacity-50">
                <span x-show="!confirmLoading">Confirm</span>
                <span x-show="confirmLoading">Processing…</span>
            </button>
        </div>
    </div>
</div>
```

### Execute Function

```javascript
async executeConfirmAction() {
    if (typeof this.confirmConfig.action === 'function') {
        this.confirmLoading = true;
        await this.confirmConfig.action();
        this.confirmLoading = false;
        this.showConfirmModal = false;
    }
},
```

---

## 3. Toast Notifications

Global helpers are defined once in the Alpine controller and available everywhere:

```javascript
toastMessage: '',
toastType: 'success',
toastVisible: false,

showSuccess(msg) {
    this.toastMessage = msg; this.toastType = 'success';
    this.toastVisible = true; setTimeout(() => this.toastVisible = false, 3500);
},
showError(msg) {
    this.toastMessage = msg; this.toastType = 'error';
    this.toastVisible = true; setTimeout(() => this.toastVisible = false, 4000);
},
```

Toast HTML (fixed bottom-right):

```html
<div x-show="toastVisible" x-transition
    :class="toastType === 'success' ? 'bg-emerald-600 border-emerald-500' : 'bg-rose-600 border-rose-500'"
    class="fixed bottom-6 right-6 z-[200] px-5 py-3 rounded-xl border text-white text-sm font-semibold shadow-xl">
    <span x-text="toastMessage"></span>
</div>
```

---

## 4. Action Button Standard

All action button sets use icon-only `w-9 h-9` buttons in a `grid grid-cols-3` container (table rows AND card views).

| Action | Button Color | Icon |
|--------|-------------|------|
| View / Inspect | `bg-blue-500 border-blue-600` | `fa-eye` |
| Edit / Modify | `bg-indigo-500 border-indigo-600` | `fa-pencil` |
| Delete / Purge | `bg-rose-600 border-rose-700` | `fa-trash-alt` |

```html
<div class="inline-grid grid-cols-3 gap-1.5">
    <button @click="viewItem(item)" title="Inspect"
        class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-600 text-white flex items-center justify-center text-xs hover:bg-blue-400 active:scale-95 transition">
        <i class="fas fa-eye"></i>
    </button>
    <button @click="editItem(item)" title="Edit"
        class="w-9 h-9 rounded-xl bg-indigo-500 border border-indigo-600 text-white flex items-center justify-center text-xs hover:bg-indigo-400 active:scale-95 transition">
        <i class="fas fa-pencil"></i>
    </button>
    <button @click="confirmDelete(item)" title="Delete"
        class="w-9 h-9 rounded-xl bg-rose-600 border border-rose-700 text-white flex items-center justify-center text-xs hover:bg-rose-500 active:scale-95 transition">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
```

---

## 5. Confirm Delete Pattern

```javascript
confirmDelete(item, endpoint, arrayName, labelField = 'name') {
    this.confirmConfig = {
        title: 'Delete Record',
        message: `Are you sure you want to permanently delete <strong>${item[labelField]}</strong>?`,
        isDanger: true,
        action: async () => {
            try {
                await this.apiCall(`${endpoint}/${item.id}`, 'DELETE');
                this[arrayName] = this[arrayName].filter(i => i.id !== item.id);
                this.showSuccess(`${item[labelField]} deleted successfully.`);
            } catch (e) {
                this.showError(e.message);
            }
        }
    };
    this.showConfirmModal = true;
},
```

---

## 6. Bento-Style Modals (Add/Edit Forms)

Forms should use a gradient-header bento card style:

```html
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <!-- Gradient Header -->
        <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 px-6 py-5">
            <h3 class="text-lg font-bold text-white" x-text="form.id ? 'Edit Record' : 'Add New Record'"></h3>
            <p class="text-indigo-200 text-xs mt-1">Fill in the fields below and save.</p>
        </div>
        <!-- Form Body -->
        <form @submit.prevent="saveRecord()" class="p-6 space-y-4">
            <!-- Fields here -->
            <div class="flex justify-end gap-3 pt-2 border-t border-slate-800">
                <button type="button" @click="showModal = false" class="px-4 py-2 text-xs text-slate-400 hover:text-white border border-slate-700 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl transition">Save</button>
            </div>
        </form>
    </div>
</div>
```

---

## 7. Search & Filter Pattern

Each panel includes a live search input that filters the data array client-side:

```html
<input type="text" x-model="searchQuery" placeholder="Search..."
    class="w-64 rounded-xl border-0 bg-slate-800 py-2 px-4 text-slate-100 text-sm focus:ring-2 focus:ring-indigo-500 placeholder:text-slate-500">
```

Computed filter in x-for:
```html
<template x-for="item in boards.filter(b => b.name.toLowerCase().includes(searchQuery.toLowerCase()))" :key="item.id">
```

---

## 8. Queue Monitor Panel

The Jobs & Queue panel (inside Ingestion & OCR) auto-refreshes every 10 seconds:

```javascript
async openJobsView() {
    this.currentView = 'jobs';
    await this.loadJobs();
    this.jobsRefreshInterval = setInterval(() => this.loadJobs(), 10000);
},
async loadJobs() {
    try {
        this.jobsData = await this.apiCall('jobs');
    } catch (e) { console.error('Failed to load jobs', e); }
},
```

Status badge classes:
- `pending` → `bg-amber-500/20 text-amber-300`
- `processing` → `bg-blue-500/20 text-blue-300` (pulse animation)
- `failed` → `bg-rose-500/20 text-rose-300`
- `completed` (activity log) → `bg-emerald-500/20 text-emerald-300`

---

## 9. Entity Panel Checklist

Every management panel MUST include:

| Feature | Required |
|---------|---------|
| Section header with "Add" button | ✅ |
| Live search input | ✅ |
| Sortable table columns | ✅ |
| Icon-only `w-9 h-9` action grid | ✅ |
| Confirm modal for delete | ✅ |
| Bento gradient modal for create/edit | ✅ |
| Toast feedback on all mutations | ✅ |
| Loading spinner on async actions | ✅ |

---

## 10. Default UI States

```javascript
currentView: 'dashboard',  // always start on dashboard
showConfirmModal: false,
toastVisible: false,
searchQuery: '',
```

No sidebar filters needed in IQRA (unlike the CRM) — filtering is done via inline search inputs and select dropdowns in the panel header row.
