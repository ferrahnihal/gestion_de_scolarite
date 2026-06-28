const pageIcons = { informations:'👤', notes:'📝', releve:'📄' };
  const subLabels  = {
    voir: 'Voir mes notes',
    moyenne: 'Ma moyenne générale',
    telecharger: 'Télécharger mon relevé'
  };
  const allPrefixes = ['info','nt','rl'];

  function goPage(id, el) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sb-item').forEach(i => i.classList.remove('active'));
    document.getElementById('page-' + id).classList.add('active');
    document.getElementById('page-title').textContent = 'Tableau de bord';
    if (el) el.classList.add('active');
  }

  function toggleMenu(prefix, el) {
    const sub   = document.getElementById('submenu-' + prefix);
    const arrow = document.getElementById('arrow-'   + prefix);
    const isOpen = sub.classList.contains('open');
    allPrefixes.forEach(p => {
      document.getElementById('submenu-' + p).classList.remove('open');
      document.getElementById('arrow-'   + p).classList.remove('open');
      document.getElementById('menu-'    + p).classList.remove('active');
    });
    if (!isOpen) {
      sub.classList.add('open');
      arrow.classList.add('open');
      el.classList.add('active');
    }
  }

  function goSub(page, sub, prefix, el) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById('page-' + page).classList.add('active');

    const titles = {
      informations: { voir: '👤 Mes informations' },
      notes: { voir: '📝 Mes notes', moyenne: '📊 Ma moyenne générale' },
      releve: { telecharger: '📄 Mon relevé de notes' }
    };
    document.getElementById('page-title').textContent = titles[page][sub] || page;

    // Cacher toutes les sous-vues
    ['voir','moyenne','telecharger'].forEach(s => {
      const e = document.getElementById(prefix + '-' + s);
      if (e) e.style.display = 'none';
    });
    const target = document.getElementById(prefix + '-' + sub);
    if (target) target.style.display = 'block';

    const titleEl = document.getElementById(prefix + '-title');
    if (titleEl) titleEl.textContent = titles[page][sub] || page;

    document.querySelectorAll('#submenu-' + prefix + ' .sb-sub-item').forEach(i => i.classList.remove('active'));
    if (el) el.classList.add('active');
  }