<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    <title>{{ __('Sign in') }} | {{ config('app.name', 'Inventory') }}</title>

    <script src="https://kit.fontawesome.com/ef44533d17.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f7f9f8] font-sans text-slate-900 antialiased">

    <x-success_alert />

    <div class="flex min-h-screen flex-col">

        <!-- HEADER -->
        <header class="border-b border-slate-200/80 bg-white">
            <div class="mx-auto flex h-[68px] max-w-7xl items-center justify-between px-6 lg:px-10">

                <!-- LOGO -->
                <a href="/" class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#123c32] text-white">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </div>

                    <span class="text-lg font-bold tracking-tight text-slate-900">
                        Inventory<span class="text-emerald-600">.</span>
                    </span>
                </a>

                <!-- LANGUAGE -->
                <div class="flex items-center gap-1 rounded-lg border border-slate-200 p-1">

                    <a href="{{ route('language.switch', 'en') }}"
                       class="rounded-md px-3 py-1.5 text-xs font-semibold transition
                       {{ app()->getLocale() === 'en' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:bg-slate-50' }}">
                        EN
                    </a>

                    <a href="{{ route('language.switch', 'sw') }}"
                       class="rounded-md px-3 py-1.5 text-xs font-semibold transition
                       {{ app()->getLocale() === 'sw' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:bg-slate-50' }}">
                        SW
                    </a>

                </div>

            </div>
        </header>


        <!-- MAIN -->
        <main class="flex flex-1 items-center justify-center px-5 py-12 sm:px-8">

            <div class="w-full max-w-md">

                <!-- HEADING -->
                <div class="mb-8">

                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.16em] text-emerald-700">
                        {{ __('ACCOUNT ACCESS') }}
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                        {{ __('Welcome back') }}
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ __('Sign in to your account to continue to your workspace.') }}
                    </p>

                </div>


                <!-- LOGIN CARD -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_4px_20px_rgba(15,23,42,0.04)] sm:p-8">

                    <form action="/login" method="POST" class="space-y-5">

                        @csrf

                        <!-- EMAIL -->
                        <div>

                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                {{ __('Email address') }}
                            </label>

                            <div class="relative">

                                <i class="fa-regular fa-envelope pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="name@example.com"
                                    required
                                    autofocus
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                >

                            </div>

                            <x-error_handling name="email" />

                        </div>


                        <!-- PASSWORD -->
                        <div>

                            <div class="mb-2 flex items-center justify-between">

                                <label for="password" class="block text-sm font-semibold text-slate-700">
                                    {{ __('Password') }}
                                </label>

                                <a href="#"
                                   class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                                    {{ __('Forgot password?') }}
                                </a>

                            </div>

                            <div class="relative">

                                <i class="fa-solid fa-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    autocomplete="current-password"
                                    placeholder="{{ __('Enter your password') }}"
                                    required
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-3 pl-10 pr-11 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                >

                                <button
                                    type="button"
                                    id="password-toggle"
                                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 transition hover:text-slate-700"
                                    aria-label="Show password"
                                    aria-controls="password"
                                    aria-pressed="false"
                                >
                                    <i class="fa-regular fa-eye text-sm" aria-hidden="true"></i>
                                </button>

                            </div>

                            <x-error_handling name="password" />

                        </div>


                        <!-- REMEMBER ME -->
                        <div class="flex items-center justify-between pt-1">

                            <label for="remember" class="flex cursor-pointer items-center gap-2.5">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                    class="h-4 w-4 cursor-pointer rounded border-slate-300 text-emerald-700 focus:ring-emerald-700/20"
                                >

                                <span class="text-sm text-slate-600">
                                    {{ __('Keep me signed in') }}
                                </span>

                            </label>

                        </div>


                        <!-- SUBMIT -->
                        

                        <!-- ADDITIONAL BUTTON EXAMPLE (E.G. SSO / PASSKEY / ALTERNATIVE ACTION) -->
                        <button
                            type="button"
                            class="flex min-h-12 w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200 active:scale-[0.99]"
                        >
                            <i class="fa-solid fa-key text-xs text-slate-500"></i>
                            {{ __('Sign in') }}
                        </button>

                    </form>


                    <!-- REGISTER -->
                    <div class="mt-7 border-t border-slate-100 pt-6 text-center">

                        <p class="text-sm text-slate-500">
                            {{ __('Don\'t have an account?') }}

                            <a href="/signup"
                               class="font-semibold text-emerald-700 transition hover:text-emerald-800 hover:underline">
                                {{ __('Create account') }}
                            </a>
                        </p>

                    </div>

                </div>


                <!-- BOTTOM NOTE -->
                <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-400">
                    <i class="fa-solid fa-shield-halved text-emerald-700"></i>
                    <span>{{ __('Secure access to your business workspace.') }}</span>
                </div>

            </div>

        </main>


        <!-- FOOTER -->
        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex min-h-[56px] max-w-7xl items-center justify-between px-6 lg:px-10">

                <p class="text-xs text-slate-400">
                    © {{ date('Y') }} {{ config('app.name', 'Inventory') }}.
                    {{ __('All rights reserved.') }}
                </p>

                <p class="hidden text-xs text-slate-400 sm:block">
                    {{ __('Business management made simple.') }}
                </p>

            </div>
        </footer>

    </div>


    <x-form_persistence />


    <!-- PASSWORD VISIBILITY -->
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('password-toggle');

        toggleButton?.addEventListener('click', () => {

            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            toggleButton.innerHTML = isPassword
                ? '<i class="fa-regular fa-eye-slash text-sm" aria-hidden="true"></i>'
                : '<i class="fa-regular fa-eye text-sm" aria-hidden="true"></i>';

            toggleButton.setAttribute('aria-pressed', String(isPassword));

            toggleButton.setAttribute(
                'aria-label',
                isPassword ? 'Hide password' : 'Show password'
            );

        });
    </script>

</body>
</html>