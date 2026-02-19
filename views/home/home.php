<div class="container">
    <h1 class="text-center">Top 5 Most Liked Posts</h1>
    <div class="row justify-content-center" style="overflow-y: scroll; -webkit-overflow-scrolling: touch; max-height: 75vh">
        <?php foreach ($topPosts as $index => $post):  if ($index >= 5) return ?>
            <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card bg-dark border-secondary text-light shadow w-100 aspect-ratio aspect-ratio-1x1"
                    onclick="window.location.href='/post/single/<?= $post->idPost ?>'">
                    <img src="<?= $post->fileExt ?>" class="card-img-top" alt="Preview"
                        style="object-fit: scale-down; height: 100px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title">
                            <?= htmlspecialchars($post->name) ?>
                        </h5>
                        <p style="font-size: 10px;">(<?= $post->likes ?> likes)</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>