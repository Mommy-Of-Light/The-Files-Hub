<div class="container py-5">
    <h4 class="text-center">User list</h4>

    <div class="table-container" style="max-height: 500px; overflow-y: auto; --webkit-overflow-scrolling: touch;">
        <table class="table table-striped table-dark align-middle mb-4">
            <thead class="table-dark position-sticky top-0" style="z-index: 10;">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Username</th>
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
                        <td class="actions d-flex flex-row flex-nowrap gap-2">
                            <?php if ($user->getRoleName($user->getRoles()) === 'opperator' || $user->getRoleName($user->getRoles()) === 'creator'): ?>
                                <form method="POST" action="/mod/user/promote/<?= $user->idUser ?>">
                                    <button class="btn btn-warning btn-sm"
                                        onclick="return confirm('Promote this user to <?= htmlspecialchars($user->getRoleName($user->getRoles())) ?>?')">
                                        Promote
                                    </button>
                                </form>
                                <form method="POST" action="/mod/user/demote/<?= $user->idUser ?>">
                                    <button class="btn btn-warning btn-sm"
                                        onclick="return confirm('Demote this user to regular user?')">
                                        Demote
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" action="/mod/user/delete/<?= $user->idUser ?>">
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this user?')">
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
<div class="container py-5">
    <h4 class="text-center">Posts list</h4>
    
    <div class="table-container" style="max-height: 500px; overflow-y: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-striped table-dark align-middle mb-4">
            <thead class="table-dark position-sticky top-0" style="z-index: 10;">
                <tr>
                    <th>Créateur</th>
                    <th>Date de création</th>
                    <th style="width: 150px;">Action</th>
                </tr>
            </thead>
            <!-- <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post) ?></td>
                        <td><?= htmlspecialchars($post) ?></td>
                        <td>
                            <form method="POST" action="/mod/post/delete/<?= $post ?>">
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Supprimer ce post ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody> -->
        </table>
    </div>
</div>