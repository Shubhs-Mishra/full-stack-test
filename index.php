<?php

require_once __DIR__ . '/src/SlideRepository.php';

$errorMessage = '';
$topics = [];
$slides = [];
$activeSlide = null;
$selectedTopic = 'Communication';
$tabIcons = [
    'Learning'      => 'files/images/DL-learning.svg',
    'Technology'    => 'files/images/DL-technology.svg',
    'Communication' => 'files/images/DL-communication.svg',
];

try {
    $repository = new SlideRepository();
    $topics = $repository->fetchTopics();

    if (empty($topics)) {
        $topics = [['topic' => 'Learning'], ['topic' => 'Technology'], ['topic' => 'Communication']];
    }

    $topicList = array_column($topics, 'topic');
    $preferredOrder = ['Learning', 'Technology', 'Communication'];
    $topicList = array_values(array_intersect($preferredOrder, $topicList));
    if (empty($topicList)) {
        $topicList = $preferredOrder;
    }

    $selectedTopic = $_GET['topic'] ?? $topicList[0] ?? 'Learning';
    $slides = $repository->fetchSlidesByTopic($selectedTopic);
    $activeSlide = $slides[0] ?? null;
} catch (Throwable $exception) {
    $errorMessage = $exception->getMessage();
    $topicList = ['Learning', 'Technology', 'Communication'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WPoets Slider Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ── Page header (dark nav bar) ──────────────────────────── -->
<header class="bg-dark text-white py-3">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <span class="h5 mb-0">WPoets Slider Showcase</span>
            <a href="admin.php" class="btn btn-outline-light btn-sm">Admin CRUD</a>
        </div>
    </div>
</header>

<!-- ── Hero title ───────────────────────────────────────────── -->
<div class="page-hero">
    <h1>DelphianLogic in Action</h1>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo</p>
</div>

<!-- ── Main slider block ────────────────────────────────────── -->
<main class="container-fluid px-4 pb-5">

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger mb-3">
            A database error occurred: <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <!--
        Three-column flex layout:
          Col 1  – topics panel  (27 %)
          Col 2  – slider panel  (37 %)
          Col 3  – image panel   (flex:1, ~36 %)
    -->
    <div class="slider-outer">

        <!-- ── COL 1: Topics ──────────────────────────────────── -->
        <section class="topics-panel" aria-label="Topic navigation">
            <nav class="nav flex-column" role="tablist" aria-label="Topic tabs">
                <?php foreach ($topicList as $topicName): ?>
                    <a href="?topic=<?php echo urlencode($topicName); ?>"
                       class="topic-tab <?php echo $topicName === $selectedTopic ? 'active' : ''; ?>"
                       role="tab"
                       aria-selected="<?php echo $topicName === $selectedTopic ? 'true' : 'false'; ?>">
                        <img src="<?php echo htmlspecialchars($tabIcons[$topicName] ?? 'files/images/arrow-right.svg'); ?>"
                             alt=""
                             class="topic-icon">
                        <span><?php echo htmlspecialchars($topicName); ?></span>
                        <span class="toggle-btn" aria-hidden="true">
                            <img src="<?php echo $topicName === $selectedTopic ? 'files/images/minus-01.svg' : 'files/images/plus-01.svg'; ?>"
                                 alt="<?php echo $topicName === $selectedTopic ? 'close' : 'open'; ?>"
                                 class="toggle-icon">
                        </span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </section>

        <!-- ── COL 2: Slider content ──────────────────────────── -->
        <section class="slider-panel" aria-label="Slide content">

            <!-- hidden prev/next kept for JS compatibility -->
            <button id="previous-slide" type="button" class="d-none" aria-hidden="true">Prev</button>
            <button id="next-slide"     type="button" class="d-none" aria-hidden="true">Next</button>

            <div class="slider-items"
                 id="slider-items"
                 data-topic="<?php echo htmlspecialchars($selectedTopic); ?>">

                <?php foreach ($slides as $slideIndex => $slide): ?>
                    <article class="slider-card<?php echo $slideIndex === 0 ? ' active' : ''; ?>"
                             data-index="<?php echo $slideIndex; ?>"
                             data-image="<?php echo htmlspecialchars($slide['image_path']); ?>">

                        <!-- Square-cornered badge (CSS: border-radius:0) -->
                        <span class="slider-badge">Digital Learning Infrastructure</span>

                        <h3><?php echo htmlspecialchars($slide['title']); ?></h3>

                        <a href="#" class="learn-more-link">Learn More &nbsp;→</a>

                        <!-- "Slide X of Y" hidden via CSS -->
                        <p class="slide-counter">
                            Slide <?php echo $slideIndex + 1; ?> of <?php echo count($slides); ?>
                        </p>
                    </article>
                <?php endforeach; ?>

            </div><!-- /.slider-items -->

            <!-- Dot indicators -->
            <div class="slider-dots" id="slider-dots" aria-hidden="true">
                <?php foreach ($slides as $slideIndex => $slide): ?>
                    <button class="slider-dot<?php echo $slideIndex === 0 ? ' active' : ''; ?>"
                            data-index="<?php echo $slideIndex; ?>"
                            aria-label="Go to slide <?php echo $slideIndex + 1; ?>"></button>
                <?php endforeach; ?>
            </div>

        </section><!-- /.slider-panel -->

        <!-- ── COL 3: Image ───────────────────────────────────── -->
        <section class="image-panel" aria-label="Slide image">
            <div class="image-frame" id="slide-image-container">
                <?php if ($activeSlide): ?>
                    <img src="<?php echo htmlspecialchars($activeSlide['image_path']); ?>"
                         alt="<?php echo htmlspecialchars($activeSlide['title']); ?>"
                         id="active-slide-image">
                <?php else: ?>
                    <div class="placeholder">No slides in this topic yet.</div>
                <?php endif; ?>
            </div>
        </section>

    </div><!-- /.slider-outer -->

</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/js/app.js"></script>



</body>
</html>