<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card bg-dark border-secondary text-light shadow">
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-4">
                            <input type="file" name="file" id="file"
                                class="form-control bg-dark text-light border-secondary" required>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="form-label">Title</label>
                            <input type="text" name="name" id="name"
                                class="form-control bg-dark text-light border-secondary" placeholder="Title" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="bg-dark text-light" name="desc" id="desc"
                                style="width: 100%; height: 200px; resize: none;" placeholder="Desctription"
                                required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                Publish
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>