<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card bg-dark border-secondary text-light shadow">
                <div class="card-body text-center">

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

                    <img
                        src="<?= $user->getProfilePicture()
                                    ? '/assets/pfp/' . htmlspecialchars($user->getProfilePicture())
                                    : '/assets/pfp/default.png'
                                ?>"
                        alt="Profile Picture"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit: cover;">

                    <h3 class="mb-0"><?= htmlspecialchars($user->getUsername()) ?></h3>
                    <div class="progress bg-secondary" style="height: 25px;">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: <?= min(100, ($user->getXp() / $nextLevelXpRequierd) * 100) ?>%;"
                            aria-valuenow="<?= min(100, ($user->getXp() / $nextLevelXpRequierd) * 100) ?>"
                            aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                        <span class="progress-bar-text position-absolute start-50 translate-middle-x text-light" style="font-size: 1rem !important;">
                            Level <?= htmlspecialchars($user->getLevel()) ?> -
                            <?= htmlspecialchars($user->getXp()) ?> /
                            <?= htmlspecialchars($nextLevelXpRequierd) ?> XP
                        </span>
                    </div>

                    <hr class="border-secondary">

                    <div class="text-start mb-4">
                        <p><strong>Full Name:</strong>
                            <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?>
                        </p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
                        <p><strong>Username:</strong> <?= htmlspecialchars($user->getUsername()) ?></p>
                        <p><strong>Roles:</strong> <?= htmlspecialchars($user->getRoleName()) ?></p>
                    </div>

                    <form
                        method="post"
                        action="/profile/update-pfp"
                        enctype="multipart/form-data"
                        class="mb-4">
                        <div class="mb-3 text-start">
                            <label for="pfp" class="form-label">Update Profile Picture</label>
                            <input
                                type="file"
                                class="form-control bg-dark text-light border-secondary"
                                id="pfp"
                                name="pfp"
                                accept="image/*"
                                required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                Update Picture
                            </button>
                        </div>
                    </form>

                    <hr class="border-secondary">

                    <form
                        method="post"
                        action="/profile/delete"
                        onsubmit="return confirm('Are you sure? This action is irreversible.');">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                Delete Account
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>