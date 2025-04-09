<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ request('email') }}"> <!-- Hidden email input -->

   

    <!-- Password Input -->
    <label for="password">New Password</label>
    <input type="password" id="password" name="password" required>
    @if ($errors->has('password'))
        <div class="error">{{ $errors->first('password') }}</div>
    @endif

    <!-- Password Confirmation Input -->
    <label for="password_confirmation">Confirm Password</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>
    @if ($errors->has('password_confirmation'))
        <div class="error">{{ $errors->first('password_confirmation') }}</div>
    @endif

    <!-- Success Message -->
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <!-- General Error -->
    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <button type="submit">Reset Password</button>
</form>
