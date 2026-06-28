<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "miniproject");
$matricule = $_SESSION['user']['Matricule'];

$res_et = mysqli_query($conn, "SELECT * FROM etudiant WHERE Matricule='$matricule'");
$etudiant = mysqli_fetch_assoc($res_et);

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
$moy_statut = $moyenne_generale >= 10 ? 'Admis' : 'Ajourné';
$nom_complet = ($etudiant['PRENOM'] ?? '') . ' ' . ($etudiant['NOM'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Relevé de notes</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #222; }
    .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #1a3a6b; padding-bottom: 15px; margin-bottom: 20px; }
    .header img { width: 80px; }
    .header-title { text-align: center; }
    .header-title h2 { color: #1a3a6b; margin: 0; font-size: 18px; }
    .header-title p { margin: 4px 0; font-size: 12px; color: #555; }
    .info-box { background: #f5f7fa; border: 1px solid #ddd; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; }
    .info-box p { margin: 3px 0; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #1a3a6b; color: white; padding: 8px 10px; text-align: left; }
    td { padding: 7px 10px; border-bottom: 1px solid #eee; }
    tr:nth-child(even) { background: #f9f9f9; }
    .ok { color: #388E3C; font-weight: bold; }
    .ko { color: #D32F2F; font-weight: bold; }
    .moy-box { border: 2px solid #1a3a6b; border-radius: 8px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
    .moy-val { font-size: 24px; font-weight: bold; color: #1a3a6b; }
    .btn-print { display: block; margin: 20px auto; padding: 10px 30px; background: #1a3a6b; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; }
    @media print { .btn-print { display: none; } }
  </style>
</head>
<body>

<div class="header">
  <img src="../image/usthb1.webp" alt="Logo USTHB">
  <div class="header-title">
    <h2>UNIVERSITÉ DES SCIENCES ET DE LA TECHNOLOGIE</h2>
    <p>Houari Boumediene — USTHB</p>
    <p>Relevé de notes — Année universitaire 2025/2026</p>
  </div>
  <div style="width:80px;"></div>
</div>

<div class="info-box">
  <p><strong>Nom complet :</strong> <?= htmlspecialchars($nom_complet) ?></p>
  <p><strong>Matricule :</strong> <?= htmlspecialchars($etudiant['Matricule'] ?? '') ?></p>
  <p><strong>Niveau :</strong> <?= htmlspecialchars($etudiant['niveau'] ?? '') ?></p>
</div>

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
      <td class="<?= $n['note'] >= 10 ? 'ok' : 'ko' ?>">
        <?= $n['note'] >= 10 ? 'Validé' : 'Non validé' ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="moy-box">
  <div>
    <div style="color:#555;font-size:13px;">Moyenne générale pondérée</div>
    <div class="moy-val"><?= $moyenne_generale ?> / 20</div>
  </div>
  <div class="<?= $moyenne_generale >= 10 ? 'ok' : 'ko' ?>" style="font-size:18px;">
    <?= $moy_statut ?>
  </div>
</div>

<button class="btn-print" onclick="window.print()">🖨️ Imprimer / Télécharger en PDF</button>

</body>
</html>