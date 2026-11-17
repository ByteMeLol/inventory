<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    <title>{{ __('Sign up') }} | {{ config('app.name', 'Inventory') }}</title>

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
        <main class="flex flex-1 items-center justify-center px-5 py-8 sm:px-8">

            <div class="w-full max-w-lg">

                <!-- HEADING -->
                <div class="mb-6">

                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-emerald-700">
                        {{ __('GET STARTED') }}
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ __('Create account') }}
                    </h1>

                    <p class="mt-1 text-sm leading-5 text-slate-500">
                        {{ __('Start your free account and set up your workspace.') }}
                    </p>

                </div>


                <!-- SIGNUP CARD -->
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-[0_4px_20px_rgba(15,23,42,0.04)] sm:p-7">

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50/70 p-3 text-xs text-rose-800" role="alert">
                            <p class="font-semibold">{{ __('Please correct the highlighted fields and try again.') }}</p>
                            <ul class="mt-1 list-inside list-disc space-y-0.5 text-[11px] text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('signup.store') }}" method="POST" class="space-y-3.5">

                        @csrf

                        <!-- BUSINESS NAME -->
                        <div>
                            <label for="company_name" class="mb-1 block text-xs font-semibold text-slate-700">
                                {{ __('Business name') }}
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-building pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                <input
                                    type="text"
                                    name="company_name"
                                    id="company_name"
                                    value="{{ old('company_name') }}"
                                    required
                                    autofocus
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                    placeholder="{{ __('Your business name') }}"
                                >
                            </div>
                            <x-error_handling name="company_name" />
                        </div>


                        <!-- FIRST & LAST NAME -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="first_name" class="mb-1 block text-xs font-semibold text-slate-700">
                                    {{ __('First name') }}
                                </label>

                                <div class="relative">
                                    <i class="fa-regular fa-user pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                    <input
                                        type="text"
                                        name="first_name"
                                        id="first_name"
                                        value="{{ old('first_name') }}"
                                        autocomplete="given-name"
                                        required
                                        class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                        placeholder="{{ __('First name') }}"
                                    >
                                </div>
                                <x-error_handling name="first_name" />
                            </div>

                            <div>
                                <label for="last_name" class="mb-1 block text-xs font-semibold text-slate-700">
                                    {{ __('Last name') }}
                                </label>

                                <div class="relative">
                                    <i class="fa-regular fa-user pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                    <input
                                        type="text"
                                        name="last_name"
                                        id="last_name"
                                        value="{{ old('last_name') }}"
                                        autocomplete="family-name"
                                        required
                                        class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                        placeholder="{{ __('Last name') }}"
                                    >
                                </div>
                                <x-error_handling name="last_name" />
                            </div>
                        </div>


                        <!-- EMAIL & PHONE -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="email" class="mb-1 block text-xs font-semibold text-slate-700">
                                    {{ __('Work email') }}
                                </label>

                                <div class="relative">
                                    <i class="fa-regular fa-envelope pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                        class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                        placeholder="you@company.com"
                                    >
                                </div>
                                <x-error_handling name="email" />
                            </div>

                            <div>
                                <label for="phone" class="mb-1 block text-xs font-semibold text-slate-700">
                                    {{ __('Phone number') }}
                                </label>

                                <div class="relative">
                                    <i class="fa-solid fa-phone pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                    <input
                                        type="tel"
                                        name="phone"
                                        id="phone"
                                        value="{{ old('phone') }}"
                                        autocomplete="tel"
                                        required
                                        class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                        placeholder="+255..."
                                    >
                                </div>
                                <x-error_handling name="phone" />
                            </div>
                        </div>


                        <!-- PASSWORD -->
                        <div>
                            <label for="password" class="mb-1 block text-xs font-semibold text-slate-700">
                                {{ __('Create password') }}
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    autocomplete="new-password"
                                    required
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                    placeholder="{{ __('At least 8 characters') }}"
                                >

                                <button
                                    type="button"
                                    id="password-toggle"
                                    class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-slate-400 transition hover:text-slate-700"
                                    aria-label="Show password"
                                    aria-controls="password"
                                    aria-pressed="false"
                                >
                                    <i class="fa-regular fa-eye text-xs" aria-hidden="true"></i>
                                </button>
                            </div>
                            <x-error_handling name="password" />
                        </div>


                        <!-- CONFIRM PASSWORD -->
                        <div>
                            <label for="password_confirmation" class="mb-1 block text-xs font-semibold text-slate-700">
                                {{ __('Confirm password') }}
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    autocomplete="new-password"
                                    required
                                    class="block w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                                    placeholder="{{ __('Repeat your password') }}"
                                >

                                <button
                                    type="button"
                                    id="password-confirm-toggle"
                                    class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-slate-400 transition hover:text-slate-700"
                                    aria-label="Show password"
                                    aria-controls="password_confirmation"
                                    aria-pressed="false"
                                >
                                    <i class="fa-regular fa-eye text-xs" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>


                        <!-- SUBMIT BUTTON -->
                        <button
                            type="submit"
                            class="flex min-h-11 w-full items-center justify-center border border-slate-300 bg-white gap-2 rounded-lg bg-[#123c32] px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-[#0d3028] focus:outline-none focus:ring-4 focus:ring-emerald-800/20 active:scale-[0.99] mt-4"
                        >
                            {{ __('Create account') }}
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                    </form>


                    <!-- SIGN IN LINK -->
                    <div class="mt-5 border-t border-slate-100 pt-4 text-center">

                        <p class="text-xs text-slate-500">
                            {{ __('Already have an account?') }}

                            <a href="/signin"
                               class="font-semibold text-emerald-700 transition hover:text-emerald-800 hover:underline">
                                {{ __('Sign in') }}
                            </a>
                        </p>

                    </div>

                </div>


                <!-- BOTTOM NOTE -->
                <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-400">
                    <i class="fa-solid fa-shield-halved text-emerald-700"></i>
                    <span>{{ __('Secure access to your business workspace.') }}</span>
                </div>

            </div>

        </main>


        <!-- FOOTER -->
        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex min-h-[48px] max-w-7xl items-center justify-between px-6 lg:px-10">

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


    <!-- PASSWORD VISIBILITY TOGGLE SCRIPT -->
    <script>
        function setupPasswordToggle(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(toggleId);

            toggleButton?.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';

                toggleButton.innerHTML = isPassword
                    ? '<i class="fa-regular fa-eye-slash text-xs" aria-hidden="true"></i>'
                    : '<i class="fa-regular fa-eye text-xs" aria-hidden="true"></i>';

                toggleButton.setAttribute('aria-pressed', String(isPassword));
                toggleButton.setAttribute(
                    'aria-label',
                    isPassword ? 'Hide password' : 'Show password'
                );
            });
        }

        setupPasswordToggle('password', 'password-toggle');
        setupPasswordToggle('password_confirmation', 'password-confirm-toggle');
    </script>

</body>
</html>