<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SnapCode — Code Screenshot Manager</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <!-- Google Fonts: DM Sans + DM Mono -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet"/>

  <style>
    /* =============================================
       CSS VARIABLES & THEME
    ============================================= */
    :root {
      --bg-base:       #f5f4f0;
      --bg-surface:    #ffffff;
      --bg-sidebar:    #1a1a2e;
      --bg-card:       #ffffff;
      --bg-hover:      #f0eee8;

      --text-primary:  #1a1a2e;
      --text-secondary:#6b6880;
      --text-muted:    #a09cb8;
      --text-sidebar:  #c8c4e0;
      --text-sidebar-active: #ffffff;

      --accent:        #6c5ce7;
      --accent-light:  #a29bfe;
      --accent-glow:   rgba(108,92,231,0.15);
      --danger:        #e17055;
      --success:       #00b894;
      --warning:       #fdcb6e;

      --border:        rgba(26,26,46,0.08);
      --border-sidebar:rgba(255,255,255,0.07);

      --shadow-sm:     0 2px 8px rgba(26,26,46,0.06);
      --shadow-md:     0 8px 30px rgba(26,26,46,0.10);
      --shadow-lg:     0 20px 60px rgba(26,26,46,0.15);
      --shadow-card:   0 4px 16px rgba(26,26,46,0.08);
      --shadow-card-hover: 0 12px 40px rgba(108,92,231,0.18);

      --radius-sm:     8px;
      --radius-md:     14px;
      --radius-lg:     20px;
      --radius-xl:     28px;

      --sidebar-w:     260px;
      --navbar-h:      64px;
      --transition:    0.22s cubic-bezier(0.4,0,0.2,1);
    }

    [data-theme="dark"] {
      --bg-base:       #0f0f1a;
      --bg-surface:    #16162a;
      --bg-sidebar:    #0c0c1a;
      --bg-card:       #1c1c30;
      --bg-hover:      #22223a;

      --text-primary:  #e8e4ff;
      --text-secondary:#9490b8;
      --text-muted:    #5a5670;
      --border:        rgba(200,196,224,0.08);
      --shadow-sm:     0 2px 8px rgba(0,0,0,0.3);
      --shadow-md:     0 8px 30px rgba(0,0,0,0.4);
      --shadow-card:   0 4px 16px rgba(0,0,0,0.3);
      --shadow-card-hover: 0 12px 40px rgba(108,92,231,0.3);
    }

    /* =============================================
       BASE
    ============================================= */
    *, *::before, *::after { box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg-base);
      color: var(--text-primary);
      margin: 0;
      overflow-x: hidden;
      transition: background var(--transition), color var(--transition);
    }

    /* =============================================
       SIDEBAR
    ============================================= */
    #sidebar {
      position: fixed;
      top: 0; left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      background: var(--bg-sidebar);
      display: flex;
      flex-direction: column;
      z-index: 1040;
      transition: transform var(--transition);
      overflow: hidden;
    }

    /* Sidebar noise texture overlay */
    #sidebar::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
      pointer-events: none;
      opacity: 0.5;
    }

    .sidebar-logo {
      padding: 20px 22px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid var(--border-sidebar);
    }

    .sidebar-logo .logo-icon {
      width: 36px; height: 36px;
      background: var(--accent);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; color: #fff;
      flex-shrink: 0;
      box-shadow: 0 4px 14px rgba(108,92,231,0.4);
    }

    .sidebar-logo .logo-text {
      font-size: 17px; font-weight: 700;
      color: #fff; letter-spacing: -0.3px;
      line-height: 1.1;
    }
    .sidebar-logo .logo-text span {
      display: block; font-size: 10px;
      font-weight: 400; color: var(--text-sidebar);
      letter-spacing: 0.8px; text-transform: uppercase;
    }

    /* Sidebar nav */
    .sidebar-nav {
      flex: 1; overflow-y: auto; padding: 16px 12px;
    }
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

    .sidebar-label {
      font-size: 10px; font-weight: 600;
      color: var(--text-muted); letter-spacing: 1.2px;
      text-transform: uppercase; padding: 8px 12px 6px;
      margin-top: 6px;
    }

    .folder-item {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 12px; border-radius: var(--radius-sm);
      cursor: pointer; color: var(--text-sidebar);
      font-size: 14px; font-weight: 400;
      transition: all var(--transition);
      margin-bottom: 2px; position: relative;
      text-decoration: none;
    }
    .folder-item:hover {
      background: rgba(255,255,255,0.06);
      color: #fff;
    }
    .folder-item.active {
      background: rgba(108,92,231,0.25);
      color: var(--text-sidebar-active);
      font-weight: 500;
    }
    .folder-item.active::before {
      content: '';
      position: absolute; left: 0; top: 20%; bottom: 20%;
      width: 3px; background: var(--accent-light);
      border-radius: 0 3px 3px 0;
    }
    .folder-item .folder-icon {
      width: 28px; height: 28px;
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; flex-shrink: 0;
    }
    .folder-item .count-badge {
      margin-left: auto; font-size: 11px; font-weight: 600;
      background: rgba(255,255,255,0.1);
      color: var(--text-muted); padding: 1px 7px;
      border-radius: 20px; min-width: 24px; text-align: center;
    }
    .folder-item.active .count-badge {
      background: rgba(108,92,231,0.4); color: var(--accent-light);
    }

    /* Folder colors */
    .fi-all   { background: rgba(108,92,231,0.2); color: #a29bfe; }
    .fi-js    { background: rgba(253,203,110,0.15); color: #fdcb6e; }
    .fi-php   { background: rgba(116,185,255,0.15); color: #74b9ff; }
    .fi-react { background: rgba(0,206,201,0.15); color: #00cec9; }
    .fi-py    { background: rgba(0,184,148,0.15); color: #00b894; }
    .fi-css   { background: rgba(225,112,85,0.15); color: #e17055; }
    .fi-ts    { background: rgba(162,155,254,0.15); color: #a29bfe; }

    .sidebar-footer {
      padding: 14px 12px;
      border-top: 1px solid var(--border-sidebar);
    }

    .add-folder-btn {
      display: flex; align-items: center; gap: 8px;
      padding: 9px 14px; border-radius: var(--radius-sm);
      background: rgba(108,92,231,0.15);
      border: 1px dashed rgba(108,92,231,0.35);
      color: var(--accent-light); font-size: 13px;
      font-weight: 500; cursor: pointer; width: 100%;
      transition: all var(--transition);
    }
    .add-folder-btn:hover {
      background: rgba(108,92,231,0.25);
      border-color: rgba(108,92,231,0.6);
    }

    /* =============================================
       OVERLAY (mobile)
    ============================================= */
    #sidebar-overlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 1039;
      backdrop-filter: blur(2px);
    }
    #sidebar-overlay.show { display: block; }

    /* =============================================
       MAIN WRAPPER
    ============================================= */
    #main-wrapper {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      transition: margin-left var(--transition);
      display: flex; flex-direction: column;
    }

    /* =============================================
       NAVBAR
    ============================================= */
    #topnav {
      position: sticky; top: 0;
      height: var(--navbar-h);
      background: var(--bg-surface);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center;
      padding: 0 24px; gap: 14px;
      z-index: 1030;
      box-shadow: var(--shadow-sm);
      transition: background var(--transition);
    }

    .hamburger-btn {
      display: none;
      background: none; border: none;
      font-size: 18px; color: var(--text-secondary);
      cursor: pointer; padding: 6px 8px;
      border-radius: var(--radius-sm);
      transition: all var(--transition);
    }
    .hamburger-btn:hover { background: var(--bg-hover); color: var(--text-primary); }

    /* Search */
    .search-wrapper {
      flex: 1; position: relative; max-width: 480px;
    }
    .search-wrapper .search-icon {
      position: absolute; left: 13px; top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted); font-size: 14px; pointer-events: none;
    }
    .search-wrapper input {
      width: 100%; padding: 9px 14px 9px 38px;
      background: var(--bg-base);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-md);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px; color: var(--text-primary);
      transition: all var(--transition); outline: none;
    }
    .search-wrapper input::placeholder { color: var(--text-muted); }
    .search-wrapper input:focus {
      border-color: var(--accent); background: var(--bg-surface);
      box-shadow: 0 0 0 3px var(--accent-glow);
    }

    /* Navbar right */
    .nav-actions {
      display: flex; align-items: center; gap: 8px; margin-left: auto;
    }

    .nav-icon-btn {
      width: 36px; height: 36px;
      background: none; border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      display: flex; align-items: center; justify-content: center;
      color: var(--text-secondary); cursor: pointer;
      font-size: 14px; transition: all var(--transition);
    }
    .nav-icon-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--accent); }

    .upload-btn {
      display: flex; align-items: center; gap: 7px;
      padding: 8px 16px; border-radius: var(--radius-sm);
      background: var(--accent); border: none;
      color: #fff; font-family: 'DM Sans', sans-serif;
      font-size: 13px; font-weight: 600; cursor: pointer;
      transition: all var(--transition); white-space: nowrap;
      box-shadow: 0 4px 14px rgba(108,92,231,0.35);
    }
    .upload-btn:hover {
      background: #5a4ed1; transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(108,92,231,0.45);
    }
    .upload-btn:active { transform: translateY(0); }

    .avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), #fd79a8);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 13px; font-weight: 700;
      cursor: pointer; flex-shrink: 0;
    }

    /* =============================================
       CONTENT AREA
    ============================================= */
    #content {
      flex: 1; padding: 28px 28px 40px;
    }

    /* Page header */
    .page-header {
      display: flex; align-items: flex-start;
      justify-content: space-between; margin-bottom: 28px;
      gap: 16px; flex-wrap: wrap;
    }
    .page-header h1 {
      font-size: 22px; font-weight: 700;
      color: var(--text-primary); margin: 0;
      letter-spacing: -0.3px;
    }
    .page-header p {
      margin: 4px 0 0; font-size: 13px;
      color: var(--text-secondary);
    }

    /* View toggle & sort */
    .toolbar {
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }

    .view-toggle {
      display: flex; border: 1.5px solid var(--border);
      border-radius: var(--radius-sm); overflow: hidden;
    }
    .view-toggle button {
      background: none; border: none;
      padding: 6px 10px; color: var(--text-muted);
      font-size: 13px; cursor: pointer; transition: all var(--transition);
    }
    .view-toggle button.active {
      background: var(--accent); color: #fff;
    }
    .view-toggle button:hover:not(.active) { background: var(--bg-hover); }

    .sort-select {
      background: var(--bg-surface);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 6px 10px; font-family: 'DM Sans', sans-serif;
      font-size: 13px; color: var(--text-secondary);
      cursor: pointer; outline: none;
      transition: border-color var(--transition);
    }
    .sort-select:focus { border-color: var(--accent); }

    /* Stats bar */
    .stats-bar {
      display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;
    }
    .stat-chip {
      display: flex; align-items: center; gap: 6px;
      padding: 6px 13px; border-radius: 20px;
      background: var(--bg-surface); border: 1.5px solid var(--border);
      font-size: 12px; font-weight: 500; color: var(--text-secondary);
      box-shadow: var(--shadow-sm);
    }
    .stat-chip .dot {
      width: 7px; height: 7px; border-radius: 50%;
    }

    /* =============================================
       SCREENSHOT CARDS
    ============================================= */
    #cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 20px;
    }

    /* Card */
    .snap-card {
      background: var(--bg-card);
      border-radius: var(--radius-lg);
      border: 1.5px solid var(--border);
      overflow: hidden; cursor: pointer;
      transition: all var(--transition);
      box-shadow: var(--shadow-card);
      position: relative;
    }
    .snap-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-card-hover);
      border-color: rgba(108,92,231,0.25);
    }

    /* Card image */
    .card-img-wrap {
      position: relative; overflow: hidden;
      aspect-ratio: 16 / 10; background: var(--bg-base);
    }
    .card-img-wrap img {
      width: 100%; height: 100%;
      object-fit: cover; display: block;
      transition: transform 0.35s ease;
    }
    .snap-card:hover .card-img-wrap img {
      transform: scale(1.04);
    }

    /* Overlay on hover */
    .card-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(
        to bottom,
        transparent 40%,
        rgba(26,26,46,0.75) 100%
      );
      opacity: 0; transition: opacity var(--transition);
      display: flex; align-items: flex-end;
      padding: 14px;
    }
    .snap-card:hover .card-overlay { opacity: 1; }

    .overlay-actions {
      display: flex; gap: 7px; margin-left: auto;
    }
    .overlay-btn {
      width: 32px; height: 32px;
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 13px; cursor: pointer;
      transition: all 0.18s;
    }
    .overlay-btn:hover { background: rgba(255,255,255,0.3); }
    .overlay-btn.fav-active { background: rgba(253,203,110,0.4); color: #fdcb6e; }
    .overlay-btn.danger:hover { background: rgba(225,112,85,0.5); }

    /* Card language badge */
    .lang-badge {
      position: absolute; top: 10px; left: 10px;
      padding: 3px 9px; border-radius: 20px;
      font-size: 10px; font-weight: 700;
      letter-spacing: 0.5px; text-transform: uppercase;
      backdrop-filter: blur(8px);
    }

    /* Favorite star */
    .fav-star {
      position: absolute; top: 10px; right: 10px;
      font-size: 15px; color: #fdcb6e;
      text-shadow: 0 0 6px rgba(253,203,110,0.6);
    }

    /* Card body */
    .card-body-custom {
      padding: 14px 16px;
    }
    .card-title-row {
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 8px;
    }
    .card-title-row h3 {
      font-size: 14px; font-weight: 600;
      color: var(--text-primary); margin: 0;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      flex: 1;
    }

    /* Tags */
    .card-tags {
      display: flex; flex-wrap: wrap; gap: 5px;
      margin-bottom: 12px;
    }
    .tag {
      font-size: 11px; font-weight: 500;
      padding: 2px 8px; border-radius: 20px;
      background: var(--bg-base);
      border: 1.5px solid var(--border);
      color: var(--text-secondary);
      font-family: 'DM Mono', monospace;
    }
    .tag.accent { background: var(--accent-glow); border-color: rgba(108,92,231,0.25); color: var(--accent); }

    /* Card footer */
    .card-footer-custom {
      display: flex; align-items: center; justify-content: space-between;
      padding-top: 10px; border-top: 1px solid var(--border);
    }
    .card-date {
      font-size: 11px; color: var(--text-muted);
    }
    .card-folder {
      font-size: 11px; color: var(--text-secondary);
      display: flex; align-items: center; gap: 4px;
    }

    /* =============================================
       EMPTY STATE
    ============================================= */
    .empty-state {
      grid-column: 1 / -1;
      text-align: center; padding: 80px 20px;
    }
    .empty-state .empty-icon {
      font-size: 52px; color: var(--text-muted);
      margin-bottom: 16px; line-height: 1;
    }
    .empty-state h4 { font-size: 17px; color: var(--text-secondary); font-weight: 500; }
    .empty-state p { font-size: 14px; color: var(--text-muted); }

    /* =============================================
       MODALS
    ============================================= */
    .modal-content {
      background: var(--bg-surface);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
    }
    .modal-header {
      border-bottom: 1px solid var(--border);
      padding: 20px 24px 16px;
    }
    .modal-title {
      font-size: 17px; font-weight: 700;
      color: var(--text-primary); letter-spacing: -0.2px;
    }
    .modal-body { padding: 20px 24px; }
    .modal-footer {
      border-top: 1px solid var(--border);
      padding: 14px 24px;
    }
    .btn-close {
      filter: var(--close-filter, none);
    }
    [data-theme="dark"] .btn-close { filter: invert(1); }

    /* Form styling */
    .form-label {
      font-size: 13px; font-weight: 600;
      color: var(--text-secondary); margin-bottom: 6px;
    }
    .form-control, .form-select {
      background: var(--bg-base);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px; color: var(--text-primary);
      padding: 9px 13px; outline: none;
      transition: all var(--transition);
    }
    .form-control:focus, .form-select:focus {
      background: var(--bg-surface);
      border-color: var(--accent);
      box-shadow: 0 0 0 3px var(--accent-glow);
    }
    .form-control::placeholder { color: var(--text-muted); }

    /* Upload drop zone */
    .drop-zone {
      border: 2px dashed var(--border);
      border-radius: var(--radius-md);
      padding: 32px 20px; text-align: center;
      cursor: pointer; transition: all var(--transition);
      background: var(--bg-base);
    }
    .drop-zone:hover, .drop-zone.dragover {
      border-color: var(--accent);
      background: var(--accent-glow);
    }
    .drop-zone .dz-icon { font-size: 30px; color: var(--text-muted); margin-bottom: 8px; }
    .drop-zone p { font-size: 13px; color: var(--text-secondary); margin: 0; }
    .drop-zone span { font-size: 12px; color: var(--text-muted); }

    /* Preview modal image */
    #previewModalImg {
      width: 100%; border-radius: var(--radius-md);
      border: 1px solid var(--border);
    }

    /* Buttons */
    .btn-accent {
      background: var(--accent); color: #fff; border: none;
      padding: 9px 20px; border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
      cursor: pointer; transition: all var(--transition);
      box-shadow: 0 4px 14px rgba(108,92,231,0.3);
    }
    .btn-accent:hover { background: #5a4ed1; transform: translateY(-1px); }

    .btn-ghost {
      background: var(--bg-base); color: var(--text-secondary);
      border: 1.5px solid var(--border);
      padding: 9px 20px; border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      cursor: pointer; transition: all var(--transition);
    }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }

    .btn-danger-outline {
      background: none; color: var(--danger);
      border: 1.5px solid rgba(225,112,85,0.35);
      padding: 9px 20px; border-radius: var(--radius-sm);
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      cursor: pointer; transition: all var(--transition);
    }
    .btn-danger-outline:hover { background: rgba(225,112,85,0.1); border-color: var(--danger); }

    /* Toast */
    .toast-wrap {
      position: fixed; bottom: 24px; right: 24px;
      z-index: 9999; display: flex; flex-direction: column; gap: 8px;
    }
    .snap-toast {
      background: var(--bg-sidebar); color: #fff;
      padding: 12px 18px; border-radius: var(--radius-md);
      font-size: 13px; font-weight: 500;
      display: flex; align-items: center; gap: 8px;
      box-shadow: var(--shadow-md);
      animation: slideInToast 0.3s ease, fadeOutToast 0.3s ease 2.5s forwards;
      min-width: 220px;
    }
    @keyframes slideInToast {
      from { transform: translateX(120%); opacity: 0; }
      to   { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeOutToast {
      to { opacity: 0; transform: translateX(120%); }
    }

    /* =============================================
       RESPONSIVE
    ============================================= */
    @media (max-width: 991.98px) {
      #sidebar {
        transform: translateX(calc(-1 * var(--sidebar-w)));
      }
      #sidebar.open {
        transform: translateX(0);
        box-shadow: var(--shadow-lg);
      }
      #main-wrapper { margin-left: 0; }
      .hamburger-btn { display: flex; }
    }

    @media (max-width: 767.98px) {
      #content { padding: 18px 16px 32px; }
      #topnav { padding: 0 14px; gap: 10px; }
      #cards-grid {
        grid-template-columns: 1fr;
      }
      .upload-btn .btn-label { display: none; }
      .upload-btn { padding: 8px 11px; }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
      #cards-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (min-width: 992px) and (max-width: 1399.98px) {
      #cards-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (min-width: 1400px) {
      #cards-grid { grid-template-columns: repeat(4, 1fr); }
    }

    /* Smooth reveal animation */
    .snap-card {
      animation: cardReveal 0.4s ease both;
    }
    @keyframes cardReveal {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .snap-card:nth-child(1)  { animation-delay: 0.03s; }
    .snap-card:nth-child(2)  { animation-delay: 0.07s; }
    .snap-card:nth-child(3)  { animation-delay: 0.11s; }
    .snap-card:nth-child(4)  { animation-delay: 0.15s; }
    .snap-card:nth-child(5)  { animation-delay: 0.19s; }
    .snap-card:nth-child(6)  { animation-delay: 0.23s; }
    .snap-card:nth-child(7)  { animation-delay: 0.27s; }
    .snap-card:nth-child(8)  { animation-delay: 0.31s; }
  </style>
</head>
<body>

<!-- =============================================
     SIDEBAR
============================================= -->
<aside id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="fa-solid fa-camera-retro"></i></div>
    <div class="logo-text">
      SnapCode
      <span>Screenshot Manager</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-label">Library</div>

    <a class="folder-item active" href="#" data-folder="all" onclick="filterFolder(this,'all'); return false;">
      <span class="folder-icon fi-all"><i class="fa-solid fa-layer-group"></i></span>
      All Screenshots
      <span class="count-badge">24</span>
    </a>
    <a class="folder-item" href="#" data-folder="fav" onclick="filterFolder(this,'fav'); return false;">
      <span class="folder-icon" style="background:rgba(253,203,110,0.15);color:#fdcb6e;"><i class="fa-solid fa-star"></i></span>
      Favorites
      <span class="count-badge">6</span>
    </a>

    <div class="sidebar-label" style="margin-top:14px;">Folders</div>
<!-- 
    <a class="folder-item" href="#" data-folder="PHP" onclick="filterFolder(this,'PHP'); return false;">
      <span class="folder-icon fi-php"><i class="fa-brands fa-php"></i></span>
      PHP
      <span class="count-badge">5</span>
    </a> -->
  </nav>

  <div class="sidebar-footer">
    <button class="add-folder-btn" onclick="openAddFolderModal()">
      <i class="fa-solid fa-plus"></i> Add Folder
    </button>
  </div>

<!-- =============================================
     ADD FOLDER MODAL
============================================= -->
<div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFolderModalLabel">
          <i class="fa-solid fa-folder-plus me-2" style="color:var(--accent)"></i>
          Add New Folder
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label">Folder Name</label>
        <input type="text" class="form-control" id="newFolderName" placeholder="e.g. VueJS"/>
      </div>
      <div class="modal-footer">
        <button class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
        <button class="btn-accent" onclick="addFolderFrontend()">
          <i class="fa-solid fa-plus me-1"></i> Add Folder
        </button>
      </div>
    </div>
  </div>
</div>
  </div>
</aside>

<!-- Mobile overlay -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- =============================================
     MAIN WRAPPER
============================================= -->
<div id="main-wrapper">

  <!-- NAVBAR -->
  <nav id="topnav">
    <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Search -->
    <div class="search-wrapper">
      <i class="fa-solid fa-magnifying-glass search-icon"></i>
      <input type="text" id="searchInput" placeholder="Search screenshots, tags, folders…" oninput="handleSearch(this.value)"/>
    </div>

    <!-- Actions -->
    <div class="nav-actions">
      <button class="nav-icon-btn" onclick="toggleTheme()" title="Toggle dark mode" id="themeBtn">
        <i class="fa-solid fa-moon"></i>
      </button>
      <button class="upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fa-solid fa-cloud-arrow-up"></i>
        <span class="btn-label">Upload</span>
      </button>
      <div class="avatar" title="Profile">AK</div>
    </div>
  </nav>

  <!-- CONTENT -->
  <main id="content">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h1 id="pageTitle">All Screenshots</h1>
        <p id="pageSubtitle">Your complete code screenshot library</p>
      </div>
      <div class="toolbar">
        <div class="view-toggle">
          <button class="active" title="Grid view"><i class="fa-solid fa-grid-2"></i></button>
          <button title="List view" onclick="showToast('List view coming soon!')"><i class="fa-solid fa-list"></i></button>
        </div>
        <select class="sort-select" onchange="sortCards(this.value)">
          <option value="date">Sort: Newest</option>
          <option value="name">Sort: Name</option>
          <option value="folder">Sort: Folder</option>
        </select>
      </div>
    </div>

    <!-- Stats bar -->
    <div class="stats-bar">
      <div class="stat-chip"><span class="dot" style="background:#a29bfe"></span>24 Screenshots</div>
      <div class="stat-chip"><span class="dot" style="background:#fdcb6e"></span>6 Favorites</div>
      <div class="stat-chip"><span class="dot" style="background:#00b894"></span>6 Folders</div>
      <div class="stat-chip"><span class="dot" style="background:#74b9ff"></span>~18 MB Used</div>
    </div>

    <!-- Cards grid -->
    <div id="cards-grid">
      <!-- Cards injected by JS -->
    </div>
  </main>
</div>

<!-- =============================================
     UPLOAD MODAL
============================================= -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">
          <i class="fa-solid fa-cloud-arrow-up me-2" style="color:var(--accent)"></i>
          Upload Screenshot
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Drop zone -->
        <div class="drop-zone mb-3" id="dropZone" onclick="document.getElementById('uploadImage').click()">
          <div class="dz-icon"><i class="fa-regular fa-image"></i></div>
          <p>Click to upload or drag &amp; drop</p>
          <span>PNG, JPG, SVG up to 10MB</span>
          <img id="dropPreview" src="" alt="" style="display:none;max-height:120px;border-radius:8px;margin-top:10px;"/>
        </div>
        <input type="file" id="uploadImage" accept="image/*" style="display:block" onchange="previewFile(event)"/>

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" id="uploadTitle" placeholder="e.g. React Hook Pattern"/>
        </div>

        <div class="mb-3">
          <label class="form-label">Folder</label>
          <select class="form-select" id="uploadFolder">
            <option value="">Select folder…</option>
            <option value="1">laravel</option>
          </select>
        </div>

        <div class="mb-1">
          <label class="form-label">Tags <span style="font-weight:400;color:var(--text-muted)">(comma separated)</span></label>
          <input type="text" class="form-control" id="uploadTags" placeholder="e.g. hooks, useState, async"/>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
        <button class="btn-accent" onclick="handleUpload()">
          <i class="fa-solid fa-upload me-1"></i> Upload Screenshot
        </button>
      </div>
    </div>
  </div>
</div>

<!-- =============================================
     PREVIEW MODAL
============================================= -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalTitle">Screenshot Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <img id="previewModalImg" src="" alt="Preview" style="max-width:100%;max-height:70vh;cursor:zoom-in;"/>
        <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
          <div id="previewModalTags" style="display:flex;flex-wrap:wrap;gap:5px;flex:1"></div>
          <span id="previewModalFolder" style="font-size:12px;color:var(--text-muted)"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-danger-outline" id="previewDeleteBtn">
          <i class="fa-solid fa-trash-can me-1"></i> Delete
        </button>
        <button class="btn-ghost" onclick="showToast('Downloaded!')">
          <i class="fa-solid fa-download me-1"></i> Download
        </button>
        <button class="btn-accent" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<!-- =============================================
     TOAST CONTAINER
============================================= -->
<div class="toast-wrap" id="toastWrap"></div>

<!-- =============================================
     SCRIPTS
============================================= -->

<!-- Medium Zoom for image zooming -->
<script src="https://cdn.jsdelivr.net/npm/medium-zoom@1.1.0/dist/medium-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

    $(document).ready(function() {
        getFolders();
    });
/* =============================================
   DATA — Mock screenshot library
============================================= */
  const LANG_COLORS = {
              'JavaScript': { bg: 'rgba(253,203,110,0.9)', color: '#1a1a2e' },
              'PHP': { bg: 'rgba(116,185,255,0.9)', color: '#1a1a2e' },
              'React': { bg: 'rgba(0,206,201,0.9)', color: '#fff' },
              'Python': { bg: 'rgba(0,184,148,0.9)', color: '#fff' },
              'CSS': { bg: 'rgba(225,112,85,0.9)', color: '#fff' },
              'TypeScript': { bg: 'rgba(162,155,254,0.9)', color: '#1a1a2e' },
              'VueJS': { bg: 'rgba(66,184,131,0.9)', color: '#fff' }
            };
function getFolders(){
    $.ajax({
        url: 'api/snap_api.php?action=get_folder',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.status === 200) {
                const folders = response.data;
                let folderCount=folders.length
                const folderContainer = $('.sidebar-nav');
                // Clear existing folder items (except "All Screenshots" and "Favorites")
                folderContainer.find('.folder-item:not([data-folder="all"]):not([data-folder="fav"])').remove();
                // Add folders from API
                folders.forEach((folder,index) => {
                  $(`.folder-item[data-folder="${folder.name}"] .count-badge`).text(folderCount);
                    const folderItem = $(`
                        <a class="folder-item"  data-folder="${folder.name}" onclick="filterFolder(this,'${folder.id}'); return false;">
                            <span class="folder-icon fi-${folder.name.toLowerCase()}"><i class="fa-brands fa-${folder.name}"></i></span>
                            ${folder.name}
                            <span class="count-badge">${folderCount}</span>
                        </a>
                    `);
                    folderContainer.append(folderItem);
                          if(index === 0){
                        setTimeout(() => {
                            filterFolder(folderItem[0], folder.id);
                        }, 100);
                    }

                });  
}
        },  
        error: function(xhr, status, error) {
            console.error('Error fetching folders:', error);
            }
    });
}
const SCREENSHOTS = [];



