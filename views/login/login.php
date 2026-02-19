<div class="container">
    <div class="row justify-content-center" style="overflow-y: scroll; -webkit-overflow-scrolling: touch; max-height: 80vh">
        <div class="col-md-6 col-lg-4">
            <div class="card bg-dark border-secondary text-light shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Login</h2>

                    <form method="post" action="/login">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input
                                type="text"
                                class="form-control bg-dark text-light border-secondary"
                                id="username"
                                name="username"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control bg-dark text-light border-secondary"
                                id="password"
                                name="password"
                                required
                            >
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>
                        </div>
                    </form>
                    <a href="/register" class="d-block text-center mt-3 text-light">Don't have an account? Register</a>

                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
