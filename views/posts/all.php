<div class="container py-5">
    <div class="row justify-content-center">
        <?php foreach ($posts as $post): ?>
            <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card bg-dark border-secondary text-light shadow w-100 aspect-ratio aspect-ratio-1x1" onclick="window.location.href='/post/single/<?= $post->idPost ?>'">
                    <img src="<?= $post->fileExt ?>" class="card-img-top" alt="Preview" style="object-fit: scale-down; height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title"><?= htmlspecialchars($post->name) ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>    
    </div>
