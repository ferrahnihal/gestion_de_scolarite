const allPrefixes = ['mod','et','nt'];

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
      modules:   { liste: '📚 Mes modules' },
      etudiants: { liste: '👤 Liste des étudiants' },
      notes:     { saisir: '📝 Saisir les notes', modifier: '✏️ Modifier les notes' }
    };
    document.getElementById('page-title').textContent = titles[page][sub] || page;

    ['liste','saisir','modifier'].forEach(s => {
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

  // Recherche par matricule dans les tableaux
  function filtrerTableau(tableId, valeur) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    const v = valeur.toLowerCase().trim();
    rows.forEach(row => {
      const matricule = row.cells[0].textContent.toLowerCase();
      row.style.display = matricule.includes(v) ? '' : 'none';
    });
  }

  function ouvrirModal(mat, mod_id, note, type) {
    document.getElementById('modal-note-mat').value  = mat;
    document.getElementById('modal-note-mod').value  = mod_id;
    document.getElementById('modal-note-val').value  = note;
    document.getElementById('modal-note-type').value = type;
    document.getElementById('modal-overlay').classList.add('open');
  }
  function fermerModal() {
    document.getElementById('modal-overlay').classList.remove('open');
  }

  document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) fermerModal();
  });

function calculerMoyenne(input) {
    const row = input.closest('tr');
    const inputs = row.querySelectorAll('.note-input');
    const cc   = parseFloat(inputs[0].value.replace(',', '.'));
    const tp   = parseFloat(inputs[1].value.replace(',', '.'));
    const exam = parseFloat(inputs[2].value.replace(',', '.'));
    const cell = row.querySelector('.moyenne-cell');

    if (!isNaN(cc) && !isNaN(tp) && !isNaN(exam)) {
        const moy = (cc * 0.20 + tp * 0.20 + exam * 0.60).toFixed(2);
        cell.textContent = moy;
        cell.style.color = moy >= 10 ? '#388E3C' : '#D32F2F';
    } else {
        cell.textContent = '—';
        cell.style.color = '#aaa';
    }
}