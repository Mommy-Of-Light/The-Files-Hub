<nav class="navbar navbar-expand-lg bg-transparent text-light">
    <div class="container-fluid">
        <a class="navbar-brand text-light" href="/">The File Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggle"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-toggle">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/secret">_</a>
                </li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light"
                            href="/profile"><?= htmlspecialchars($_SESSION['user']->userName) ?></a>
                    </li>
                    <?php if (in_array($_SESSION['user']->getRoleName($_SESSION['user']->getRoles()), ['admin', 'opperator', 'creator'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="/mod/<?= htmlspecialchars($_SESSION['user']->getRoleName($_SESSION['user']->getRoles() == 3 ? 2 : $_SESSION['user']->getRoles())) ?>-dashboard">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/posts">All Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/post/new">New Post</a>
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