<x-guest-layout>
    <div class="flex flex-col justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <div class="mb-4 text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mx-auto">
            </div>

            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
                    <input id="email" class="block mt-1 w-full border rounded py-2 px-3" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full border rounded py-2 px-3" type="password" name="password" required autocomplete="current-password">
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <input type="checkbox" id="remember_me" name="remember" class="mr-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-500 hover:text-blue-700" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
