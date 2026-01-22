<nav class="navbar navbar-expand-lg bg-transparent text-light">
    <div class="container-fluid">
        <a class="navbar-brand text-light" href="/">The File Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggle"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-toggle">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light"
                            href="/profile"><?= htmlspecialchars($_SESSION['user']->username) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/register">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>