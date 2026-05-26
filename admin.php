<?php

require_once __DIR__ . '/src/SlideRepository.php';

$errorMessage = '';
$repository = null;
$availableImages = [
    'files/images/DL-Communication.jpg',
    'files/images/DL-Learning-1.jpg',
    'files/images/DL-Technology.jpg',
    'files/images/DL-communication.svg',
    'files/images/DL-learning.svg',
    'files/images/DL-technology.svg',
];
$topics = ['Learning', 'Technology', 'Communication'];
$message = $_GET['message'] ?? '';

try {
    $repository = new SlideRepository();
} catch (Throwable $exception) {
    $errorMessage = $exception->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $repository !== null) {
    $action = $_POST['action'] ?? '';
    $slideData = [
        'id' => $_POST['id'] ?? null,
        'topic' => $_POST['topic'] ?? '',
        'title' => $_POST['title'] ?? '',
        'image_path' => $_POST['image_path'] ?? '',
        'sort_order' => $_POST['sort_order'] ?? 0,
    ];

    try {
        if ($action === 'save') {
            $repository->saveSlide($slideData);
            header('Location: admin.php?message=' . urlencode('Slide saved successfully.'));
            exit;
        }

        if ($action === 'delete' && !empty($slideData['id'])) {
            $repository->removeSlide((int) $slideData['id']);
            header('Location: admin.php?message=' . urlencode('Slide deleted successfully.'));
            exit;
        }
    } catch (Throwable $exception) {
        $errorMessage = 'A database error occurred: ' . $exception->getMessage();
    }
}

if ($repository !== null) {
    $allSlides = $repository->fetchAllSlides();
    $editingSlide = null;
    if (!empty($_GET['edit'])) {
        $editingSlide = $repository->fetchSlideById((int) $_GET['edit']);
    }
} else {
    $allSlides = [];
    $editingSlide = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WPoets Admin CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Admin Panel</h1>
        <a href="index.php" class="btn btn-outline-light btn-sm">View public slider</a>
    </div>
</header>
<main class="container">
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger">A database error occurred: <?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <div class="row gy-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">Create or update a slide</div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($editingSlide['id'] ?? ''); ?>">
                        <input type="hidden" name="action" value="save">

                        <div class="mb-3">
                            <label class="form-label" for="topic">Topic</label>
                            <select class="form-select" id="topic" name="topic" required>
                                <?php foreach ($topics as $topicName): ?>
                                    <option value="<?php echo htmlspecialchars($topicName); ?>"
                                        <?php echo isset($editingSlide['topic']) && $editingSlide['topic'] === $topicName ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($topicName); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($editingSlide['title'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="image_path">Image path</label>
                            <select class="form-select" id="image_path" name="image_path" required>
                                <?php foreach ($availableImages as $imageFile): ?>
                                    <option value="<?php echo htmlspecialchars($imageFile); ?>"
                                        <?php echo isset($editingSlide['image_path']) && $editingSlide['image_path'] === $imageFile ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars(basename($imageFile)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="sort_order">Sort order</label>
                            <input class="form-control" id="sort_order" name="sort_order" type="number" value="<?php echo htmlspecialchars($editingSlide['sort_order'] ?? 0); ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Save slide</button>
                            <a class="btn btn-outline-secondary" href="admin.php">Reset form</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">Existing slides</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Topic</th>
                                    <th>Title</th>
                                    <th>Order</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allSlides as $slide): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($slide['topic']); ?></td>
                                        <td><?php echo htmlspecialchars($slide['title']); ?></td>
                                        <td><?php echo htmlspecialchars($slide['sort_order']); ?></td>
                                        <td class="text-end action-cell">
                                            <div class="admin-action-buttons d-inline-flex flex-nowrap gap-2">
                                                <a href="admin.php?edit=<?php echo htmlspecialchars($slide['id']); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <form method="post" class="d-inline-flex flex-nowrap" onsubmit="return confirm('Delete this slide?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($slide['id']); ?>">
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($allSlides)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No slides created yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
