<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign in | Inventory</title>
	<script src="https://kit.fontawesome.com/ef44533d17.js" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f7] text-slate-900 antialiased">
	<x-success_alert />
	<main class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
		<section class="grid w-full max-w-5xl overflow-hidden border border-[#dfe6e0] bg-white lg:grid-cols-[0.92fr_1.08fr]">
			<div class="relative hidden overflow-hidden bg-[#173b26] p-10 text-white lg:flex lg:flex-col lg:justify-between">
				<div class="absolute -right-20 -top-20 h-64 w-64 rounded-full border-[32px] border-white/10"></div>
				<div class="absolute -bottom-28 -left-20 h-72 w-72 rounded-full border-[42px] border-white/10"></div>
				<div class="relative">
					<div class="flex items-center gap-3">
						<div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20">
							<i class="fa-solid fa-boxes-stacked text-lg" aria-hidden="true"></i>
						</div>
						<span class="text-lg font-semibold tracking-wide">Inventory<span class="text-[#9bd5a6]">.</span></span>
					</div>
					<div class="mt-20 max-w-xs">
						<p class="text-sm font-medium uppercase tracking-[0.2em] text-[#b8ddbf]">Welcome back</p>
						<h2 class="mt-4 text-4xl font-semibold leading-tight tracking-tight">Everything you need, in one place.</h2>
						<p class="mt-5 text-sm leading-6 text-[#e0f0e2]/75">Manage stock, sales, purchases, and your business with clarity and confidence.</p>
					</div>
				</div>
				<p class="relative text-xs text-cyan-100/60">Secure workspace access</p>
			</div>

			<div class="p-7 sm:p-12 lg:p-16">
				<div class="mb-9 lg:hidden">
					<div class="flex items-center gap-3 text-[#173b26]">
						<div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e3f3f7]">
							<i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
						</div>
						<span class="font-semibold tracking-wide">Inventory</span>
					</div>
				</div>

				<div class="mb-8">
					<p class="text-sm font-medium text-[#638a6d]">Good to see you</p>
					<h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Sign in to your account</h1>
					<p class="mt-3 text-sm leading-6 text-slate-500">Enter your details to continue to your workspace.</p>
				</div>

				<form action="/login" method="POST" class="space-y-5">
					@csrf

					<div class="space-y-2">
						<label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
						<input
							id="email"
							name="email"
							type="email"
							autocomplete="email"
							value="{{ old('email') }}"
							required
							class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-[#9ab9a1] focus:border-[#638a6d] focus:bg-white focus:ring-4 focus:ring-[#cfe6d3]/50"
							placeholder="you@example.com"
						>
					</div>
					<x-error_handling name="email" />
					<div class="space-y-2">
						<div class="flex items-center justify-between">
							<label for="password" class="block text-sm font-medium text-slate-700">Password</label>
							<a href="#" class="text-xs font-semibold text-[#638a6d] transition hover:text-[#173b26] hover:underline">Forgot password?</a>
						</div>

						<div class="relative">
							<input
								id="password"
								name="password"
								type="password"
								autocomplete="current-password"
								required
								class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-[#9ab9a1] focus:border-[#638a6d] focus:bg-white focus:ring-4 focus:ring-[#cfe6d3]/50"
								placeholder="Enter your password"
							>
							<button id="password-toggle" type="button" class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-[#638a6d]" aria-label="Show password" aria-controls="password">
								<i class="fa-solid fa-eye" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					<x-error_handling name="password" />

					<div class="flex items-center gap-3 pt-1">
						<input
							id="remember"
							name="remember"
							type="checkbox"
							{{ old('remember') ? 'checked' : '' }}
							class="h-4 w-4 border-slate-300 text-[#638a6d] focus:ring-[#638a6d]/40"
						>
						<label for="remember" class="text-sm text-slate-600">Keep me signed in</label>
					</div>

					<button
						type="submit"
						class="w-full bg-[#173b26] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#285b38] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#9bcfa3]/40"
					>
						Sign in
					</button>

					<p class="pt-2 text-center text-sm text-slate-500">
						Don't have an account?
						<a href="/signup" class="font-semibold text-[#638a6d] transition hover:text-[#173b26] hover:underline">Create one</a>
					</p>
				</form>
			</div>
		</section>
	</main>
</body>
<x-form_persistence />
<script>
	const passInput = document.getElementById('password');
	const togglerButton = document.getElementById('password-toggle');
	const togglerIcon = togglerButton.querySelector('i');

	togglerButton.addEventListener('click', function () {
		const isHidden = passInput.type === 'password';
		passInput.type = isHidden ? 'text' : 'password';
		togglerButton.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
		togglerIcon.classList.toggle('fa-eye-slash', isHidden);
		togglerIcon.classList.toggle('fa-eye', !isHidden);
	});
</script>
</html>