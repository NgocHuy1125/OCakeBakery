// Hide navbar row 2 when scroll down
(() => {
  const row2 = document.getElementById('navRow2');
  if (!row2) return;

  const setRow2HeightVar = () => {
    const h = row2.offsetHeight || 56;
    row2.style.setProperty('--row2-h', h + 'px');
  };
  setRow2HeightVar();
  window.addEventListener('resize', setRow2HeightVar);

  let lastY = window.scrollY || 0;
  let ticking = false;

  const onScroll = () => {
    const y = window.scrollY || 0;

    if (y < 80) {
      row2.classList.remove('is-hidden');
      lastY = y;
      return;
    }

    if (y > lastY + 5) {
      row2.classList.add('is-hidden');
    } else if (y < lastY - 5) {
      row2.classList.remove('is-hidden');
    }
    lastY = y;
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => { onScroll(); ticking = false; });
      ticking = true;
    }
  }, { passive: true });
})();