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
                        src="<?= $user->profilePicture
                            ? '/assets/pfp/' . htmlspecialchars($user->profilePicture)
                            : '/assets/pfp/default.png'
                        ?>"
                        alt="Profile Picture"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit: cover;"
                    >

                    <h3 class="mb-0"><?= htmlspecialchars($user->userName) ?></h3>

                    <hr class="border-secondary">

                    <div class="text-start mb-4">
                        <p><strong>Full Name:</strong>
                            <?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?>
                        </p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
                        <p><strong>Username:</strong> <?= htmlspecialchars($user->userName) ?></p>
                    </div>

                    <form
                        method="post"
                        action="/profile/update-pfp"
                        enctype="multipart/form-data"
                        class="mb-4"
                    >
                        <div class="mb-3 text-start">
                            <label for="pfp" class="form-label">Update Profile Picture</label>
                            <input
                                type="file"
                                class="form-control bg-dark text-light border-secondary"
                                id="pfp"
                                name="pfp"
                                accept="image/*"
                                required
                            >
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
                        onsubmit="return confirm('Are you sure? This action is irreversible.');"
                    >
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
