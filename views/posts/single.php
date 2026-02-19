<?php
$userId = $_SESSION['user']->getIdUser() ?? null;
$isCreator = $userId === $post->getCreator()->idUser;

$filePath = $post->getFileLink();

$fullPath = __DIR__ . '/../../public' . $filePath;

$fileSize = file_exists($fullPath)
    ? round(filesize($fullPath) / 1024, 2) . ' KB'
    : 'N/A';

$fileExt = pathinfo($filePath, PATHINFO_EXTENSION);
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card bg-dark text-light border-secondary shadow-lg p-4">
                <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                        <?php elseif (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                <div class="row mb-4 align-items-center">

                    <div class="col-md-8">
                        <h4><?= htmlspecialchars($post->name) ?></h4>
                        <p class="mb-1"><strong>Type:</strong> <?= $fileExt ?></p>
                        <p class="mb-1"><strong>Taille:</strong> <?= $fileSize ?></p>
                    </div>

                    <div class="col-md-4 text-center">
                        <a 
                            href="<?= htmlspecialchars($post->getFileLink()) ?>" 
                            class="btn btn-info btn-lg w-100"
                            download
                        >
                            Telecharger
                        </a>
                    </div>
                </div>

                <hr class="border-secondary">

                <div class="mb-3">
                    <h5>
                        By <?= $post->getCreator()->userName; ?>
                    </h5>
                </div>

                <div class="border border-secondary rounded p-3 mb-4"
                     style="max-height: 300px; overflow-y: auto;">
                    <?= nl2br(htmlspecialchars($post->getDescription())) ?>
                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <?php if (!$isCreator): ?>
                        <div class="d-flex gap-3">

                            <form action="/post/single/<?= $post->getPost() ?>/like" method="POST">
                                <button type="submit" class="btn <?= $post->getAction() === 1 ? 'btn-success' : 'btn-outline-success' ?>">
                                    Like (<?= $post->getLikes(); ?>)
                                </button>
                            </form>

                            <form action="/post/single/<?= $post->getPost() ?>/dislike" method="POST">
                                <button type="submit" class="btn <?= $post->getAction() === 0 ? 'btn-danger' : 'btn-outline-danger' ?>">
                                    Dislike
                                </button>
                            </form>

                        </div>
                    <?php endif; ?>

                    <?php if ($isCreator): ?>
                        <div class="d-flex gap-3">
                            <a 
                                href="/post/single/<?= $post->getPost() ?>/edit" 
                                class="btn btn-warning"
                            >
                                Modifier
                            </a>

                            <form 
                                action="/post/single/<?= $post->getPost() ?>/delete" 
                                method="POST"
                                onsubmit="return confirm('Supprimer ce post?');"
                            >
                                <button type="submit" class="btn btn-danger">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</div>
