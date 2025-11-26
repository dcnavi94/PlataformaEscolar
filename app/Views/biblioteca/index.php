<style>
    .category-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
        margin-top: 30px;
        padding-left: 4%;
        color: #5a5c69;
    }
    .books-row {
        display: flex;
        overflow-x: auto;
        padding: 20px 4%;
        scroll-behavior: smooth;
    }
    .books-row::-webkit-scrollbar {
        height: 8px;
    }
    .books-row::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .books-row::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .book-card {
        flex: 0 0 auto;
        width: 200px;
        margin-right: 15px;
        transition: transform 0.3s ease;
        position: relative;
        text-decoration: none; /* Remove underline from link */
        color: inherit;
    }
    .book-card:hover {
        transform: scale(1.05);
        z-index: 10;
        color: inherit;
    }
    .book-cover {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .book-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255,255,255,0.9);
        padding: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top: 1px solid #e3e6f0;
    }
    .book-card:hover .book-info {
        opacity: 1;
    }
    .book-title {
        font-size: 0.9rem;
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #4e73df;
    }
    .book-author {
        font-size: 0.8rem;
        color: #858796;
    }
</style>

<div class="container-fluid">
    <div class="pt-4 px-4">
        <h1 class="h2 mb-4 text-gray-800"><i class="bi bi-book-half text-primary me-2"></i>Biblioteca Digital</h1>
        <p class="text-gray-600">Explora nuestra colección de libros digitales.</p>
    </div>

    <?php if (empty($librosPorCategoria)): ?>
        <div class="text-center mt-5">
            <i class="bi bi-journal-x fa-4x text-gray-400"></i>
            <h3 class="mt-3 text-gray-600">No hay libros disponibles por el momento.</h3>
        </div>
    <?php else: ?>
        <?php foreach ($librosPorCategoria as $categoria): ?>
            <div class="category-section">
                <h2 class="category-title"><?= htmlspecialchars($categoria['categoria']) ?></h2>
                <div class="books-row">
                    <?php foreach ($categoria['libros'] as $libro): ?>
                        <a href="<?= BASE_URL ?>/biblioteca/detalle/<?= $libro['id_libro'] ?>" class="book-card">
                            <?php if ($libro['portada_url']): ?>
                                <img src="<?= BASE_URL . $libro['portada_url'] ?>" class="book-cover" alt="<?= htmlspecialchars($libro['titulo']) ?>">
                            <?php else: ?>
                                <div class="book-cover d-flex align-items-center justify-content-center bg-light border">
                                    <i class="bi bi-book fa-3x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="book-info">
                                <div class="book-title"><?= htmlspecialchars($libro['titulo']) ?></div>
                                <div class="book-author"><?= htmlspecialchars($libro['autor']) ?></div>
                                <div class="mt-2 text-center">
                                    <span class="btn btn-primary btn-sm rounded-circle">
                                        <i class="bi bi-eye-fill"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
