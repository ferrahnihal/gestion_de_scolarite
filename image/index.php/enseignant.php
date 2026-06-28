<!-- Ferrah Nihal Yasmine G1 242431423103
     Ziane Damia Feriel G1 232431847516
	 Ouldedine Chaima 232331595914
	 Djahel Yousra 232331406306 -->
	 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Connexion BDD
$conn = mysqli_connect("localhost", "root", "", "miniprojet");
if (!$conn) { die("Connexion échouée : " . mysqli_connect_error()); }

// ─── TRAITEMENT ───────────────────────────────────────────────

// Saisir les notes
if (isset($_POST['valider_notes'])) {
    $matricules = $_POST['mats'];
    $notes      = $_POST['notes'];
    $module_id  = mysqli_real_escape_string($conn, $_POST['module_id']);
    $type       = mysqli_real_escape_string($conn, $_POST['type_evaluation']);

    foreach ($matricules as $i => $mat) {
        if ($notes[$i] === '' || $notes[$i] === null) continue;
        $note = mysqli_real_escape_string($conn, $notes[$i]);
        $mat  = mysqli_real_escape_string($conn, $mat);
        $check = mysqli_query($conn, "SELECT id FROM note WHERE Matricule='$mat' AND ID_Module='$module_id'");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn, "UPDATE note SET note='$note', Type_evaluation='$type' WHERE Matricule='$mat' AND ID_Module='$module_id'");
        } else {
            mysqli_query($conn, "INSERT INTO note (Matricule, ID_Module, note, Type_evaluation) VALUES ('$mat','$module_id','$note','$type')");
        }
        // Vérifier erreur SQL
        if (mysqli_error($conn)) error_log(mysqli_error($conn));
    }
    $success = "Notes enregistrées avec succès !";
}

// Modifier une note (pas de colonne id, on utilise Matricule + ID_Module)
if (isset($_POST['modifier_note'])) {
    $mat      = mysqli_real_escape_string($conn, $_POST['matricule_note']);
    $mod_id   = mysqli_real_escape_string($conn, $_POST['module_note']);
    $note     = mysqli_real_escape_string($conn, $_POST['note']);
    $type     = mysqli_real_escape_string($conn, $_POST['type_evaluation']);
    mysqli_query($conn, "UPDATE note SET note='$note', Type_evaluation='$type' WHERE Matricule='$mat' AND ID_Module='$mod_id'");
    $success = "Note modifiée avec succès !";
}

// ─── DONNÉES ──────────────────────────────────────────────────
// id_Enseinant est la clé primaire dans ta BDD (avec cette orthographe)
$ens_id = $_SESSION['user']['id_Enseinant']; // remplacer par $_SESSION['user']['id_Enseinant']

$res_ens = mysqli_query($conn, "SELECT * FROM enseignant WHERE id_Enseinant=$ens_id");
$enseignant = mysqli_fetch_assoc($res_ens);
$nom_complet = ($enseignant['Prenom'] ?? '') . ' ' . ($enseignant['Nom'] ?? '');
$initiales   = strtoupper(substr($enseignant['Prenom'] ?? 'E', 0, 1) . substr($enseignant['Nom'] ?? 'N', 0, 1));

// Les modules de cet enseignant (via ID-MODULE dans la table enseignant)
$module_ens = mysqli_real_escape_string($conn, $enseignant['ID-MODULE'] ?? '');
$res_mod = mysqli_query($conn, "SELECT * FROM module WHERE `ID-MODULE`='$module_ens'");
$modules = [];
while ($r = mysqli_fetch_assoc($res_mod)) $modules[] = $r;

$res_et = mysqli_query($conn, "SELECT * FROM etudiant ORDER BY NOM");
$etudiants = [];
while ($r = mysqli_fetch_assoc($res_et)) $etudiants[] = $r;

