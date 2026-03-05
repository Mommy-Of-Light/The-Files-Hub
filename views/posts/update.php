<div class="container">
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
                        <input type="hidden" name="idPost" value="<?= htmlspecialchars($post->getPost()) ?>">
                        <img src="<?= $post->getFileLink() ?>" class="card-img-top" alt="Preview" style="object-fit: scale-down; height: 100px;">
                        <div class="mb-4">
                            <input type="text" name="file" id="file"
                                class="form-control bg-dark text-light border-secondary" disabled value="<?= htmlspecialchars(explode('/', $post->getFileLink())[3]) ?>">
                        </div>
                        <div class="mb-4">
                            <label for="name" class="form-label">Title</label>
                            <input type="text" name="name" id="name"
                                class="form-control bg-dark text-light border-secondary" placeholder="Title" required value="<?= htmlspecialchars($post->name) ?>">
                        </div>
                        <div class="mb-3">
                            <textarea class="bg-dark text-light" name="desc" id="desc"
                                style="width: 100%; height: 200px; resize: none;" placeholder="Desctription"
                                required><?= htmlspecialchars($post->getDescription()) ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-grow-1">
                                Update
                            </button>
                            <a href="/post/single/<?= htmlspecialchars($post->getPost()) ?>" class="btn btn-danger flex-grow-1">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>