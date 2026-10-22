<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign up</title>
    <script src="https://kit.fontawesome.com/ef44533d17.js" crossorigin="anonymous"></script>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f7] text-slate-900 antialiased">
	<main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
		<section class="w-full max-w-md border border-[#dfe6e0] bg-white p-8 sm:p-10">
			<div class="mb-8 text-center">
				<h1 class="text-2xl font-semibold tracking-tight text-slate-900">Create account</h1>
				<p class="mt-2 text-sm text-slate-500">Start your free account.</p>
			</div>

			<form action="{{ route('signup.store') }}" method="POST" class="space-y-5">
				@csrf

				<div class="space-y-2">
					<label for="company_name" class="block text-sm font-medium text-slate-700">Business name</label>
					<input
						id="company_name"
						name="company_name"
						type="text"
						value="{{ old('company_name') }}"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="Your business name"
					>
				</div>
				<div class="space-y-2">
					<label for="name" class="block text-sm font-medium text-slate-700">First name</label>
					<input
						id="name"
						name="first_name"
						type="text"
						autocomplete="name"
						value="{{ old('first_name') }}"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="Your first name"
					>
				</div>
                <div class="space-y-2">
					<label for="last_name" class="block text-sm font-medium text-slate-700">Last name</label>
					<input
						id="last_name"
						name="last_name"
						type="text"
						autocomplete="name"
						value="{{ old('last_name') }}"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="Your last name"
					>
				</div>

				<div class="space-y-2">
					<label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
					<input
						id="email"
						name="email"
						type="email"
						autocomplete="email"
						value="{{ old('email') }}"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="you@example.com"
					>
				</div>
                <x-error_handling name="email" />
                <div class="space-y-2">
					<label for="phone" class="block text-sm font-medium text-slate-700">Phone Number</label>
					<input
						id="phone"
						name="phone"
						type="tel"
						autocomplete="tel"
						value="{{ old('phone') }}"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="your phone number"
					>
				</div>
				<x-error_handling name="phone" />
				<div class="space-y-2">
					<label for="password" class="block text-sm font-medium text-slate-700">Password</label>
					<div class="relative">
                        <input
						id="password"
						name="password"
						type="password"
						autocomplete="new-password"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="Create a password"
					>
					<button type="button" class="absolute inset-y-0 right-3 flex items-center text-slate-500 transition hover:text-slate-700" aria-label="Show password">
                        <i class="fa-solid fa-eye"></i>
					</button>
					</div>
					
				</div>

                <div class="space-y-2">
					<label for="password" class="block text-sm font-medium text-slate-700">Confirm Password</label>
					<div class="relative">
                        <input
						id="password"
						name="password_confirmation"
						type="password"
						autocomplete="new-password"
						required
						class="w-full border border-[#d7e1d9] bg-[#fbfcfb] px-3 py-2.5 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#638a6d] focus:bg-white focus:ring-2 focus:ring-[#cfe6d3]/50"
						placeholder="Create a password"
					>
					<button type="button" class="absolute inset-y-0 right-3 flex items-center text-slate-500 transition hover:text-slate-700" aria-label="Show password">
                        <i class="fa-solid fa-eye"></i>
					</button>
					</div>
					
				</div>
                <x-error_handling name="password" />


				<button
					type="submit"
					class="w-full bg-[#173b26] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#285b38] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#9bcfa3]/40"
				>
					Create account
				</button>

				<p class="text-center text-sm text-slate-500">
					Already have an account?
					<a href="/signin" class="font-medium text-[#638a6d] hover:text-[#173b26] hover:underline">Sign in</a>
				</p>
			</form>
		</section>
	</main>
</body>
<x-form_persistence />
<script>
	//password visibility toggle
	const passInput=document.getElementById('password');
	const togglerbtn=document.querySelector('.fa-eye');
	togglerbtn.addEventListener('click',function(){
		const type=passInput.getAttribute('type')==='password'?'text':'password';
		passInput.setAttribute('type',type);
		togglerbtn.classList.toggle('fa-eye-slash');
	})


</script>
</html>
