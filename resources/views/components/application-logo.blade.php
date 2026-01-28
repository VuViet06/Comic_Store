<svg viewBox="0 0 400 80" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <defs>
    <style>
      .logo-text { font-family: 'Poppins', 'Segoe UI', sans-serif; font-weight: 700; font-size: 42px; fill: url(#textGradient); letter-spacing: -0.5px; }
      .logo-subtext { font-family: 'Poppins', 'Segoe UI', sans-serif; font-weight: 500; font-size: 12px; fill: #9c7dd4; letter-spacing: 1px; }
    </style>

    <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#8b5fbb;stop-opacity:1" />
      <stop offset="50%" style="stop-color:#d64e89;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#ff8c42;stop-opacity:1" />
    </linearGradient>

    <linearGradient id="iconGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#a67dc9;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#ff9d6b;stop-opacity:1" />
    </linearGradient>

    <filter id="softGlow">
      <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
      <feMerge>
        <feMergeNode in="coloredBlur"/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>
  

  <text x="50" y="52" class="logo-text">COMIC STORE</text>
</svg>
