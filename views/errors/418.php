<div style=" display: flex; justify-content: center; align-items: center; background-color: black; height: 100vh;">
    <a href="<?= isset($_SESSION['user']) ? '/' : '/login' ?>">
        <img src="https://http.cat/418" alt="Erreur 418"
            style="max-width: 90vw; max-height: 90vh; width: 90vw; height: 90vh; object-fit: contain; cursor: pointer;">
    </a>
</div>