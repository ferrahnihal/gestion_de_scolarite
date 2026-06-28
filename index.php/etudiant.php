<?php
session_start();

// Connexion BDD
$conn = mysqli_connect("localhost", "root", "", "miniproject");
if (!$conn) { die("Erreur de connexion : " . mysqli_connect_error()); }

// Récupérer les infos de l'étudiant connecté
// $matricule = $_SESSION['user']['Matricule']; // à décommenter quand login est fait
$matricule = $_SESSION['user']['Matricule']; // pour test, remplace par la session

$res_et = mysqli_query($conn, "SELECT * FROM etudiant WHERE Matricule='$matricule'");
$etudiant = mysqli_fetch_assoc($res_et);

// Récupérer les notes de l'étudiant
$res_notes = mysqli_query($conn, "
    SELECT n.*, m.NOM_MODULE, m.CONFESSION as coefficient
    FROM note n
    JOIN module m ON n.ID_Module = m.`ID-MODULE`
    WHERE n.Matricule = '$matricule'
");
$notes = [];
$total_points = 0;
$total_coef   = 0;
while ($row = mysqli_fetch_assoc($res_notes)) {
    $notes[] = $row;
    $total_points += $row['note'] * $row['coefficient'];
    $total_coef   += $row['coefficient'];
}
$moyenne_generale = $total_coef > 0 ? round($total_points / $total_coef, 2) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>USTHB — Espace Étudiant</title>
  <link rel="stylesheet" href="../fichier.css/etudiant.css">
</head>
<body>

<?php
$nom_complet = ($etudiant['PRENOM'] ?? '') . ' ' . ($etudiant['NOM'] ?? '');
$initiales   = strtoupper(substr($etudiant['PRENOM'] ?? 'E', 0, 1) . substr($etudiant['NOM'] ?? 'T', 0, 1));
$moy_class   = $moyenne_generale >= 10 ? 'ok' : 'ko';
$moy_statut  = $moyenne_generale >= 10 ? 'Admis' : 'Ajourné';
?>

<div class="wrap">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-head">
      <div class="sb-logo"><img src="../image/usthb1.webp" style="width:90px" height="90" alt="Logo"></div>
      <div class="sb-sub">Espace Étudiant — 2025/2026</div>
    </div>
    <nav class="sb-nav">
      <div class="sb-section">Principal</div>
      <div class="sb-item active" onclick="goPage('accueil',this)" id="menu-accueil">
        <div class="sb-item-left"><span class="sb-icon">🏠</span> Tableau de bord</div>
      </div>

      <div class="sb-section">Mon espace</div>

      <div class="sb-item" onclick="toggleMenu('info',this)" id="menu-info">
        <div class="sb-item-left"><span class="sb-icon">👤</span> Mes informations</div>
        <span class="arrow" id="arrow-info">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-info">
        <div class="sb-sub-item" onclick="goSub('informations','voir','info',this)">
          <span class="sb-sub-dot"></span> Voir mes informations
        </div>
      </div>

      <div class="sb-item" onclick="toggleMenu('nt',this)" id="menu-nt">
        <div class="sb-item-left"><span class="sb-icon">📝</span> Mes notes</div>
        <span class="arrow" id="arrow-nt">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-nt">
        <div class="sb-sub-item" onclick="goSub('notes','voir','nt',this)">
          <span class="sb-sub-dot"></span> Voir mes notes
        </div>
        <div class="sb-sub-item" onclick="goSub('notes','moyenne','nt',this)">
          <span class="sb-sub-dot"></span> Ma moyenne générale
        </div>
      </div>

      <div class="sb-item" onclick="toggleMenu('rl',this)" id="menu-rl">
        <div class="sb-item-left"><span class="sb-icon">📄</span> Relevé de notes</div>
        <span class="arrow" id="arrow-rl">›</span>
      </div>
      <div class="sb-sub-menu" id="submenu-rl">
        <div class="sb-sub-item" onclick="goSub('releve','telecharger','rl',this)">
          <span class="sb-sub-dot"></span> Télécharger mon relevé
        </div>
      </div>

    </nav>
    <div class="sb-foot">
        <button onclick="window.location.href='../index.php/acceuil1.php'">Déconnexion</button>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">
    <div class="topbar">
      <div class="tb-title" id="page-title">Tableau de bord</div>
      <div class="tb-right">
        <div>
          <div class="uname"><?= htmlspecialchars($nom_complet) ?></div>
          <div class="urole">Étudiant</div>
        </div>
        <div class="av"><?= $initiales ?></div>
      </div>
    </div>

    <div class="content">

      <!-- ACCUEIL -->
      <div class="page active" id="page-accueil">
        <div class="welcome-card">
          <div>
            <div class="wt">Bonjour, <?= htmlspecialchars($etudiant['PRENOM'] ?? '') ?> 👋</div>
            <div class="ws">Bienvenue sur votre espace étudiant — USTHB</div>
          </div>
          <div class="welcome-badge">2025 / 2026 — S2</div>
        </div>
        <div class="kpi-row">
          <div class="kpi">
            <div class="kpi-lbl">Matricule</div>
            <div class="kpi-val" style="font-size:18px;"><?= htmlspecialchars($etudiant['Matricule'] ?? '') ?></div>
            <div class="kpi-sub">Identifiant étudiant</div>
          </div>
          <div class="kpi g">
            <div class="kpi-lbl">Modules</div>
            <div class="kpi-val"><?= count($notes) ?></div>
            <div class="kpi-sub">Notes enregistrées</div>
          </div>
          <div class="kpi <?= $moy_class ?>">
            <div class="kpi-lbl">Moyenne générale</div>
            <div class="kpi-val"><?= $moyenne_generale ?></div>
            <div class="kpi-sub"><?= $moy_statut ?></div>
          </div>
        </div>
      </div>

      <!-- INFORMATIONS -->
      <div class="page" id="page-informations">
        <div class="card">
          <div class="card-head"><div class="card-title" id="info-title">👤 Mes informations</div></div>
          <div id="info-voir">
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Matricule</div>
                <div class="info-value"><?= htmlspecialchars($etudiant['Matricule'] ?? '—') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Nom</div>
                <div class="info-value"><?= htmlspecialchars($etudiant['NOM'] ?? '—') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Prénom</div>
                <div class="info-value"><?= htmlspecialchars($etudiant['PRENOM'] ?? '—') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Email</div>
                
<div class="info-value"><?= htmlspecialchars($etudiant['EMAIL'] ?? '—') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Niveau</div>
                <div class="info-value"><?= htmlspecialchars($etudiant['niveau'] ?? '—') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                  <span class="pill <?= $moy_class ?>"><?= $moy_statut ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- NOTES -->
      <div class="page" id="page-notes">
        <div class="card">
          <div class="card-head"><div class="card-title" id="nt-title">📝 Mes notes</div></div>

          <!-- VOIR NOTES -->
          <div id="nt-voir">
            <?php if (count($notes) > 0): ?>
            <table>
              <thead>
                <tr>
                  <th>Module</th>
                  <th>Note /20</th>
                  <th>Coefficient</th>
                  <th>Type</th>
                  <th>Résultat</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($notes as $n): ?>
                <tr>
                  <td><?= htmlspecialchars($n['NOM_MODULE']) ?></td>
                  <td><strong><?= $n['note'] ?></strong></td>
                  <td><?= $n['coefficient'] ?></td>
                  <td><?= htmlspecialchars($n['Type_evaluation']) ?></td>
                  <td>
                    <?php if ($n['note'] >= 10): ?>
                      <span class="pill ok">Validé</span>
                    <?php else: ?>
                      <span class="pill ko">Non validé</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php else: ?>
            <div style="padding:30px;text-align:center;color:#aaa;font-size:13px;">Aucune note enregistrée pour le moment.</div>
            <?php endif; ?>
          </div>

          <!-- MOYENNE -->
          <div id="nt-moyenne" style="display:none;">
            <div class="moy-box <?= $moy_class ?>">
              <div>
                <div class="moy-title">Moyenne générale pondérée</div>
                <div class="moy-val <?= $moy_class ?>"><?= $moyenne_generale ?> / 20</div>
              </div>
              <div class="moy-statut <?= $moy_class ?>"><?= $moy_statut ?></div>
            </div>
            <?php if (count($notes) > 0): ?>
            <table>
              <thead>
                <tr><th>Module</th><th>Note</th><th>Coefficient</th><th>Points</th></tr>
              </thead>
              <tbody>
                <?php foreach ($notes as $n): ?>
                <tr>
                  <td><?= htmlspecialchars($n['NOM_MODULE']) ?></td>
                  <td><?= $n['note'] ?></td>
                  <td><?= $n['coefficient'] ?></td>
                  <td><?= round($n['note'] * $n['coefficient'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold;background:#FAFAFA;">
                  <td colspan="2">Total</td>
                  <td><?= $total_coef ?></td>
                  <td><?= round($total_points, 2) ?></td>
                </tr>
              </tbody>
            </table>
            <?php endif; ?>
          </div>

        </div>
      </div>

      <!-- RELEVE -->
      <div class="page" id="page-releve">
        <div class="card">
          <div class="card-head"><div class="card-title" id="rl-title">📄 Relevé de notes</div></div>
          <div id="rl-telecharger">
            <div class="releve-wrap">
              <div class="releve-header">
                <div class="releve-title">Relevé de notes — <?= htmlspecialchars($nom_complet) ?></div>
                <div class="releve-sub">Matricule : <?= htmlspecialchars($etudiant['Matricule'] ?? '') ?> | Niveau : <?= htmlspecialchars($etudiant['niveau'] ?? '') ?></div>
              </div>

              <table>
                <thead>
                  <tr><th>Module</th><th>Note /20</th><th>Coefficient</th><th>Type</th><th>Résultat</th></tr>
                </thead>
                <tbody>
                  <?php if (count($notes) > 0): ?>
                    <?php foreach ($notes as $n): ?>
                    <tr>
                      <td><?= htmlspecialchars($n['NOM_MODULE']) ?></td>
                      <td><?= $n['note'] ?></td>
                      <td><?= $n['coefficient'] ?></td>
                      <td><?= htmlspecialchars($n['Type_evaluation']) ?></td>
                      <td>
                        <?php if ($n['note'] >= 10): ?>
                          <span class="pill ok">Validé</span>
                        <?php else: ?>
                          <span class="pill ko">Non validé</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;color:#aaa;padding:20px;">Aucune note disponible</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <div class="moy-box <?= $moy_class ?>" style="margin:16px 0 0 0;">
                <div>
                  <div class="moy-title">Moyenne générale</div>
                  <div class="moy-val <?= $moy_class ?>"><?= $moyenne_generale ?> / 20</div>
                </div>
                <div class="moy-statut <?= $moy_class ?>"><?= $moy_statut ?></div>
              </div>

              <button class="btn-pdf" onclick="window.print()">
                📥 Télécharger / Imprimer le relevé
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<script src="../fichier.js/etudiant.js"></script>
</body>
</html>