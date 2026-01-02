<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Projects • Dev Hub</title>
  <link rel="stylesheet" href="/assets/style.css" />
</head>
<body>
  <div class="wrap">
    <div class="header">
      <div class="brand">
        <div class="avatar" id="avatar">NL</div>
        <div class="title">Project Hub</div>
      </div>
      <div class="toolbar">
        <input id="search" class="input" type="search" placeholder="Tìm project... (Ctrl+/)" />
        <button class="btn" id="themeToggle" title="Đổi giao diện">🌓</button>
      </div>
    </div>

    <div class="filters" id="filters"></div>

    <div class="grid" id="grid"></div>

    <div class="footer">Made with ❤ — GitHub-style, no framework, 100% static.</div>
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

  <script src="/js/main.js"></script>
</body>
</html>