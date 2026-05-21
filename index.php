<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ReclamPro — Gestion des Réclamations Clients</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet" />

  <!-- ============================================================
       CSS — ReclamPro Design System
       Color palette:
         --colorPrimary:    #0F1C2E  (deep navy)
         --colorSecondary:  #1A3A5C  (medium navy)
         --colorAccent:     #E8943A  (warm amber)
         --colorAccentHover:#D07E2A  (amber dark)
         --colorBg:         #F7F9FC  (off-white)
         --colorSurface:    #FFFFFF  (white)
         --colorText:       #1E293B  (dark)
         --colorMuted:      #64748B  (muted grey)
         --colorBorder:     #E2E8F0  (light border)
         --colorSuccess:    #16A34A  (green)
         --colorWarning:    #CA8A04  (yellow)
         --colorDanger:     #DC2626  (red)
  ============================================================ -->

  <style>
    /* ── Reset & Base ─────────────────────────────────────────── */
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --colorPrimary:    #0F1C2E;
      --colorSecondary:  #1A3A5C;
      --colorAccent:     #E8943A;
      --colorAccentHover:#D07E2A;
      --colorBg:         #F7F9FC;
      --colorSurface:    #FFFFFF;
      --colorText:       #1E293B;
      --colorMuted:      #64748B;
      --colorBorder:     #E2E8F0;
      --colorSuccess:    #16A34A;
      --colorWarning:    #CA8A04;
      --colorDanger:     #DC2626;

      --fontDisplay: 'Sora', sans-serif;
      --fontBody:    'DM Sans', sans-serif;

      --radiusSm:  6px;
      --radiusMd:  10px;
      --radiusLg:  16px;
      --radiusXl:  24px;

      --shadowSm:  0 1px 4px rgba(15,28,46,.06);
      --shadowMd:  0 4px 16px rgba(15,28,46,.10);
      --shadowLg:  0 12px 40px rgba(15,28,46,.14);

      --transitionFast: 160ms ease;
      --transitionMed:  260ms ease;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: var(--fontBody);
      background-color: var(--colorBg);
      color: var(--colorText);
      line-height: 1.6;
      font-size: 16px;
      -webkit-font-smoothing: antialiased;
    }

    img { display: block; max-width: 100%; }
    a   { color: inherit; text-decoration: none; }
    ul  { list-style: none; }
    button { cursor: pointer; font-family: var(--fontBody); }

    /* ── Utility ──────────────────────────────────────────────── */
    .container {
      max-width: 1120px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .srOnly {
      position: absolute;
      width: 1px; height: 1px;
      overflow: hidden;
      clip: rect(0,0,0,0);
      white-space: nowrap;
    }

    /* ── Buttons ──────────────────────────────────────────────── */
    .btnPrimary,
    .btnOutline,
    .btnGhost {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-family: var(--fontDisplay);
      font-weight: 600;
      font-size: .875rem;
      letter-spacing: .01em;
      border-radius: var(--radiusMd);
      padding: 10px 22px;
      border: 2px solid transparent;
      transition: background var(--transitionFast),
                  color var(--transitionFast),
                  border-color var(--transitionFast),
                  transform var(--transitionFast),
                  box-shadow var(--transitionFast);
    }

    .btnPrimary {
      background: var(--colorAccent);
      color: #fff;
    }
    .btnPrimary:hover {
      background: var(--colorAccentHover);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(232,148,58,.35);
    }

    .btnOutline {
      background: transparent;
      color: var(--colorPrimary);
      border-color: var(--colorPrimary);
    }
    .btnOutline:hover {
      background: var(--colorPrimary);
      color: #fff;
      transform: translateY(-1px);
    }

    .btnGhost {
      background: transparent;
      color: var(--colorMuted);
      border-color: transparent;
      padding: 10px 16px;
    }
    .btnGhost:hover {
      color: var(--colorPrimary);
      background: rgba(15,28,46,.06);
    }

    /* ── Navbar ───────────────────────────────────────────────── */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(255,255,255,.92);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--colorBorder);
    }

    .navbarInner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 64px;
      gap: 24px;
    }

    .navLogo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: var(--fontDisplay);
      font-weight: 800;
      font-size: 1.2rem;
      color: var(--colorPrimary);
      letter-spacing: -.02em;
      flex-shrink: 0;
    }

    .navLogoMark {
      width: 34px;
      height: 34px;
      background: var(--colorAccent);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      color: #fff;
      font-weight: 800;
    }

    .navLinks {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .navLink {
      font-size: .875rem;
      font-weight: 500;
      color: var(--colorMuted);
      padding: 6px 12px;
      border-radius: var(--radiusSm);
      transition: color var(--transitionFast), background var(--transitionFast);
    }
    .navLink:hover {
      color: var(--colorPrimary);
      background: rgba(15,28,46,.06);
    }

    .navActions {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* Mobile nav toggle */
    .navToggle {
      display: none;
      background: none;
      border: none;
      padding: 8px;
      color: var(--colorPrimary);
    }

    /* ── Hero ─────────────────────────────────────────────────── */
    .hero {
      background: var(--colorPrimary);
      position: relative;
      overflow: hidden;
      padding: 96px 0 80px;
    }

    /* Decorative circles */
    .hero::before,
    .hero::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }
    .hero::before {
      width: 520px; height: 520px;
      top: -160px; right: -120px;
      background: radial-gradient(circle, rgba(232,148,58,.18) 0%, transparent 70%);
    }
    .hero::after {
      width: 320px; height: 320px;
      bottom: -80px; left: -60px;
      background: radial-gradient(circle, rgba(26,58,92,.6) 0%, transparent 70%);
    }

    .heroInner {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 64px;
      align-items: center;
    }

    .heroBadge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: .75rem;
      font-weight: 600;
      font-family: var(--fontDisplay);
      letter-spacing: .06em;
      text-transform: uppercase;
      color: var(--colorAccent);
      background: rgba(232,148,58,.12);
      border: 1px solid rgba(232,148,58,.25);
      border-radius: 100px;
      padding: 5px 14px;
      margin-bottom: 24px;
    }

    .heroBadgeDot {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--colorAccent);
      animation: heroPulse 2s ease-in-out infinite;
    }

    @keyframes heroPulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: .5; transform: scale(1.4); }
    }

    .heroTitle {
      font-family: var(--fontDisplay);
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -.03em;
      color: #fff;
      margin-bottom: 20px;
    }

    .heroTitleAccent {
      color: var(--colorAccent);
    }

    .heroSubtitle {
      font-size: 1.05rem;
      color: rgba(255,255,255,.65);
      line-height: 1.7;
      margin-bottom: 36px;
      max-width: 480px;
    }

    .heroCtas {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .heroCtaPrimary {
      background: var(--colorAccent);
      color: #fff;
      font-family: var(--fontDisplay);
      font-weight: 600;
      font-size: .9rem;
      padding: 13px 26px;
      border-radius: var(--radiusMd);
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background var(--transitionFast), transform var(--transitionFast), box-shadow var(--transitionFast);
    }
    .heroCtaPrimary:hover {
      background: var(--colorAccentHover);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(232,148,58,.40);
    }

    .heroCtaSecondary {
      background: rgba(255,255,255,.1);
      color: #fff;
      font-family: var(--fontDisplay);
      font-weight: 600;
      font-size: .9rem;
      padding: 13px 26px;
      border-radius: var(--radiusMd);
      border: 1px solid rgba(255,255,255,.2);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background var(--transitionFast), transform var(--transitionFast);
    }
    .heroCtaSecondary:hover {
      background: rgba(255,255,255,.18);
      transform: translateY(-2px);
    }

    /* Hero visual — Stats strip */
    .heroStats {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .heroStatCard {
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: var(--radiusLg);
      padding: 24px 20px;
      backdrop-filter: blur(8px);
    }

    .heroStatCard:first-child {
      grid-column: 1 / -1;
      background: rgba(232,148,58,.12);
      border-color: rgba(232,148,58,.25);
    }

    .heroStatNum {
      font-family: var(--fontDisplay);
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: -.04em;
      line-height: 1;
      margin-bottom: 6px;
    }

    .heroStatCard:first-child .heroStatNum {
      color: var(--colorAccent);
    }

    .heroStatLabel {
      font-size: .8rem;
      color: rgba(255,255,255,.55);
      font-weight: 500;
    }

    .heroStatIcon {
      font-size: 1.5rem;
      margin-bottom: 12px;
    }

    /* ── Section shared ───────────────────────────────────────── */
    .section {
      padding: 80px 0;
    }

    .sectionLabel {
      font-family: var(--fontDisplay);
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--colorAccent);
      margin-bottom: 10px;
    }

    .sectionTitle {
      font-family: var(--fontDisplay);
      font-size: clamp(1.5rem, 3vw, 2.1rem);
      font-weight: 800;
      letter-spacing: -.03em;
      color: var(--colorPrimary);
      margin-bottom: 14px;
      line-height: 1.2;
    }

    .sectionSubtitle {
      font-size: 1rem;
      color: var(--colorMuted);
      max-width: 520px;
      line-height: 1.7;
    }

    .sectionHeader {
      margin-bottom: 48px;
    }

    /* ── Tracking Section ─────────────────────────────────────── */
    .trackingSection {
      background: var(--colorSurface);
      border-top: 1px solid var(--colorBorder);
      border-bottom: 1px solid var(--colorBorder);
    }

    .trackingCard {
      background: var(--colorBg);
      border: 1px solid var(--colorBorder);
      border-radius: var(--radiusXl);
      padding: 40px 48px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 48px;
      align-items: center;
    }

    .trackingForm {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .inputGroup {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .inputLabel {
      font-size: .82rem;
      font-weight: 600;
      color: var(--colorText);
      font-family: var(--fontDisplay);
    }

    .inputField {
      font-family: var(--fontBody);
      font-size: .9rem;
      color: var(--colorText);
      background: var(--colorSurface);
      border: 1.5px solid var(--colorBorder);
      border-radius: var(--radiusMd);
      padding: 11px 16px;
      transition: border-color var(--transitionFast), box-shadow var(--transitionFast);
      outline: none;
      width: 100%;
    }
    .inputField::placeholder { color: #b0bec5; }
    .inputField:focus {
      border-color: var(--colorAccent);
      box-shadow: 0 0 0 3px rgba(232,148,58,.15);
    }

    .btnTrack {
      font-family: var(--fontDisplay);
      font-weight: 600;
      font-size: .875rem;
      background: var(--colorPrimary);
      color: #fff;
      border: none;
      border-radius: var(--radiusMd);
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background var(--transitionFast), transform var(--transitionFast);
      width: 100%;
    }
    .btnTrack:hover {
      background: var(--colorSecondary);
      transform: translateY(-1px);
    }

    /* Result area */
    .trackingResult {
      display: none;
      margin-top: 4px;
    }

    .trackingResult.isVisible {
      display: block;
    }

    .resultCard {
      background: var(--colorSurface);
      border: 1.5px solid var(--colorBorder);
      border-radius: var(--radiusMd);
      padding: 16px 18px;
    }

    .resultRow {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .85rem;
      padding: 5px 0;
    }

    .resultRow + .resultRow {
      border-top: 1px solid var(--colorBorder);
    }

    .resultRowLabel {
      color: var(--colorMuted);
      font-weight: 500;
    }

    .resultRowValue {
      font-weight: 600;
      color: var(--colorText);
    }

    /* Status badges */
    .statusBadge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: .75rem;
      font-weight: 700;
      font-family: var(--fontDisplay);
      letter-spacing: .04em;
      text-transform: uppercase;
      padding: 3px 10px;
      border-radius: 100px;
    }

    .statusBadge.statusNew        { background: #EFF6FF; color: #2563EB; }
    .statusBadge.statusPending    { background: #FEF9C3; color: var(--colorWarning); }
    .statusBadge.statusInProgress { background: #FFF7ED; color: var(--colorAccent); }
    .statusBadge.statusResolved   { background: #F0FDF4; color: var(--colorSuccess); }
    .statusBadge.statusClosed     { background: #F1F5F9; color: var(--colorMuted); }

    .statusDot {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: currentColor;
    }

    /* Tracking visual right side */
    .trackingVisual {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .trackingVisualTitle {
      font-family: var(--fontDisplay);
      font-size: 1rem;
      font-weight: 700;
      color: var(--colorPrimary);
      margin-bottom: 4px;
    }

    .trackingStep {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }

    .trackingStepLine {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0;
    }

    .trackingStepDot {
      width: 28px; height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .75rem;
      flex-shrink: 0;
      font-weight: 700;
    }

    .trackingStepDot.isDone {
      background: var(--colorPrimary);
      color: #fff;
    }

    .trackingStepDot.isActive {
      background: var(--colorAccent);
      color: #fff;
      box-shadow: 0 0 0 4px rgba(232,148,58,.2);
    }

    .trackingStepDot.isPending {
      background: var(--colorBorder);
      color: var(--colorMuted);
    }

    .trackingStepConnector {
      width: 2px;
      height: 24px;
      background: var(--colorBorder);
      margin: 3px 0;
    }

    .trackingStepConnector.isDone {
      background: var(--colorPrimary);
    }

    .trackingStepContent {
      padding-top: 4px;
    }

    .trackingStepTitle {
      font-size: .85rem;
      font-weight: 600;
      color: var(--colorText);
      line-height: 1.3;
    }

    .trackingStepDate {
      font-size: .75rem;
      color: var(--colorMuted);
      margin-top: 2px;
    }

    /* ── Features Section ─────────────────────────────────────── */
    .featuresGrid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .featureCard {
      background: var(--colorSurface);
      border: 1px solid var(--colorBorder);
      border-radius: var(--radiusLg);
      padding: 28px 24px;
      transition: border-color var(--transitionMed), box-shadow var(--transitionMed), transform var(--transitionMed);
    }

    .featureCard:hover {
      border-color: rgba(232,148,58,.4);
      box-shadow: var(--shadowMd);
      transform: translateY(-3px);
    }

    .featureIcon {
      width: 44px; height: 44px;
      border-radius: var(--radiusMd);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      margin-bottom: 18px;
      background: rgba(15,28,46,.06);
    }

    .featureTitle {
      font-family: var(--fontDisplay);
      font-size: .95rem;
      font-weight: 700;
      color: var(--colorPrimary);
      margin-bottom: 8px;
    }

    .featureDesc {
      font-size: .85rem;
      color: var(--colorMuted);
      line-height: 1.65;
    }

    /* ── CTA Banner ───────────────────────────────────────────── */
    .ctaBanner {
      background: var(--colorPrimary);
      border-radius: var(--radiusXl);
      padding: 56px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 32px;
      position: relative;
      overflow: hidden;
    }

    .ctaBanner::before {
      content: '';
      position: absolute;
      width: 400px; height: 400px;
      top: -150px; right: -100px;
      background: radial-gradient(circle, rgba(232,148,58,.2) 0%, transparent 70%);
      pointer-events: none;
    }

    .ctaBannerTitle {
      font-family: var(--fontDisplay);
      font-size: clamp(1.3rem, 2.5vw, 1.75rem);
      font-weight: 800;
      color: #fff;
      letter-spacing: -.03em;
      line-height: 1.2;
      margin-bottom: 10px;
    }

    .ctaBannerSub {
      font-size: .9rem;
      color: rgba(255,255,255,.6);
    }

    .ctaBannerActions {
      display: flex;
      gap: 12px;
      flex-shrink: 0;
    }

    /* ── Footer ───────────────────────────────────────────────── */
    .footer {
      background: var(--colorPrimary);
      color: rgba(255,255,255,.6);
      padding: 48px 0 28px;
      margin-top: 0;
    }

    .footerInner {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1fr;
      gap: 40px;
      padding-bottom: 40px;
      border-bottom: 1px solid rgba(255,255,255,.1);
    }

    .footerBrand {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
    }

    .footerBrandName {
      font-family: var(--fontDisplay);
      font-size: 1.1rem;
      font-weight: 800;
      color: #fff;
    }

    .footerDesc {
      font-size: .84rem;
      line-height: 1.7;
      max-width: 240px;
    }

    .footerColTitle {
      font-family: var(--fontDisplay);
      font-size: .8rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: rgba(255,255,255,.4);
      margin-bottom: 16px;
    }

    .footerLinks {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .footerLink {
      font-size: .85rem;
      color: rgba(255,255,255,.55);
      transition: color var(--transitionFast);
    }
    .footerLink:hover { color: #fff; }

    .footerBottom {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 24px;
      font-size: .8rem;
    }

    .footerBottomRight {
      display: flex;
      gap: 20px;
    }

    /* ── Error state ──────────────────────────────────────────── */
    .resultError {
      background: #FEF2F2;
      border: 1.5px solid #FECACA;
      border-radius: var(--radiusMd);
      padding: 14px 16px;
      font-size: .85rem;
      color: var(--colorDanger);
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* ── Responsive ───────────────────────────────────────────── */
    @media (max-width: 900px) {
      .heroInner {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .heroStats {
        grid-template-columns: repeat(3, 1fr);
      }

      .heroStatCard:first-child {
        grid-column: auto;
      }

      .trackingCard {
        grid-template-columns: 1fr;
        padding: 28px 24px;
      }

      .featuresGrid {
        grid-template-columns: repeat(2, 1fr);
      }

      .ctaBanner {
        flex-direction: column;
        text-align: center;
        padding: 40px 28px;
      }

      .ctaBannerActions { justify-content: center; }

      .footerInner {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
      }
    }

    @media (max-width: 600px) {
      .navLinks    { display: none; }
      .navToggle   { display: block; }

      .heroStats {
        grid-template-columns: 1fr 1fr;
      }

      .featuresGrid {
        grid-template-columns: 1fr;
      }

      .footerInner {
        grid-template-columns: 1fr;
      }

      .footerBottom {
        flex-direction: column;
        gap: 12px;
        text-align: center;
      }

      /* Mobile nav open state */
      .navLinks.isOpen {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 64px;
        left: 0; right: 0;
        background: #fff;
        border-bottom: 1px solid var(--colorBorder);
        padding: 12px 16px;
        box-shadow: var(--shadowMd);
        z-index: 99;
      }
    }

    /* ── Fade-in animation on load ────────────────────────────── */
    .fadeIn {
      animation: fadeInUp .5s ease both;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .fadeIn:nth-child(1) { animation-delay: .05s; }
    .fadeIn:nth-child(2) { animation-delay: .12s; }
    .fadeIn:nth-child(3) { animation-delay: .20s; }
    .fadeIn:nth-child(4) { animation-delay: .28s; }
  </style>
</head>

<body>

  <!-- ====================================================
       NAVBAR
  ===================================================== -->
  <nav class="navbar">
    <div class="container">
      <div class="navbarInner">

        <!-- Logo -->
        <a href="index.php" class="navLogo">
          <div class="navLogoMark">R</div>
          ReclamPro
        </a>

        <!-- Nav links -->
        <ul class="navLinks" id="navLinks">
          <li><a href="#tracking" class="navLink">Suivre une réclamation</a></li>
          <li><a href="#features" class="navLink">Fonctionnalités</a></li>
          <li><a href="#contact" class="navLink">Contact</a></li>
        </ul>

        <!-- Right actions -->
        <div class="navActions">
          <a href="login.php" class="btnOutline">Se connecter</a>
          <button class="navToggle" id="navToggle" aria-label="Menu">
            <!-- Hamburger icon -->
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="3" y1="6"  x2="19" y2="6" />
              <line x1="3" y1="11" x2="19" y2="11" />
              <line x1="3" y1="16" x2="19" y2="16" />
            </svg>
          </button>
        </div>

      </div>
    </div>
  </nav>

  <!-- ====================================================
       HERO
  ===================================================== -->
  <section class="hero">
    <div class="container">
      <div class="heroInner">

        <!-- Left: text -->
        <div>
          <div class="heroBadge fadeIn">
            <span class="heroBadgeDot"></span>
            Plateforme de gestion des réclamations
          </div>

          <h1 class="heroTitle fadeIn">
            Gérez vos<br/>
            réclamations<br/>
            <span class="heroTitleAccent">avec clarté.</span>
          </h1>

          <p class="heroSubtitle fadeIn">
            Déposez, suivez et résolvez vos réclamations en toute
            transparence. Une interface simple pour les clients,
            un outil puissant pour les agents.
          </p>

          <div class="heroCtas fadeIn">
            <a href="login.php" class="heroCtaPrimary">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 1v6M1 8h14M8 15V9"/></svg>
              Créer une réclamation
            </a>
            <a href="#tracking" class="heroCtaSecondary">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="8" r="6"/><path d="M8 4v4l3 2"/></svg>
              Suivre mon dossier
            </a>
          </div>
        </div>

        <!-- Right: stat cards -->
        <div class="heroStats fadeIn">
          <div class="heroStatCard">
            <div class="heroStatIcon">⚡</div>
            <div class="heroStatNum">48h</div>
            <div class="heroStatLabel">Délai moyen de traitement</div>
          </div>
          <div class="heroStatCard">
            <div class="heroStatIcon">📋</div>
            <div class="heroStatNum">3 rôles</div>
            <div class="heroStatLabel">Client · Agent · Admin</div>
          </div>
          <div class="heroStatCard">
            <div class="heroStatIcon">🔔</div>
            <div class="heroStatNum">Temps réel</div>
            <div class="heroStatLabel">Notifications automatiques</div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ====================================================
       TRACKING SECTION
  ===================================================== -->
  <section class="section trackingSection" id="tracking">
    <div class="container">

      <div class="sectionHeader">
        <p class="sectionLabel">Suivi de réclamation</p>
        <h2 class="sectionTitle">Où en est votre dossier ?</h2>
        <p class="sectionSubtitle">
          Entrez le numéro de référence reçu lors de la création de votre réclamation
          pour consulter son état en temps réel.
        </p>
      </div>

      <div class="trackingCard">

        <!-- Left: form -->
        <div>
          <div class="trackingForm" id="trackingForm">
            <div class="inputGroup">
              <label class="inputLabel" for="trackingRef">Numéro de référence</label>
              <input
                class="inputField"
                type="text"
                id="trackingRef"
                placeholder="Ex: REC-2024-00142"
                autocomplete="off"
              />
            </div>
            <div class="inputGroup">
              <label class="inputLabel" for="trackingEmail">Adresse e-mail (optionnel)</label>
              <input
                class="inputField"
                type="email"
                id="trackingEmail"
                placeholder="votre@email.com"
                autocomplete="off"
              />
            </div>
            <button class="btnTrack" id="btnTrack" type="button" onclick="handleTrackingSearch()">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="7" cy="7" r="5"/><path d="M12 12l2.5 2.5"/></svg>
              Rechercher
            </button>
          </div>

          <!-- Result area — shown by JS -->
          <div class="trackingResult" id="trackingResult"></div>
        </div>

        <!-- Right: step visual -->
        <div class="trackingVisual">
          <p class="trackingVisualTitle">Cycle de traitement d'une réclamation</p>

          <!-- Step 1 -->
          <div class="trackingStep">
            <div class="trackingStepLine">
              <div class="trackingStepDot isDone">✓</div>
              <div class="trackingStepConnector isDone"></div>
            </div>
            <div class="trackingStepContent">
              <div class="trackingStepTitle">Réclamation soumise</div>
              <div class="trackingStepDate">Enregistrement et validation</div>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="trackingStep">
            <div class="trackingStepLine">
              <div class="trackingStepDot isDone">✓</div>
              <div class="trackingStepConnector isDone"></div>
            </div>
            <div class="trackingStepContent">
              <div class="trackingStepTitle">Affectation à un agent</div>
              <div class="trackingStepDate">Prise en charge par l'équipe</div>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="trackingStep">
            <div class="trackingStepLine">
              <div class="trackingStepDot isActive">→</div>
              <div class="trackingStepConnector"></div>
            </div>
            <div class="trackingStepContent">
              <div class="trackingStepTitle">En cours de traitement</div>
              <div class="trackingStepDate">Analyse et résolution</div>
            </div>
          </div>

          <!-- Step 4 -->
          <div class="trackingStep">
            <div class="trackingStepLine">
              <div class="trackingStepDot isPending">4</div>
            </div>
            <div class="trackingStepContent">
              <div class="trackingStepTitle">Clôturée</div>
              <div class="trackingStepDate">Réclamation résolue</div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </section>

  <!-- ====================================================
       FEATURES SECTION
  ===================================================== -->
  <section class="section" id="features">
    <div class="container">

      <div class="sectionHeader">
        <p class="sectionLabel">Ce que nous offrons</p>
        <h2 class="sectionTitle">Une plateforme pensée<br/>pour chaque acteur</h2>
        <p class="sectionSubtitle">
          De la soumission à la résolution, chaque étape est tracée,
          notifiée et documentée.
        </p>
      </div>

      <div class="featuresGrid">

        <div class="featureCard">
          <div class="featureIcon">📝</div>
          <h3 class="featureTitle">Dépôt simple et rapide</h3>
          <p class="featureDesc">
            Créez une réclamation en quelques clics. Joignez des
            pièces justificatives et précisez la catégorie concernée.
          </p>
        </div>

        <div class="featureCard">
          <div class="featureIcon">🔍</div>
          <h3 class="featureTitle">Suivi en temps réel</h3>
          <p class="featureDesc">
            Consultez l'état d'avancement de votre dossier à tout
            moment via votre numéro de référence.
          </p>
        </div>

        <div class="featureCard">
          <div class="featureIcon">🔔</div>
          <h3 class="featureTitle">Notifications automatiques</h3>
          <p class="featureDesc">
            Soyez alerté dès que le statut de votre réclamation
            change ou qu'un agent vous répond.
          </p>
        </div>

        <div class="featureCard">
          <div class="featureIcon">👤</div>
          <h3 class="featureTitle">Espace client dédié</h3>
          <p class="featureDesc">
            Consultez tout l'historique de vos réclamations passées
            et leurs traitements depuis votre compte.
          </p>
        </div>

        <div class="featureCard">
          <div class="featureIcon">🛠️</div>
          <h3 class="featureTitle">Traitement par les agents</h3>
          <p class="featureDesc">
            Les agents disposent d'une interface claire pour prendre
            en charge, commenter et clôturer les dossiers.
          </p>
        </div>

        <div class="featureCard">
          <div class="featureIcon">📊</div>
          <h3 class="featureTitle">Supervision administrative</h3>
          <p class="featureDesc">
            Les administrateurs pilotent les équipes, consultent
            les statistiques et gèrent les utilisateurs.
          </p>
        </div>

      </div>

    </div>
  </section>

  <!-- ====================================================
       CTA BANNER
  ===================================================== -->
  <section class="section" style="padding-top: 0;">
    <div class="container">
      <div class="ctaBanner">
        <div style="position: relative; z-index: 1;">
          <h2 class="ctaBannerTitle">Prêt à soumettre<br/>votre réclamation ?</h2>
          <p class="ctaBannerSub">Créez un compte gratuit et déposez votre premier dossier en moins de 2 minutes.</p>
        </div>
        <div class="ctaBannerActions" style="position: relative; z-index: 1;">
          <a href="login.php" class="heroCtaPrimary">Créer un compte</a>
          <a href="login.php" class="heroCtaSecondary">Se connecter</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       FOOTER
  ===================================================== -->
  <footer class="footer" id="contact">
    <div class="container">

      <div class="footerInner">

        <!-- Brand -->
        <div>
          <div class="footerBrand">
            <div class="navLogoMark">R</div>
            <span class="footerBrandName">ReclamPro</span>
          </div>
          <p class="footerDesc">
            Plateforme de gestion et de suivi des réclamations clients.
            Simple, transparente et efficace.
          </p>
        </div>

        <!-- Col 2 -->
        <div>
          <p class="footerColTitle">Plateforme</p>
          <ul class="footerLinks">
            <li><a href="#" class="footerLink">Soumettre une réclamation</a></li>
            <li><a href="#tracking" class="footerLink">Suivre un dossier</a></li>
            <li><a href="login.php" class="footerLink">Mon espace</a></li>
          </ul>
        </div>

        <!-- Col 3 -->
        <div>
          <p class="footerColTitle">Aide</p>
          <ul class="footerLinks">
            <li><a href="#" class="footerLink">FAQ</a></li>
            <li><a href="#" class="footerLink">Comment ça marche</a></li>
            <li><a href="#" class="footerLink">Nous contacter</a></li>
          </ul>
        </div>

        <!-- Col 4 -->
        <div>
          <p class="footerColTitle">Légal</p>
          <ul class="footerLinks">
            <li><a href="#" class="footerLink">Confidentialité</a></li>
            <li><a href="#" class="footerLink">Conditions d'utilisation</a></li>
          </ul>
        </div>

      </div>

      <div class="footerBottom">
        <span>© <?= date("Y"); ?> ReclamPro — Tous droits réservés</span>
        <div class="footerBottomRight">
          <a href="#" class="footerLink">Mentions légales</a>
        </div>
      </div>

    </div>
  </footer>


  <!-- ============================================================
       JAVASCRIPT
  ============================================================ -->
  <script>
    // ── Mobile nav toggle ──────────────────────────────────────
    const navToggle = document.getElementById('navToggle');
    const navLinks  = document.getElementById('navLinks');

    navToggle.addEventListener('click', function () {
      navLinks.classList.toggle('isOpen');
    });

    // Close nav when a link is clicked (mobile)
    navLinks.querySelectorAll('.navLink').forEach(function (link) {
      link.addEventListener('click', function () {
        navLinks.classList.remove('isOpen');
      });
    });

    // ── Complaint tracking search ──────────────────────────────
    /**
     * handleTrackingSearch()
     * Validates the tracking reference field and renders a
     * mock result card. Replace with a real fetch() call to
     * the PHP backend once the API is ready.
     */
    function handleTrackingSearch() {
      var refInput   = document.getElementById('trackingRef');
      var resultArea = document.getElementById('trackingResult');
      var rawRef     = refInput.value.trim();

      // ── Validation ─────────────────────────────────────────
      if (!rawRef) {
        resultArea.classList.add('isVisible');
        resultArea.innerHTML = buildErrorHtml('Veuillez saisir un numéro de référence.');
        return;
      }

      // Basic format hint: REC-YYYY-NNNNN  (not enforced strictly)
      var refPattern = /^REC-\d{4}-\d{4,6}$/i;
      if (!refPattern.test(rawRef)) {
        resultArea.classList.add('isVisible');
        resultArea.innerHTML = buildErrorHtml(
          'Format invalide. Le numéro doit ressembler à : <strong>REC-2024-00142</strong>'
        );
        return;
      }

      // ── Mock response — swap with fetch() later ────────────
      var mockData = getMockResult(rawRef.toUpperCase());
      resultArea.classList.add('isVisible');
      resultArea.innerHTML = buildResultHtml(mockData);
    }

    /**
     * getMockResult(ref)
     * Returns a fake complaint object for UI demo purposes.
     * This entire function will be removed when backend is ready.
     */
    function getMockResult(ref) {
      var mockMap = {
        'REC-2024-00142': {
          ref:      'REC-2024-00142',
          title:    'Problème de facturation',
          category: 'Facturation',
          status:   'inProgress',
          statusLabel: 'En cours',
          priority: 'Haute',
          date:     '12 nov. 2024',
          agent:    'Agent assigné'
        },
        'REC-2024-00200': {
          ref:      'REC-2024-00200',
          title:    'Livraison non reçue',
          category: 'Logistique',
          status:   'resolved',
          statusLabel: 'Résolue',
          priority: 'Normale',
          date:     '03 déc. 2024',
          agent:    'Agent assigné'
        }
      };

      return mockMap[ref] || {
        ref:      ref,
        title:    'Réclamation introuvable',
        category: '—',
        status:   'notFound',
        statusLabel: null,
        priority: '—',
        date:     '—',
        agent:    '—'
      };
    }

    /**
     * buildResultHtml(data)
     * Renders the result card HTML string.
     */
    function buildResultHtml(data) {
      if (data.status === 'notFound') {
        return buildErrorHtml(
          'Aucune réclamation trouvée pour la référence <strong>' + data.ref + '</strong>. ' +
          'Vérifiez le numéro ou <a href="login.php" style="color:var(--colorDanger);text-decoration:underline;">connectez-vous</a> pour accéder à votre espace.'
        );
      }

      var badgeClass = {
        'new':        'statusNew',
        'pending':    'statusPending',
        'inProgress': 'statusInProgress',
        'resolved':   'statusResolved',
        'closed':     'statusClosed'
      }[data.status] || 'statusNew';

      return '<div class="resultCard">' +
        '<div class="resultRow">' +
          '<span class="resultRowLabel">Référence</span>' +
          '<span class="resultRowValue">' + escapeHtml(data.ref) + '</span>' +
        '</div>' +
        '<div class="resultRow">' +
          '<span class="resultRowLabel">Objet</span>' +
          '<span class="resultRowValue">' + escapeHtml(data.title) + '</span>' +
        '</div>' +
        '<div class="resultRow">' +
          '<span class="resultRowLabel">Catégorie</span>' +
          '<span class="resultRowValue">' + escapeHtml(data.category) + '</span>' +
        '</div>' +
        '<div class="resultRow">' +
          '<span class="resultRowLabel">Priorité</span>' +
          '<span class="resultRowValue">' + escapeHtml(data.priority) + '</span>' +
        '</div>' +
        '<div class="resultRow">' +
          '<span class="resultRowLabel">Date de création</span>' +
          '<span class="resultRowValue">' + escapeHtml(data.date) + '</span>' +
        '</div>' +
        '<div class="resultRow">' +
          '<span class="resultRowLabel">Statut</span>' +
          '<span class="statusBadge ' + badgeClass + '">' +
            '<span class="statusDot"></span>' +
            escapeHtml(data.statusLabel) +
          '</span>' +
        '</div>' +
      '</div>';
    }

    /**
     * buildErrorHtml(message)
     */
    function buildErrorHtml(message) {
      return '<div class="resultError">' +
        '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0"><circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 10.5v.5"/></svg>' +
        '<span>' + message + '</span>' +
      '</div>';
    }

    /**
     * escapeHtml(str) — prevent XSS when rendering user input
     */
    function escapeHtml(str) {
      var map = { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;' };
      return String(str).replace(/[&<>"']/g, function(m){ return map[m]; });
    }

    // Allow pressing Enter in input fields to trigger search
    ['trackingRef', 'trackingEmail'].forEach(function (id) {
      var el = document.getElementById(id);
      if (el) {
        el.addEventListener('keydown', function (e) {
          if (e.key === 'Enter') handleTrackingSearch();
        });
      }
    });

    // Clear result when user edits the ref field
    document.getElementById('trackingRef').addEventListener('input', function () {
      var resultArea = document.getElementById('trackingResult');
      resultArea.classList.remove('isVisible');
      resultArea.innerHTML = '';
    });
  </script>

</body>
</html>