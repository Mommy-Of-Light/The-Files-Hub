<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card bg-dark border-secondary text-light shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Register</h2>

                    <form method="post" action="/register" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input
                                    type="text"
                                    class="form-control bg-dark text-light border-secondary"
                                    id="firstName"
                                    name="firstName"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input
                                    type="text"
                                    class="form-control bg-dark text-light border-secondary"
                                    id="lastName"
                                    name="lastName"
                                    required
                                >
                            </div>
                        </div>

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
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control bg-dark text-light border-secondary"
                                id="email"
                                name="email"
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

                        <div class="mb-4">
                            <label for="pfp" class="form-label">Profile Picture</label>
                            <input
                                type="file"
                                class="form-control bg-dark text-light border-secondary"
                                id="pfp"
                                name="pfp"
                                accept="image/*"
                            >
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                Create Account
                            </button>
                        </div>
                    </form>
                    <a href="/login" class="d-block text-center mt-3 text-light">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
