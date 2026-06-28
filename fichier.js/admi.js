
const pageIcons = { etudiants:'👤', enseignants:'🎓', modules:'📚', notes:'📝' };
const subLabels  = { liste:'Liste', ajouter:'Ajouter', modifier:'Modifier', supprimer:'Supprimer', releve:'Relevé de notes' };

function goPage(id, el) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.sb-item').forEach(i => i.classList.remove('active'));

  const page = document.getElementById('page-' + id);
  if (page) page.classList.add('active');

  document.getElementById('page-title').textContent = 'Tableau de bord';
  if (el) el.classList.add('active');
}

function toggleMenu(prefix, el) {
  const sub   = document.getElementById('submenu-' + prefix);
  const arrow = document.getElementById('arrow-'   + prefix);

  if (!sub || !arrow) return;

  const isOpen = sub.classList.contains('open');

  ['et','ens','mod','nt'].forEach(p => {
    const s = document.getElementById('submenu-' + p);
    const a = document.getElementById('arrow-' + p);
    const m = document.getElementById('menu-' + p);

    if (s) s.classList.remove('open');
    if (a) a.classList.remove('open');
    if (m) m.classList.remove('active');
  });

  if (!isOpen) {
    sub.classList.add('open');
    arrow.classList.add('open');
    if (el) el.classList.add('active');
  }
}

function goSub(page, sub, prefix, el) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));

  const pageEl = document.getElementById('page-' + page);
  if (pageEl) pageEl.classList.add('active');

  document.getElementById('page-title').textContent = pageIcons[page] + ' ' + subLabels[sub];

  ['liste','ajouter','modifier','supprimer','releve'].forEach(s => {
    const e = document.getElementById(prefix + '-' + s);
    if (e) e.style.display = 'none';
  });

  const current = document.getElementById(prefix + '-' + sub);
  if (current) current.style.display = 'block';

  const title = document.getElementById(prefix + '-title');
  if (title) title.textContent = pageIcons[page] + ' ' + subLabels[sub];

  document.querySelectorAll('#submenu-' + prefix + ' .sb-sub-item')
    .forEach(i => i.classList.remove('active'));

  if (el) el.classList.add('active');
}

// ─── ETUDIANTS ─────────────────
function remplirModifierEtudiant(mat, nom, prenom, niveau, email) {
  goSub('etudiants','modifier','et', document.querySelector('#submenu-et .sb-sub-item:nth-child(3)'));
  document.getElementById('mod_mat_original').value = mat;
  document.getElementById('mod_mat').value = mat;
  document.getElementById('mod_nom').value = nom;
  document.getElementById('mod_prenom').value = prenom;
  document.getElementById('mod_email').value = email;
  document.getElementById('mod_niveau').value = niveau;
}

function confirmerSupprimerEtudiant(mat) {
  goSub('etudiants','supprimer','et', document.querySelector('#submenu-et .sb-sub-item:nth-child(4)'));
  document.getElementById('mat-suppr-et').value = mat;
  document.getElementById('nom-suppr-et').textContent = mat;
  document.getElementById('confirm-suppr-et').style.display = 'block';
}

// ─── ENSEIGNANTS ───────────────
function remplirModifierEns(id, nom, prenom, email, module_id) {
  goSub('enseignants','modifier','ens', document.querySelector('#submenu-ens .sb-sub-item:nth-child(3)'));
  document.getElementById('mod_ens_id').value = id;
  document.getElementById('mod_ens_nom').value = nom;
  document.getElementById('mod_ens_prenom').value = prenom;
  document.getElementById('mod_ens_email').value = email;
  document.getElementById('mod_ens_module').value = module_id;
}

function confirmerSupprimerEnseignant(id, nom){
  goSub('enseignants','supprimer','ens', document.querySelector('#submenu-ens .sb-sub-item:nth-child(4)'));
  document.getElementById('id-suppr-ens').value = id;
  document.getElementById('nom-suppr-ens').textContent = nom;
  document.getElementById('confirm-suppr-ens').style.display = 'block';
}

// ─── MODULES ───────────────
function remplirModifierModule(code, nom, coef, ens_id) {
  goSub('modules','modifier','mod', document.querySelector('#submenu-mod .sb-sub-item:nth-child(3)'));
  document.getElementById('mod_code_original').value = code;

  // ✅ CORRECTION ICI
  document.getElementById('mod_code_display').value = code;

  document.getElementById('mod_mod_nom').value = nom;
  document.getElementById('mod_mod_coef').value = coef;
  document.getElementById('mod_mod_ens').value = ens_id;
}

function confirmerSupprimerModule(code, nom) {
  goSub('modules','supprimer','mod', document.querySelector('#submenu-mod .sb-sub-item:nth-child(4)'));
  document.getElementById('code-suppr-mod').value = code;
  document.getElementById('nom-suppr-mod').textContent = nom;
  document.getElementById('confirm-suppr-mod').style.display = 'block';
}

// ─── NOTES ───────────────
function remplirModifierNote(id, note, type) {
  goSub('notes','modifier','nt', document.querySelector('#submenu-nt .sb-sub-item:nth-child(3)'));
  document.getElementById('mod_nt_id').value = id;
  document.getElementById('mod_nt_note').value = note;
  document.getElementById('mod_nt_type').value = type;
}

function confirmerSupprimerNote(id) {
  goSub('notes','supprimer','nt', document.querySelector('#submenu-nt .sb-sub-item:nth-child(4)'));
  document.getElementById('id-suppr-nt').value = id;
  document.getElementById('confirm-suppr-nt').style.display = 'block';
}
function filtrerTableauEnseignant(searchText) {
  const table = document.getElementById('tableEnseignants');
  if (!table) return;
  const tbody = table.getElementsByTagName('tbody')[0];
  const rows = tbody.getElementsByTagName('tr');
  const searchLower = searchText.toLowerCase();
  for (let i = 0; i < rows.length; i++) {
    const nomCell = rows[i].getElementsByTagName('td')[0];
    if (nomCell) {
      const nom = nomCell.textContent || nomCell.innerText;
      rows[i].style.display = nom.toLowerCase().includes(searchLower) ? '' : 'none';
    }
  }
}
function filtrerParModule() {
    const moduleSelect = document.getElementById('filtre-module');
    const searchInput = document.getElementById('search-notes');
    
    if (!moduleSelect || !searchInput) return;
    
    const module = moduleSelect.value;
    const search = searchInput.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#tableNotes tbody tr');
    
    rows.forEach(row => {
        const rowModule = row.dataset.module || '';
        const matricule = row.cells[0] ? row.cells[0].textContent.toLowerCase().trim() : '';
        
        const moduleOk = module === '' || rowModule === module;
        const searchOk = search === '' || matricule.includes(search);
        
        row.style.display = (moduleOk && searchOk) ? '' : 'none';
    });
}