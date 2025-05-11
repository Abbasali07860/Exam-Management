<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="form-wrapper">
    @include('layouts.toastr')
    <form class="form-box" action="{{ route('register.form') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h2>Register</h2>
        <div class="form-group">
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password">
        </div>

        <div class="form-group">
            <input type="number" name="mobile" placeholder="Mobile Number" value="{{ old('mobile') }}">
        </div>

        <label class="checkbox">
            <input type="checkbox" name="remember"> Remember Me
        </label>

        <button type="submit">Create Account</button>

        <p class="login-link">
            Already have an account?
            <a href="{{ route('login') }}">Sign In</a>
        </p>
    </form>
</div>

</body>
</html>
