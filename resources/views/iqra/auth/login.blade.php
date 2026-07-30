{{-- Login Screen — shown when no token exists --}}
<template x-if="!token">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">IQRA</h1>
            <p class="mt-2 text-sm text-slate-400">Enterprise Educational Content &amp; Question Bank Monolith</p>
        </div>
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="backdrop-blur-md bg-slate-900/60 border border-slate-700/50 px-4 py-8 shadow-2xl rounded-2xl sm:px-10">
                <form class="space-y-6" @submit.prevent="login()">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300">Email Address</label>
                        <input id="email" x-model="loginForm.email" type="email" required
                               class="mt-1 block w-full rounded-xl border-0 bg-slate-800/80 py-2.5 text-slate-100 shadow-sm ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm px-3">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                        <input id="password" x-model="loginForm.password" type="password" required
                               class="mt-1 block w-full rounded-xl border-0 bg-slate-800/80 py-2.5 text-slate-100 shadow-sm ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm px-3">
                    </div>
                    <template x-if="loginError">
                        <div class="rounded-lg bg-red-500/10 p-3 border border-red-500/20 text-xs text-red-400" x-text="loginError"></div>
                    </template>
                    <button type="submit"
                            class="flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-200">
                        Sign In
                    </button>
                </form>
                <div class="mt-6 text-center text-xs text-slate-500">
                    Use <code class="text-indigo-400">admin@iqra.edu</code> / <code class="text-indigo-400">Admin@12345</code>
                </div>
            </div>
        </div>
    </div>
</template>