let currentFilter = 'all';
let currentSearch = '';
let openPreviewId = null;

/* =============================================
   RENDER CARDS
============================================= */
function renderCards(data) {
  const grid = document.getElementById('cards-grid');
  grid.innerHTML = '';

  if (!data.length) {
    grid.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-regular fa-images"></i></div>
        <h4>No screenshots found</h4>
        <p>Try a different folder or search query.</p>
      </div>`;
    return;
  }

  data.forEach(s => {
    const lang = LANG_COLORS[s.folder_name] || { bg:'rgba(200,196,224,0.9)', color:'#1a1a2e' };
    const tagsHtml = s.tags.slice(0,3).map((t,i) =>
      `<span class="tag${i===0?' accent':''}">${t}</span>`
    ).join('');

    const card = document.createElement('div');
    card.className = 'snap-card';
    card.dataset.id = s.id;

    card.innerHTML = `
      <div class="card-img-wrap">
        <img src="uploads/${s.image}" alt="${s.title}" loading="lazy"/>
        <span class="lang-badge" style="background:${lang.bg};color:${lang.color};">${s.folder_name}</span>
        ${s.favorite ? '<i class="fa-solid fa-star fav-star"></i>' : ''}
        <div class="card-overlay">
          <div class="overlay-actions">
            <button class="overlay-btn ${s.favorite?'fav-active':''}" title="Favorite" onclick="toggleFav(event,${s.id})">
              <i class="fa-${s.favorite?'solid':'regular'} fa-star"></i>
            </button>
            <button class="overlay-btn" title="Preview" onclick="openPreview(event,${s.id})">
              <i class="fa-solid fa-eye"></i>
            </button>
            <button class="overlay-btn danger" title="Delete" onclick="deleteCard(event,${s.id})">
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="card-body-custom">
        <div class="card-title-row">
          <h3>${s.title}</h3>
        </div>
        <div class="card-tags">${tagsHtml}</div>
        <div class="card-footer-custom">
          <span class="card-date"><i class="fa-regular fa-clock me-1"></i>${s.date}</span>
          <span class="card-folder"><i class="fa-solid fa-folder me-1"></i>${s.folder_name}</span>
        </div>
      </div>`;

    // Click card body to open preview
    card.querySelector('.card-img-wrap').addEventListener('click', (e) => {
      if (!e.target.closest('.overlay-btn')) openPreview(e, s.id);
    });

    grid.appendChild(card);
  });
}

/* =============================================
   FILTER & SEARCH
============================================= */
function getFiltered() {
  let data = [...SCREENSHOTS];

  if (currentFilter === 'fav') {
    data = data.filter(s => s.favorite);
  } else if (currentFilter !== 'all') {
    data = data.filter(s => s.folder === currentFilter);
  }

  if (currentSearch.trim()) {
    const q = currentSearch.toLowerCase();
    data = data.filter(s =>
      s.title.toLowerCase().includes(q) ||
      s.folder.toLowerCase().includes(q) ||
      s.tags.some(t => t.toLowerCase().includes(q))
    );
  }

  return data;
}

function filterFolder(el, folder) {
    currentFilter = folder;
    $.ajax({
        url: 'api/snap_api.php?action=get_snippets&id=' + folder,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.status === 200) {
                const screenshots = response.data.map(item => ({
                    id: item.id,
                    title: item.title,
                    folder: item.folder_id,
                    folder_name: item.folder_name,
                    image: item.image,
                    img: item.image,
                    tags: item.tags.split(',').map(t => t.trim()),
                    date: new Date(item.created_at).toLocaleDateString(),
                    favorite: false
                }));
                SCREENSHOTS.length = 0; 
                SCREENSHOTS.push(...screenshots); 
                renderCards(SCREENSHOTS);
          
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching screenshots:', error);
        }
    });
  if (window.innerWidth < 992) closeSidebar();
}

function handleSearch(val) {
  currentSearch = val;
  renderCards(getFiltered());
}

function sortCards(by) {
  const data = getFiltered();
  if (by === 'name')   data.sort((a,b) => a.title.localeCompare(b.title));
  if (by === 'folder') data.sort((a,b) => a.folder.localeCompare(b.folder));
  // date is default order
  renderCards(data);
}

/* =============================================
   CARD ACTIONS
============================================= */
function toggleFav(e, id) {
  e.stopPropagation();
  const s = SCREENSHOTS.find(x => x.id === id);
  if (!s) return;
  s.favorite = !s.favorite;
  showToast(s.favorite ? '⭐ Added to Favorites' : '☆ Removed from Favorites');
  renderCards(getFiltered());
}

function deleteCard(e, id) {
  e.stopPropagation();
  const idx = SCREENSHOTS.findIndex(x => x.id === id);
  if (idx === -1) return;
  SCREENSHOTS.splice(idx, 1);
  showToast('🗑️ Screenshot deleted');
  renderCards(getFiltered());
}

function openPreview(e, id) {
  e && e.stopPropagation();
  const s = SCREENSHOTS.find(x => x.id === id);
  if (!s) return;
  openPreviewId = id;

  document.getElementById('previewModalTitle').textContent = s.title;
  const imgEl = document.getElementById('previewModalImg');
  imgEl.src = `uploads/${s.image}`;
  document.getElementById('previewModalFolder').innerHTML =
    `<i class="fa-solid fa-folder me-1"></i>${s.folder_name}`;

  const tagsEl = document.getElementById('previewModalTags');
  tagsEl.innerHTML = s.tags.map(t => `<span class="tag">${t}</span>`).join('');

  // Delete from preview
  document.getElementById('previewDeleteBtn').onclick = () => {
    deleteCard(null, id);
    bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
  };

  // Remove any previous zoom instance
  if (window.previewZoom) {
    window.previewZoom.detach();
    window.previewZoom = null;
  }
  // Wait for modal to show, then attach zoom
  const modalEl = document.getElementById('previewModal');
  const showHandler = () => {
    window.previewZoom = mediumZoom(imgEl, {
      background: 'rgba(30,30,40,0.95)',
      margin: 24,
      scrollOffset: 40
    });
    modalEl.removeEventListener('shown.bs.modal', showHandler);
  };
  modalEl.addEventListener('shown.bs.modal', showHandler);
  bootstrap.Modal.getOrCreateInstance(modalEl).show();
}

/* =============================================
   UPLOAD MOCK
============================================= */
function previewFile(event) {
  const file = event.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    const img = document.getElementById('dropPreview');
    img.src = e.target.result;
    img.style.display = 'block';
  };
  reader.readAsDataURL(file);
}

function handleUpload() {
    const title = document.getElementById('uploadTitle').value.trim();
    const folder = document.getElementById('uploadFolder').value;
    const tags = document.getElementById('uploadTags').value;
    const fileInput = document.getElementById('uploadImage');
    if (!title) return showToast('⚠️ Enter title');
    if (!folder) return showToast('⚠️ Select folder');
    if (!fileInput.files[0]) return showToast('⚠️ Select image');
    let formData = new FormData();
    formData.append('title', title);
    formData.append('folder_id', folder);
    formData.append('tags', tags);
    formData.append('image', fileInput.files[0]);
    $.ajax({
        url: 'api/snap_api.php?action=upload_snippets',
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(res) {
            let response = res;
            if(response.status === 200){
                const newSnap = {
                    id: response.data.id,
                    title: response.data.title,
                    folder: response.data.folder_id,
                    folder_name: response.data.folder_name,
                    image: response.data.image,
                    tags: response.data.tags.split(','),
                    date: response.data.date,
                    favorite: false
                };
                SCREENSHOTS.unshift(newSnap);
                renderCards(SCREENSHOTS);
                showToast('✅ Uploaded successfully');
                // reset form
                $('#uploadModal').modal('hide');
                $('#uploadTitle').val('');
                $('#uploadFolder').val('');
                $('#uploadTags').val('');
                $('#uploadImage').val('');
            }
        }
    });
}

/* =============================================
   SIDEBAR TOGGLE (minimal jQuery)
============================================= */
function toggleSidebar() {
  $('#sidebar').toggleClass('open');
  $('#sidebar-overlay').toggleClass('show');
}
function closeSidebar() {
  $('#sidebar').removeClass('open');
  $('#sidebar-overlay').removeClass('show');
}

/* =============================================
   DARK MODE
============================================= */
function toggleTheme() {
  const html = document.documentElement;
  const isDark = html.getAttribute('data-theme') === 'dark';
  html.setAttribute('data-theme', isDark ? 'light' : 'dark');
  document.getElementById('themeBtn').innerHTML =
    isDark ? '<i class="fa-solid fa-moon"></i>' : '<i class="fa-solid fa-sun"></i>';
}

/* =============================================
   TOAST
============================================= */
function showToast(msg) {
  const wrap = document.getElementById('toastWrap');
  const t = document.createElement('div');
  t.className = 'snap-toast';
  t.innerHTML = msg;
  wrap.appendChild(t);
  setTimeout(() => t.remove(), 3000);
}
function openAddFolderModal(){
    $('#addFolderModal').show();
}
/* =============================================
   INIT
============================================= */
renderCards(SCREENSHOTS);
</script>
</body>
</html>