$res_notes = mysqli_query($conn, "
    SELECT n.*, e.NOM, e.PRENOM, e.Matricule as mat_et, m.NOM_MODULE
    FROM note n
    JOIN etudiant e ON n.Matricule = e.Matricule
    JOIN module m ON n.ID_Module = m.`ID-MODULE`
    WHERE n.ID_Module = '$module_ens'
    ORDER BY m.NOM_MODULE, e.NOM
");
$notes = [];
while ($r = mysqli_fetch_assoc($res_notes)) $notes[] = $r;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>USTHB — Espace Enseignant</title>
  <link rel="stylesheet" href="../fichier.css/enseignant.css">
</head>
<body>

<div class="wrap">
  <aside class="sidebar">
    <div class="sb-head">
      <div class="sb-logo"><img src="../image/usthb1.webp" style="width:90px" height="90" alt="Logo"></div>
      <div class="sb-sub">Espace Enseignant — 2025/2026</div>
    </div>
    <nav class="sb-nav">
      <div class="sb-section">Principal</div>
      <div class="sb-item active" onclick="goPage('accueil',this)" id="menu-accueil">
        <div class="sb-item-left"><span class="sb-icon">🏠</span> Tableau de bord</div>
      </div>
      <div class="sb-section">Mes cours</div>

      <div class="sb-item" onclick="toggleMenu('mod',this)" id="menu-mod">
        <div class="sb-item-left"><span class="sb-icon">📚</span> Mes modules</div>
        <span class="arrow" id="arrow-mod">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-mod">
        <div class="sb-sub-item" onclick="goSub('modules','liste','mod',this)"><span class="sb-sub-dot"></span> Voir mes modules</div>
      </div>

      <div class="sb-item" onclick="toggleMenu('et',this)" id="menu-et">
        <div class="sb-item-left"><span class="sb-icon">👤</span> Mes étudiants</div>
        <span class="arrow" id="arrow-et">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-et">
        <div class="sb-sub-item" onclick="goSub('etudiants','liste','et',this)"><span class="sb-sub-dot"></span> Liste des étudiants</div>
      </div>

      <div class="sb-item" onclick="toggleMenu('nt',this)" id="menu-nt">
        <div class="sb-item-left"><span class="sb-icon">📝</span> Notes</div>
        <span class="arrow" id="arrow-nt">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-nt">
        <div class="sb-sub-item" onclick="goSub('notes','saisir','nt',this)"><span class="sb-sub-dot"></span> Saisir les notes</div>
        <div class="sb-sub-item" onclick="goSub('notes','modifier','nt',this)"><span class="sb-sub-dot"></span> Modifier les notes</div>
      </div>
    </nav>
    <div class="sb-foot">
      <div class="logout" onclick="window.location='login.php'"><span class="sb-icon">↩</span> Déconnexion</div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div class="tb-title" id="page-title">Tableau de bord</div>
      <div class="tb-right">
        <div>
          <div class="uname"><?= htmlspecialchars($nom_complet) ?></div>
          <div class="urole">Enseignant</div>
        </div>
        <div class="av"><?= $initiales ?></div>
      </div>
    </div>

    <div class="content">

      <?php if (isset($success)): ?>
        <div class="alert">✅ <?= $success ?></div>
      <?php endif; ?>

      <!-- ACCUEIL -->
      <div class="page active" id="page-accueil">
        <div class="welcome-card">
          <div>
            <div class="wt">Bonjour, <?= htmlspecialchars($enseignant['Prenom'] ?? '') ?> 👋</div>
            <div class="ws">Espace enseignant — USTHB</div>
          </div>
          <div class="welcome-badge">2025 / 2026 — S2</div>
        </div>
        <div class="kpi-row">
          <div class="kpi"><div class="kpi-lbl">Mes modules</div><div class="kpi-val"><?= count($modules) ?></div><div class="kpi-sub">Ce semestre</div></div>
          <div class="kpi g"><div class="kpi-lbl">Étudiants</div><div class="kpi-val"><?= count($etudiants) ?></div><div class="kpi-sub">Dans la base</div></div>
          <div class="kpi o"><div class="kpi-lbl">Notes saisies</div><div class="kpi-val"><?= count($notes) ?></div><div class="kpi-sub">Total</div></div>
        </div>
      </div>

      <!-- MODULES -->
      <div class="page" id="page-modules">
        <div class="card">
          <div class="card-head"><div class="card-title" id="mod-title">📚 Mes modules</div></div>
          <div id="mod-liste">
            <table>
              <thead><tr><th>Code</th><th>Nom du module</th><th>Coefficient</th></tr></thead>
              <tbody>
                <?php if (count($modules) > 0): ?>
                  <?php foreach ($modules as $m): ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($m['ID-MODULE']) ?></strong></td>
                    <td><?= htmlspecialchars($m['NOM_MODULE']) ?></td>
                    <td><?= htmlspecialchars($m['CONFESSION']) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3" style="text-align:center;color:#aaa;padding:30px;">Aucun module assigné</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ETUDIANTS -->
      <div class="page" id="page-etudiants">
        <div class="card">
          <div class="card-head"><div class="card-title" id="et-title">👤 Liste des étudiants</div></div>
          <div id="et-liste">
    <div class="filter-bar">
      <div class="form-group">
        <label class="form-label">Rechercher par matricule</label>
        <input
          class="form-input"
          type="text"
          placeholder="🔍 ex: 212131..."
          oninput="filtrerTableau('table-etudiants', this.value)"
          style="width:220px;"
        >
      </div>
    </div>
    <table id="table-etudiants">
      <thead><tr><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Niveau</th><th>Email</th><th>Statut</th></tr></thead>
              <tbody>
                <?php if (count($etudiants) > 0): ?>
                  <?php foreach ($etudiants as $e):
                    $enLigne = isset($e['derniere_connexion']) && $e['derniere_connexion'] && (time() - strtotime($e['derniere_connexion'])) / 60 <= 15;
                  ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($e['Matricule']) ?></strong></td>
                    <td><?= htmlspecialchars($e['NOM']) ?></td>
                    <td><?= htmlspecialchars($e['PRENOM']) ?></td>
                    <td><?= htmlspecialchars($e['niveau'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($e['EMAIL'] ?? '—') ?></td>
                    <td>
                      <span class="status-dot <?= $enLigne ? 'online' : 'offline' ?>"></span>
                      <span style="font-size:11px;color:<?= $enLigne ? '#388E3C' : '#999' ?>">
                        <?= $enLigne ? 'En ligne' : 'Hors ligne' ?>
                      </span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" style="text-align:center;color:#aaa;padding:30px;">Aucun étudiant trouvé</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- NOTES -->
      <div class="page" id="page-notes">
        <div class="card">
          <div class="card-head"><div class="card-title" id="nt-title">📝 Saisir les notes</div></div>

          <!-- SAISIR -->
          <!-- SAISIR -->
          <div id="nt-saisir">
            <form method="POST" action="">
              <div class="filter-bar">
                <div class="form-group">
                  <label class="form-label">Module</label>
                  <select class="form-select" name="module_id">
                    <?php foreach ($modules as $m): ?>
                    <option value="<?= $m['ID-MODULE'] ?>"><?= htmlspecialchars($m['NOM_MODULE']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Type d'évaluation</label>
                  <select class="form-select" name="type_evaluation">
                    <option>Examen final</option>
                    <option>Contrôle continu</option>
                    <option>TP</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Rechercher par matricule</label>
                  <input
                    class="form-input"
                    type="text"
                    id="search-saisir"
                    placeholder="🔍 ex: 212131..."
                    oninput="filtrerTableau('table-saisir', this.value)"
                    style="width:200px;"
                  >
                </div>
              </div>
              <table id="table-saisir">
                <thead><tr><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Note /20</th></tr></thead>
                <tbody>
                  <?php foreach ($etudiants as $e): ?>
                  <tr>
                    <td><?= htmlspecialchars($e['Matricule']) ?></td>
                    <td><?= htmlspecialchars($e['NOM']) ?></td>
                    <td><?= htmlspecialchars($e['PRENOM']) ?></td>
                    <td>
                      <input type="hidden" name="mats[]" value="<?= $e['Matricule'] ?>">
                      <input class="note-input" type="number" name="notes[]" min="0" max="20" step="0.25" placeholder="—">
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <button type="submit" name="valider_notes" class="btn-validate">💾 Enregistrer toutes les notes</button>
            </form>
          </div>

          <!-- MODIFIER -->
          <div id="nt-modifier" style="display:none;">
            <div class="filter-bar">
              <div class="form-group">
                <label class="form-label">Rechercher par matricule</label>
                <input
                  class="form-input"
                  type="text"
                  id="search-modifier"
                  placeholder="🔍 ex: 212131..."
                  oninput="filtrerTableau('table-modifier', this.value)"
                  style="width:200px;"
                >
              </div>
            </div>
            <table id="table-modifier">
              <thead><tr><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Module</th><th>Note</th><th>Type</th><th>Action</th></tr></thead>
              <tbody>
                <?php if (count($notes) > 0): ?>
                  <?php foreach ($notes as $n): ?>
                  <tr>
                    <td><?= htmlspecialchars($n['mat_et']) ?></td>
                    <td><?= htmlspecialchars($n['NOM']) ?></td>
                    <td><?= htmlspecialchars($n['PRENOM']) ?></td>
                    <td><?= htmlspecialchars($n['NOM_MODULE']) ?></td>
                    <td><strong><?= $n['note'] ?></strong></td>
                    <td><?= htmlspecialchars($n['Type_evaluation']) ?></td>
                    <td>
                      <button class="btn-modifier" onclick="ouvrirModal('<?= addslashes($n['mat_et']) ?>', '<?= addslashes($n['ID_Module']) ?>', <?= $n['note'] ?>, '<?= addslashes($n['Type_evaluation']) ?>')">✏️ Modifier</button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" style="text-align:center;color:#aaa;padding:30px;">Aucune note trouvée</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<!-- MODAL MODIFIER NOTE -->
<div class="modal-overlay" id="modal-overlay">
  <div class="modal">
    <div class="modal-title">✏️ Modifier la note</div>
    <form method="POST" action="">
      <input type="hidden" name="modifier_note" value="1">
      <input type="hidden" name="matricule_note" id="modal-note-mat">
      <input type="hidden" name="module_note" id="modal-note-mod">
      <div class="modal-row">
        <label class="modal-label">Nouvelle note /20</label>
        <input class="modal-input" type="number" name="note" id="modal-note-val" min="0" max="20" step="0.25" required>
      </div>
      <div class="modal-row">
        <label class="modal-label">Type d'évaluation</label>
        <select class="modal-select" name="type_evaluation" id="modal-note-type">
          <option>Examen final</option>
          <option>Contrôle continu</option>
          <option>TP</option>
        </select>
      </div>
      <div class="modal-btns">
        <button type="button" class="modal-cancel" onclick="fermerModal()">Annuler</button>
        <button type="submit" class="form-submit">✔ Sauvegarder</button>
      </div>
    </form>
  </div>
</div>
<script src="../fichier.js/enseignat.js"></script>
</body>
</html>
