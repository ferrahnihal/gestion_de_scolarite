<?php
// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "miniproject");
if (!$conn) { die("Erreur de connexion : " . mysqli_connect_error()); }

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  // ─── ETUDIANTS ───────────────────────────────
  if ($action === 'ajouter_etudiant') {
    $mat    = mysqli_real_escape_string($conn, $_POST['matricule']);
    $nom    = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $niveau = mysqli_real_escape_string($conn, $_POST['niveau']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $mdp    = mysqli_real_escape_string($conn, $_POST['mot_de_passe']);
    mysqli_query($conn, "INSERT INTO etudiant (Matricule, NOM, PRENOM, niveau, email, mot_de_passe) VALUES ('$mat','$nom','$prenom','$niveau','$email','$mdp')");
  }

  if ($action === 'supprimer_etudiant') {
    $mat = mysqli_real_escape_string($conn, $_POST['matricule']);
    mysqli_query($conn, "DELETE FROM etudiant WHERE Matricule='$mat'");
  }

  if ($action === 'modifier_etudiant') {
    $mat    = mysqli_real_escape_string($conn, $_POST['matricule_original']);
    $nom    = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $niveau = mysqli_real_escape_string($conn, $_POST['niveau']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    mysqli_query($conn, "UPDATE etudiant SET NOM='$nom', PRENOM='$prenom', niveau='$niveau', email='$email' WHERE Matricule='$mat'");
  }

  // ─── ENSEIGNANTS ─────────────────────────────
  if ($action === 'ajouter_enseignant') {
    $nom    = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $mdp    = mysqli_real_escape_string($conn, $_POST['mot_de_passe']);
    $mod_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    mysqli_query($conn, "INSERT INTO enseignant (Nom, Prenom, email, mot_de_passe, `ID-MODULE`) VALUES ('$nom','$prenom','$email','$mdp','$mod_id')");
  }

  if ($action === 'supprimer_enseignant') {
    $id = (int)$_POST['id_Enseinant'];
    mysqli_query($conn, "DELETE FROM enseignant WHERE id_Enseinant=$id");
  }

  if ($action === 'modifier_enseignant') {
    $id     = (int)$_POST['id'];
    $nom    = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $mod_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    mysqli_query($conn, "UPDATE enseignant SET Nom='$nom', Prenom='$prenom', email='$email', `ID-MODULE`='$mod_id' WHERE id_Enseinant=$id");
  }

  // ─── MODULES ─────────────────────────────────
  if ($action === 'ajouter_module') {
    $code   = mysqli_real_escape_string($conn, $_POST['code']);
    $nom    = mysqli_real_escape_string($conn, $_POST['nom']);
    $coef   = mysqli_real_escape_string($conn, $_POST['coefficient']);
    $ens_id = mysqli_real_escape_string($conn, $_POST['enseignant_id']);
    mysqli_query($conn, "INSERT INTO module (`ID-MODULE`, NOM_MODULE, CONFESSION, `ENSEIGNANT RESPONSABLE`) VALUES ('$code','$nom','$coef','$ens_id')");
  }

  if ($action === 'supprimer_module') {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    mysqli_query($conn, "DELETE FROM module WHERE `ID-MODULE`='$code'");
  }

  if ($action === 'modifier_module') {
    $code     = mysqli_real_escape_string($conn, $_POST['code_original']);
    $nom      = mysqli_real_escape_string($conn, $_POST['nom']);
    $coef     = mysqli_real_escape_string($conn, $_POST['coefficient']);
    $ens_id   = mysqli_real_escape_string($conn, $_POST['enseignant_id']);
    mysqli_query($conn, "UPDATE module SET NOM_MODULE='$nom', CONFESSION='$coef', `ENSEIGNANT RESPONSABLE`='$ens_id' WHERE `ID-MODULE`='$code'");
  }

  // ─── NOTES ───────────────────────────────────
  if ($action === 'ajouter_note') {
    $mat    = mysqli_real_escape_string($conn, $_POST['matricule']);
    $mod_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    $note   = mysqli_real_escape_string($conn, $_POST['note']);
    $type   = mysqli_real_escape_string($conn, $_POST['type_evaluation']);
    mysqli_query($conn, "INSERT INTO note (Matricule, ID_Module, note, Type_evaluation) VALUES ('$mat','$mod_id','$note','$type')");
  }

  if ($action === 'supprimer_note') {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM note WHERE id=$id");
  }

  if ($action === 'modifier_note') {
    $id   = (int)$_POST['id'];
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $type = mysqli_real_escape_string($conn, $_POST['type_evaluation']);
    mysqli_query($conn, "UPDATE note SET note='$note', Type_evaluation='$type' WHERE id=$id");
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

// Récupérer données pour les selects
$res_modules     = mysqli_query($conn, "SELECT * FROM module");
$liste_modules   = [];
while ($r = mysqli_fetch_assoc($res_modules)) $liste_modules[] = $r;

$res_enseignants   = mysqli_query($conn, "SELECT * FROM enseignant");
$liste_enseignants = [];
while ($r = mysqli_fetch_assoc($res_enseignants)) $liste_enseignants[] = $r;

$res_etudiants_sel = mysqli_query($conn, "SELECT * FROM etudiant");
$liste_etudiants   = [];
while ($r = mysqli_fetch_assoc($res_etudiants_sel)) $liste_etudiants[] = $r;
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration USTHB</title>
    <link rel="stylesheet" href="../fichier.css/admi.css">
</head>
<body>

<div class="wrap">
  <aside class="sidebar">
    <div class="sb-head">
      <div class="sb-logo"><img src="../image/usthb1.webp" style="width:90px" height="90" alt="Logo"></div>
      <div class="sb-sub">Administration — 2025/2026</div>
    </div>
    <nav class="sb-nav">
      <div class="sb-section">Principal</div>
      <div class="sb-item" onclick="goPage('accueil',this)" id="menu-accueil">
        <div class="sb-item-left"><span class="sb-icon">🏠</span> Tableau de bord</div>
      </div>

      <div class="sb-section">Gestion</div>

      <div class="sb-item" onclick="toggleMenu('et',this)" id="menu-et">
        <div class="sb-item-left"><span class="sb-icon">👤</span> Étudiants</div>
        <span class="arrow" id="arrow-et">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-et">
        <div class="sb-sub-item" onclick="goSub('etudiants','liste','et',this)">Liste</div>
        <div class="sb-sub-item" onclick="goSub('etudiants','ajouter','et',this)">Ajouter</div>
        <div class="sb-sub-item" onclick="goSub('etudiants','modifier','et',this)">Modifier</div>
        <div class="sb-sub-item" onclick="goSub('etudiants','supprimer','et',this)">Supprimer</div>
      </div>

      <div class="sb-item" onclick="toggleMenu('ens',this)" id="menu-ens">
        <div class="sb-item-left"><span class="sb-icon">🎓</span> Enseignants</div>
        <span class="arrow" id="arrow-ens">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-ens">
        <div class="sb-sub-item" onclick="goSub('enseignants','liste','ens',this)">Liste</div>
        <div class="sb-sub-item" onclick="goSub('enseignants','ajouter','ens',this)">Ajouter</div>
        <div class="sb-sub-item" onclick="goSub('enseignants','modifier','ens',this)">Modifier</div>
        <div class="sb-sub-item" onclick="goSub('enseignants','supprimer','ens',this)">Supprimer</div>
      </div>

      <div class="sb-item" onclick="toggleMenu('mod',this)" id="menu-mod">
        <div class="sb-item-left"><span class="sb-icon">📚</span> Modules</div>
        <span class="arrow" id="arrow-mod">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-mod">
        <div class="sb-sub-item" onclick="goSub('modules','liste','mod',this)">Liste</div>
        <div class="sb-sub-item" onclick="goSub('modules','ajouter','mod',this)">Ajouter</div>
        <div class="sb-sub-item" onclick="goSub('modules','modifier','mod',this)">Modifier</div>
        <div class="sb-sub-item" onclick="goSub('modules','supprimer','mod',this)">Supprimer</div>
      </div>

      <div class="sb-item" onclick="toggleMenu('nt',this)" id="menu-nt">
        <div class="sb-item-left"><span class="sb-icon">📝</span> Notes</div>
        <span class="arrow" id="arrow-nt">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-nt">
        <div class="sb-sub-item" onclick="goSub('notes','liste','nt',this)">Liste</div>
        <div class="sb-sub-item" onclick="goSub('notes','ajouter','nt',this)">Ajouter</div>
        <div class="sb-sub-item" onclick="goSub('notes','modifier','nt',this)">Modifier</div>
        <div class="sb-sub-item" onclick="goSub('notes','supprimer','nt',this)">Supprimer</div>
      </div>
    </nav>
    <div class="sb-foot">
      <button onclick="window.location.href='acceuil1.php'">Déconnexion</button>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div class="tb-title" id="page-title">Tableau de bord</div>
      <div class="av">AD</div>
    </div>

    <div class="content">

      <!-- ACCUEIL -->
      <div class="page active" id="page-accueil">
        <div class="welcome-card">
          <div><div class="wt">Bienvenue 👋</div><div class="ws">Portail de gestion — USTHB</div></div>
          <div class="welcome-badge">2025 / 2026</div>
        </div>
        <div class="kpi-row">
          <?php
            $nb_et  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM etudiant"))[0];
            $nb_mod = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM module"))[0];
            $nb_ens = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM enseignant"))[0];
          ?>
          <div class="kpi"><div class="kpi-lbl">Étudiants</div><div class="kpi-val"><?= $nb_et ?></div></div>
          <div class="kpi g"><div class="kpi-lbl">Modules</div><div class="kpi-val"><?= $nb_mod ?></div></div>
          <div class="kpi o"><div class="kpi-lbl">Enseignants</div><div class="kpi-val"><?= $nb_ens ?></div></div>
        </div>
      </div>

      <!-- ETUDIANTS -->
      <div class="page" id="page-etudiants">
        <div class="card">
          <div class="card-head"><div class="card-title" id="et-title">👤 Liste des étudiants</div></div>

          <div id="et-liste">
            <div style="padding:12px 16px;border-bottom:1px solid #F0F0F0;">
              <input type="text" placeholder="🔍 Rechercher par matricule..."
                oninput="filtrerTableau('tableEtudiants', this.value)"
                style="padding:7px 12px;border:1px solid #E0E0E0;border-radius:8px;font-size:12px;background:#FAFAFA;width:250px;">
            </div>
            <table id="tableEtudiants">
              <thead>
                <tr><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Niveau</th><th>Email</th><th>Actions</th></tr>
              </thead>
              <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM etudiant");
                while($row = mysqli_fetch_assoc($res)):
                ?>
                <tr>
                  <td><?= htmlspecialchars($row['Matricule']) ?></td>
                  <td><?= htmlspecialchars($row['NOM']) ?></td>
                  <td><?= htmlspecialchars($row['PRENOM']) ?></td>
                  <td><?= htmlspecialchars($row['niveau']) ?></td>
                  <td><?= htmlspecialchars($row['EMAIL'] ?? '') ?></td>
                  <td>
                    <button onclick="remplirModifierEtudiant('<?= $row['Matricule'] ?>','<?= addslashes($row['NOM']) ?>','<?= addslashes($row['PRENOM']) ?>','<?= $row['niveau'] ?>','<?= $row['email'] ?? '' ?>')" class="btn-action">✏️</button>
                    <button onclick="confirmerSupprimerEtudiant('<?= $row['Matricule'] ?>')" class="btn-danger">🗑️</button>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div id="et-ajouter" style="display:none;">
            <div class="form-wrap">
              <form method="POST">
                <input type="hidden" name="action" value="ajouter_etudiant">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Matricule</label><input class="form-input" name="matricule" placeholder="ex: 12345" required></div>
                  <div class="form-group"><label class="form-label">Nom</label><input class="form-input" name="nom" placeholder="Nom" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Prénom</label><input class="form-input" name="prenom" placeholder="Prénom" required></div>
                  <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" placeholder="email@usthb.dz"></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Mot de passe</label><input class="form-input" type="password" name="mot_de_passe" placeholder="••••••••" required></div>
                  <div class="form-group"><label class="form-label">Niveau</label>
                    <select class="form-select" name="niveau">
                      <option>L2 INFO</option><option>L2 ISIL</option><option>L3 INFO</option><option>L3 ISIL</option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Enregistrer</button>
              </form>
            </div>
          </div>

          <div id="et-modifier" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur ✏️ dans la liste pour modifier un étudiant.</p>
              <form method="POST" id="form-modifier-etudiant">
                <input type="hidden" name="action" value="modifier_etudiant">
                <input type="hidden" name="matricule_original" id="mod_mat_original">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Matricule</label><input class="form-input" id="mod_mat" disabled></div>
                  <div class="form-group"><label class="form-label">Nom</label><input class="form-input" name="nom" id="mod_nom" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Prénom</label><input class="form-input" name="prenom" id="mod_prenom" required></div>
                  <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" id="mod_email"></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Niveau</label>
                    <select class="form-select" name="niveau" id="mod_niveau">
                      <option>L2 INFO</option><option>L2 ISIL</option><option>L3 INFO</option><option>L3 ISIL</option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Sauvegarder</button>
              </form>
            </div>
          </div>

          <div id="et-supprimer" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur 🗑️ dans la liste pour supprimer un étudiant.</p>
              <div id="confirm-suppr-et" style="display:none;background:#FFF5F5;border:1px solid #FFCDD2;border-radius:8px;padding:16px;">
                <p style="font-size:13px;font-weight:bold;color:#B71C1C;margin-bottom:10px;">⚠️ Confirmer la suppression</p>
                <p style="font-size:12px;color:#555;margin-bottom:14px;">Étudiant : <strong id="nom-suppr-et"></strong></p>
                <form method="POST">
                  <input type="hidden" name="action" value="supprimer_etudiant">
                  <input type="hidden" name="matricule" id="mat-suppr-et">
                  <button type="submit" style="padding:8px 16px;background:#E53935;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;">🗑️ Confirmer</button>
                  <button type="button" onclick="document.getElementById('confirm-suppr-et').style.display='none'" style="padding:8px 16px;background:#fff;border:1px solid #ddd;border-radius:8px;cursor:pointer;margin-left:8px;">Annuler</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- ENSEIGNANTS -->
      <div class="page" id="page-enseignants">
        <div class="card">
          <div class="card-head"><div class="card-title" id="ens-title">🎓 Liste des enseignants</div></div>

          <div id="ens-liste">
            <div style="padding:12px 16px;border-bottom:1px solid #F0F0F0;">
              <input type="text" placeholder="🔍 Rechercher par nom..."
                oninput="filtrerTableauEnseignant(this.value)"
                style="padding:7px 12px;border:1px solid #E0E0E0;border-radius:8px;font-size:12px;background:#FAFAFA;width:250px;">
            </div>
            <table id="tableEnseignants">
              <thead><tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Module ID</th><th>Actions</th></tr></thead>
              <tbody>
                <?php
                $res_ens = mysqli_query($conn, "SELECT * FROM enseignant");
                while($row = mysqli_fetch_assoc($res_ens)):
                ?>
                <tr>
                  <td><?= htmlspecialchars($row['Nom']) ?></td>
                  <td><?= htmlspecialchars($row['Prenom']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['ID-MODULE'] ?? '—') ?></td>
                  <td>
                    <button onclick="remplirModifierEns(
<?= $row['id_Enseinant'] ?>,
'<?= htmlspecialchars($row['Nom'], ENT_QUOTES) ?>',
'<?= htmlspecialchars($row['Prenom'], ENT_QUOTES) ?>',
'<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>',
'<?= htmlspecialchars($row['ID-MODULE'] ?? '', ENT_QUOTES) ?>'
)" class="btn-action">✏️</button>
                    <button onclick="confirmerSupprimerEnseignant('<?php echo $row['id_Enseinant']; ?>')" class="btn-danger">🗑️</button>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div id="ens-ajouter" style="display:none;">
            <div class="form-wrap">
              <form method="POST">
                <input type="hidden" name="action" value="ajouter_enseignant">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Nom</label><input class="form-input" name="nom" placeholder="Nom" required></div>
                  <div class="form-group"><label class="form-label">Prénom</label><input class="form-input" name="prenom" placeholder="Prénom" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" placeholder="email@usthb.dz" required></div>
                  <div class="form-group"><label class="form-label">Mot de passe</label><input class="form-input" type="password" name="mot_de_passe" placeholder="••••••••" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Module responsable</label>
                    <select class="form-select" name="module_id">
                      <?php foreach($liste_modules as $m): ?>
                      <option value="<?= $m['ID-MODULE'] ?>"><?= $m['NOM_MODULE'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Enregistrer</button>
              </form>
            </div>
          </div>

          <div id="ens-modifier" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur ✏️ dans la liste pour modifier un enseignant.</p>
              <form method="POST" id="form-modifier-ens">
                <input type="hidden" name="action" value="modifier_enseignant">
                <input type="hidden" name="id" id="mod_ens_id">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Nom</label><input class="form-input" name="nom" id="mod_ens_nom" required></div>
                  <div class="form-group"><label class="form-label">Prénom</label><input class="form-input" name="prenom" id="mod_ens_prenom" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" id="mod_ens_email"></div>
                  <div class="form-group"><label class="form-label">Module</label>
                    <select class="form-select" name="module_id" id="mod_ens_module">
                      <?php foreach($liste_modules as $m): ?>
                      <option value="<?= $m['ID-MODULE'] ?>"><?= $m['NOM_MODULE'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Sauvegarder</button>
              </form>
            </div>
          </div>

          <div id="ens-supprimer" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur 🗑️ dans la liste pour supprimer un enseignant.</p>
              <div id="confirm-suppr-ens" style="display:none;background:#FFF5F5;border:1px solid #FFCDD2;border-radius:8px;padding:16px;">
                <p style="font-size:13px;font-weight:bold;color:#B71C1C;margin-bottom:10px;">⚠️ Confirmer la suppression</p>
                <p style="font-size:12px;color:#555;margin-bottom:14px;">Enseignant : <strong id="nom-suppr-ens"></strong></p>
                <form method="POST">
                  <input type="hidden" name="action" value="supprimer_enseignant">
                  <input type="hidden" name="id" id="id-suppr-ens">
                  <button type="submit" style="padding:8px 16px;background:#E53935;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;">🗑️ Confirmer</button>
                  <button type="button" onclick="document.getElementById('confirm-suppr-ens').style.display='none'" style="padding:8px 16px;background:#fff;border:1px solid #ddd;border-radius:8px;cursor:pointer;margin-left:8px;">Annuler</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- MODULES -->
      <div class="page" id="page-modules">
        <div class="card">
          <div class="card-head"><div class="card-title" id="mod-title">📚 Liste des modules</div></div>

          <div id="mod-liste">
            <table>
              <thead><tr><th>Code</th><th>Nom</th><th>Coefficient</th><th>Responsable</th><th>Actions</th></tr></thead>
              <tbody>
                <?php
                $res_mod = mysqli_query($conn, "SELECT * FROM module");
                while($row = mysqli_fetch_assoc($res_mod)):
                ?>
                <tr>
                  <td><?= htmlspecialchars($row['ID-MODULE']) ?></td>
                  <td><?= htmlspecialchars($row['NOM_MODULE']) ?></td>
                  <td><?= htmlspecialchars($row['CONFESSION']) ?></td>
                  <td><?= htmlspecialchars($row['ENSEIGNANT RESPONSABLE'] ?? '—') ?></td>
                  <td>
                    <button onclick="remplirModifierModule('<?= addslashes($row['ID-MODULE']) ?>','<?= addslashes($row['NOM_MODULE']) ?>','<?= $row['CONFESSION'] ?>','<?= addslashes($row['ENSEIGNANT RESPONSABLE'] ?? '') ?>')" class="btn-action">✏️</button>
                    <button onclick="confirmerSupprimerModule('<?= addslashes($row['ID-MODULE']) ?>','<?= addslashes($row['NOM_MODULE']) ?>')" class="btn-danger">🗑️</button>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div id="mod-ajouter" style="display:none;">
            <div class="form-wrap">
              <form method="POST">
                <input type="hidden" name="action" value="ajouter_module">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Code module</label><input class="form-input" name="code" placeholder="ex: PWEB" required></div>
                  <div class="form-group"><label class="form-label">Nom du module</label><input class="form-input" name="nom" placeholder="Programmation Web" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Coefficient</label><input class="form-input" type="number" name="coefficient" placeholder="3" required></div>
                  <div class="form-group"><label class="form-label">Enseignant responsable</label>
                    <select class="form-select" name="enseignant_id">
                      <?php foreach($liste_enseignants as $e): ?>
                      <option value="<?= $e['id_Enseinant'] ?>"><?= $e['Nom'] ?> <?= $e['Prenom'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Enregistrer</button>
              </form>
            </div>
          </div>

          <div id="mod-modifier" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur ✏️ dans la liste pour modifier un module.</p>
              <form method="POST">
                <input type="hidden" name="action" value="modifier_module">
                <input type="hidden" name="code_original" id="mod_code_original">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Code</label><input class="form-input" id="mod_code_display" disabled></div>
                  <div class="form-group"><label class="form-label">Nom</label><input class="form-input" name="nom" id="mod_mod_nom" required></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Coefficient</label><input class="form-input" type="number" name="coefficient" id="mod_mod_coef"></div>
                  <div class="form-group"><label class="form-label">Enseignant</label>
                    <select class="form-select" name="enseignant_id" id="mod_mod_ens">
                      <?php foreach($liste_enseignants as $e): ?>
                      <option value="<?= $e['id_Enseinant'] ?>"><?= $e['Nom'] ?> <?= $e['Prenom'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Sauvegarder</button>
              </form>
            </div>
          </div>

          <div id="mod-supprimer" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur 🗑️ dans la liste pour supprimer un module.</p>
              <div id="confirm-suppr-mod" style="display:none;background:#FFF5F5;border:1px solid #FFCDD2;border-radius:8px;padding:16px;">
                <p style="font-size:13px;font-weight:bold;color:#B71C1C;margin-bottom:10px;">⚠️ Confirmer la suppression</p>
                <p style="font-size:12px;color:#555;margin-bottom:14px;">Module : <strong id="nom-suppr-mod"></strong></p>
                <form method="POST">
                  <input type="hidden" name="action" value="supprimer_module">
                  <input type="hidden" name="code" id="code-suppr-mod">
                  <button type="submit" style="padding:8px 16px;background:#E53935;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;">🗑️ Confirmer</button>
                  <button type="button" onclick="document.getElementById('confirm-suppr-mod').style.display='none'" style="padding:8px 16px;background:#fff;border:1px solid #ddd;border-radius:8px;cursor:pointer;margin-left:8px;">Annuler</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- NOTES -->
      <div class="page" id="page-notes">
        <div class="card">
          <div class="card-head"><div class="card-title" id="nt-title">📝 Liste des notes</div></div>

          <div id="nt-liste">
            <div style="padding:12px 16px;border-bottom:1px solid #F0F0F0;display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
              <div>
                <label style="font-size:11px;font-weight:bold;color:#555;">Module</label><br>
                <select id="filtre-module" onchange="filtrerParModule()"
                  style="padding:7px 12px;border:1px solid #E0E0E0;border-radius:8px;font-size:12px;background:#FAFAFA;">
                  <option value="">-- Tous les modules --</option>
                  <?php foreach($liste_modules as $m): ?>
                  <option value="<?= $m['ID-MODULE'] ?>"><?= htmlspecialchars($m['NOM_MODULE']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label style="font-size:11px;font-weight:bold;color:#555;">Rechercher par matricule</label><br>
                <input type="text" id="search-notes" placeholder="🔍 ex: 212131..."
                  oninput="filtrerParModule()"
                  style="padding:7px 12px;border:1px solid #E0E0E0;border-radius:8px;font-size:12px;background:#FAFAFA;width:250px;">
              </div>
            </div>

            <table id="tableNotes">
              <thead>
                <tr>
                  <th>Matricule</th>
                  <th>Nom</th>
                  <th>Module</th>
                  <th>Moyenne</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $res_nt = mysqli_query($conn, "
                    SELECT n.*, m.NOM_MODULE, e.NOM, e.PRENOM,
                    ROUND(
                        (COALESCE(n.`Note d'examen`, 0) * 0.6 + 
                         COALESCE(n.`Note de contrôle`, 0) * 0.2 + 
                         COALESCE(n.`Note de tp`, 0) * 0.2)
                    , 2) AS moyenne_calculee
                    FROM note n
                    LEFT JOIN module m ON n.ID_Module = m.`ID-MODULE`
                    LEFT JOIN etudiant e ON n.Matricule = e.Matricule
                    ORDER BY n.ID_Module, e.NOM
                ");
                while($row = mysqli_fetch_assoc($res_nt)):
                ?>
                <tr data-module="<?= $row['ID_Module'] ?>">
                  <td><?= htmlspecialchars($row['Matricule']) ?></td>
                  <td><?= htmlspecialchars($row['NOM'] . ' ' . $row['PRENOM']) ?></td>
                  <td><?= htmlspecialchars($row['NOM_MODULE'] ?? '—') ?></td>
                  <td><?= $row['moyenne_calculee'] ?></td>
                  <td>
                    <button onclick="remplirModifierNote('<?= $row['Matricule'] ?>','<?= $row['ID_Module'] ?>','<?= $row['note'] ?>','<?= addslashes($row['Type_evaluation'] ?? '') ?>')" class="btn-action">✏️</button>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div id="nt-ajouter" style="display:none;">
            <div class="form-wrap">
              <form method="POST">
                <input type="hidden" name="action" value="ajouter_note">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Étudiant</label>
                    <select class="form-select" name="matricule">
                      <?php foreach($liste_etudiants as $e): ?>
                      <option value="<?= $e['Matricule'] ?>"><?= $e['NOM'] ?> <?= $e['PRENOM'] ?> (<?= $e['Matricule'] ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group"><label class="form-label">Module</label>
                    <select class="form-select" name="module_id">
                      <?php foreach($liste_modules as $m): ?>
                      <option value="<?= $m['ID-MODULE'] ?>"><?= $m['NOM_MODULE'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Note /20</label><input class="form-input" type="number" name="note" min="0" max="20" step="0.25" placeholder="ex: 14.50" required></div>
                  <div class="form-group"><label class="form-label">Type d'évaluation</label>
                    <select class="form-select" name="type_evaluation">
                      <option>Examen final</option>
                      <option>Contrôle continu</option>
                      <option>TP</option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Enregistrer</button>
              </form>
            </div>
          </div>

          <div id="nt-modifier" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur ✏️ dans la liste pour modifier une note.</p>
              <form method="POST">
                <input type="hidden" name="action" value="modifier_note">
                <input type="hidden" name="id" id="mod_nt_id">
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Nouvelle note /20</label><input class="form-input" type="number" name="note" id="mod_nt_note" min="0" max="20" step="0.25" required></div>
                  <div class="form-group"><label class="form-label">Type d'évaluation</label>
                    <select class="form-select" name="type_evaluation" id="mod_nt_type">
                      <option>Examen final</option>
                      <option>Contrôle continu</option>
                      <option>TP</option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="form-submit">✔ Sauvegarder</button>
              </form>
            </div>
          </div>

          <div id="nt-supprimer" style="display:none;">
            <div class="form-wrap">
              <p style="font-size:12px;color:#888;margin-bottom:14px;">Cliquez sur 🗑️ dans la liste pour supprimer une note.</p>
              <div id="confirm-suppr-nt" style="display:none;background:#FFF5F5;border:1px solid #FFCDD2;border-radius:8px;padding:16px;">
                <p style="font-size:13px;font-weight:bold;color:#B71C1C;margin-bottom:10px;">⚠️ Confirmer la suppression</p>
                <form method="POST">
                  <input type="hidden" name="action" value="supprimer_note">
                  <input type="hidden" name="id" id="id-suppr-nt">
                  <button type="submit" style="padding:8px 16px;background:#E53935;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;">🗑️ Confirmer</button>
                  <button type="button" onclick="document.getElementById('confirm-suppr-nt').style.display='none'" style="padding:8px 16px;background:#fff;border:1px solid #ddd;border-radius:8px;cursor:pointer;margin-left:8px;">Annuler</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<script src="../fichier.js/admi.js"></script>
</body>
</html>