<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Send Password Reset Link</button>
</form>
