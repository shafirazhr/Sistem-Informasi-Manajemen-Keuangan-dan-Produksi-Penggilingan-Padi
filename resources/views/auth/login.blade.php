<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body style="background:#f5f5f5;">

<div class="container-fluid" style="max-width: 1200px">
    <div class="row justify-content-center mt-5">

        <div class="col-md-5">
            <div class="card p-4 shadow-sm">

                <!-- LOGO -->
                <img src="{{ asset('img/logo.jpg') }}" 
                     alt="Logo" width="250" class="mx-auto d-block mb-3">

                <h3 class="text-center mb-3">Login</h3>

                <!-- ERROR -->
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>

                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                <i class="bi bi-eye" id="icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="reset" class="btn btn-secondary w-100">Batal</button>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
function togglePassword() {
    var input = document.getElementById("password");
    var icon = document.getElementById("icon");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}
</script>

</body>
</html>