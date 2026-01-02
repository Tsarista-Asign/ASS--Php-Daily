<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Assignments</title>
    <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
    <div class="wrap">
        <div class="header">
            <div class="brand">
                <div class="avatar" id="avatar"></div>
                <div class="title">Ciahip</div>
            </div>
            <div class="toolbar">
                <input id="search" class="input" type="search" placeholder="Tìm project... (Ctrl+/)" />
                <button class="btn" id="themeToggle" title="Đổi giao diện">🌓</button>
            </div>
        </div>

        <div class="filters" id="filters"></div>

        <div class="grid" id="grid">
            <?php
            include '../src/projects.php';
            $projects = getProjects();
            foreach ($projects as $project) {
                echo '<article class="card" role="button" tabindex="0" data-url="' . $project['url'] . '">';
                echo '<div class="name">';
                // echo '<svg class="icon" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M8 0C3.58 0 0 3.58 0 8a8 8 0 005.47 7.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82a7.67 7.67 0 012-.27c.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.24.54.73.54 1.48 0 1.07-.01 1.93-.01 2.19 0 .21.15.46.55.38A8 8 0 0016 8c0-4.42-3.58-8-8-8z"/></svg>';
                echo '<span>' . $project['name'] . '</span>';
                echo '<span class="badge">' . ($project['tags'][0] ?? 'project') . '</span>';
                echo '</div>';
                echo '<div class="desc">' . $project['desc'] . '</div>';
                echo '<div class="tags">' . implode('', array_map(fn($tag) => '<span class="badge">#' . $tag . '</span>', $project['tags'])) . '</div>';
                echo '<div class="meta">';
                echo '<span class="lang"><span class="dot" style="background:' . ($project['language']['color'] ?? '#999') . '"></span>' . ($project['language']['name'] ?? 'Code') . '</span>';
                // echo '<span title="Stars">⭐ ' . ($project['stars'] ?? 0) . '</span>';
                echo '</div>';
                echo '</article>';
            }
            ?>
        </div>

        <div class="footer"></div>
    </div>

    <!-- Modal Preview -->
    <div class="modal" id="modal">
        <div class="dialog">
            <header>
                <div class="left">
                    <span class="title" id="modalTitle">Xem trước</span>
                </div>
                <div class="controls">
                    <a class="btn" id="openNewTab" target="_blank" rel="noopener">Mở tab mới</a>
                    <button class="btn" id="closeModal">Đóng</button>
                </div>
            </header>
            <iframe id="preview" src="about:blank"></iframe>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>