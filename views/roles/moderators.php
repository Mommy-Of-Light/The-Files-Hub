<div class="container">
    <h4 class="text-center">User list</h4>

    <div class="table-responsive"
        style="max-height: 40vh; min-height: 4 0vh; overflow-y: auto; --webkit-overflow-scrolling: touch;"
        >
        <table class="table table-striped table-dark align-middle mb-4">
            <thead class="table-dark position-sticky top-0" style="z-index: 10;">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th style="width: 230px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->getFirstName()) ?></td>
                        <td><?= htmlspecialchars($user->getLastName()) ?></td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td><?= htmlspecialchars($user->getUsername()) ?></td>
                        <td><?= htmlspecialchars($user->getRoleName($user->getRoles())) ?></td>
                        <td class="actions d-flex flex-row flex-nowrap gap-2">
                            <form method="POST" action="/mod/user/promote/<?= $user->idUser ?>">
                                <button
                                    class="btn <?= ($user->getRoles() == 2 || $user == $current) ? 'btn-outline-warning' : 'btn-warning' ?> btn-sm"
                                    onclick="return confirm('Promote this user to <?= htmlspecialchars($user->getRoleName($user->getRoles() + 1)) ?>?')"
                                    <?= ($user->getRoles() == 2 || $user == $current) ? 'disabled' : '' ?>>
                                    Promote
                                </button>
                            </form>
                            <form method="POST" action="/mod/user/demote/<?= $user->idUser ?>">
                                <button
                                    class="btn <?= ($user->getRoles() == 0 || $user == $current) ? 'btn-outline-warning' : 'btn-warning' ?> btn-sm"
                                    onclick="return confirm('Demote this user to <?= htmlspecialchars($user->getRoleName($user->getRoles() - 1)) ?>?')"
                                    <?= ($user->getRoles() == 0 || $user == $current) ? 'disabled' : '' ?>>
                                    Demote
                                </button>
                            </form>
                            <form method="POST" action="/mod/user/delete/<?= $user->idUser ?>">
                                <button class="btn <?= ($user == $current) ? 'btn-outline-danger' : 'btn-danger' ?> btn-sm"
                                    onclick="return confirm('Delete this user?')" <?= ($user == $current) ? 'disabled' : '' ?>>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="container">
    <h4 class="text-center">Posts list</h4>

    <div class="table-container"
        style="max-height: 35vh; min-height: 35vh; overflow-y: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-striped table-dark align-middle mb-4">
            <thead class="table-dark position-sticky top-0" style="z-index: 10;">
                <tr>
                    <th style="width: 200px;">Creator</th>
                    <th>Title</th>
                    <th style="width: 130px;">Link</th>
                    <th style="width: 100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars(TheFileHub\Models\User::findById($post->idCreator)->userName) ?></td>
                        <td><?= htmlspecialchars($post->name) ?></td>
                        <td><a href="/post/single/<?= $post->idPost ?>">To the post</a></td>
                        <td>
                            <form method="POST" action="/mod/post/delete/<?= $post->idPost ?>">
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce post ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

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
</div>