<?php
  include __DIR__.'/components/session.php';

  if (!isset($_SESSION['succession_plans'])) {
      $_SESSION['succession_plans'] = [];
  }

  $errors = [];
  $input = [
      'role_id' => '',
      'role_name' => '',
      'candidate_name' => '',
      'skills' => '',
      'status' => ''
  ];

  $editMode = false;
  $editOriginalRoleId = null;

  function findSuccessionPlanIndexByRoleId($plans, $roleId) {
      foreach ($plans as $index => $plan) {
          if ($plan['role_id'] === $roleId) return $index;
      }
      return false;
  }

  // Handle Delete action
  if (isset($_GET['delete'])) {
      $delRoleId = $_GET['delete'];
      $idx = findSuccessionPlanIndexByRoleId($_SESSION['succession_plans'], $delRoleId);
      if ($idx !== false) {
          array_splice($_SESSION['succession_plans'], $idx, 1);
      }
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
  }

  // Handle Edit action
  if (isset($_GET['edit'])) {
      $editRoleId = $_GET['edit'];
      $idx = findSuccessionPlanIndexByRoleId($_SESSION['succession_plans'], $editRoleId);
      if ($idx !== false) {
          $editMode = true;
          $editOriginalRoleId = $_SESSION['succession_plans'][$idx]['role_id'];
          $input = $_SESSION['succession_plans'][$idx];
      }
  }

  // Handle form submission (Create or Update)
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $editMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] === '1';
      $editOriginalRoleId = $_POST['edit_original_role_id'] ?? null;

      $input['role_id'] = trim($_POST['role_id'] ?? '');
      $input['role_name'] = trim($_POST['role_name'] ?? '');
      $input['candidate_name'] = trim($_POST['candidate_name'] ?? '');
      $input['skills'] = trim($_POST['skills'] ?? '');
      $input['status'] = trim($_POST['status'] ?? '');

      // Validation
      if ($input['role_id'] === '') {
          $errors['role_id'] = 'Role ID is required.';
      } elseif (strlen($input['role_id']) > 20) {
          $errors['role_id'] = 'Role ID must be at most 20 characters.';
      } else {
          foreach ($_SESSION['succession_plans'] as $plan) {
              if ($plan['role_id'] === $input['role_id']) {
                  if (!$editMode || ($editMode && $input['role_id'] !== $editOriginalRoleId)) {
                      $errors['role_id'] = 'Role ID must be unique.';
                      break;
                  }
              }
          }
      }

      if ($input['role_name'] === '') {
          $errors['role_name'] = 'Role Name is required.';
      } elseif (strlen($input['role_name']) > 60) {
          $errors['role_name'] = 'Role Name must be at most 60 characters.';
      }

      if ($input['candidate_name'] === '') {
          $errors['candidate_name'] = 'Candidate Name is required.';
      } elseif (strlen($input['candidate_name']) > 60) {
          $errors['candidate_name'] = 'Candidate Name must be at most 60 characters.';
      }

      if (strlen($input['skills']) > 300) {
          $errors['skills'] = 'Skills description must be at most 300 characters.';
      }

      $allowedStatuses = ['Ready', 'Developing', 'Not Ready'];
      if ($input['status'] === '') {
          $errors['status'] = 'Status is required.';
      } elseif (!in_array($input['status'], $allowedStatuses, true)) {
          $errors['status'] = 'Invalid status selected.';
      }

      if (empty($errors)) {
          $cleanPlan = [
              'role_id' => htmlspecialchars($input['role_id'], ENT_QUOTES, 'UTF-8'),
              'role_name' => htmlspecialchars($input['role_name'], ENT_QUOTES, 'UTF-8'),
              'candidate_name' => htmlspecialchars($input['candidate_name'], ENT_QUOTES, 'UTF-8'),
              'skills' => htmlspecialchars($input['skills'], ENT_QUOTES, 'UTF-8'),
              'status' => $input['status']
          ];

          if ($editMode) {
              $idx = findSuccessionPlanIndexByRoleId($_SESSION['succession_plans'], $editOriginalRoleId);
              if ($idx !== false) {
                  $_SESSION['succession_plans'][$idx] = $cleanPlan;
              }
          } else {
              $_SESSION['succession_plans'][] = $cleanPlan;
          }

          header('Location: ' . $_SERVER['PHP_SELF']);
          exit;
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Succession Management System</title>
<link rel="stylesheet" href="css/app.css"/>
    <link rel="stylesheet" href="css/sidebar.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
  h1 {
    font-weight: 700;
    margin-bottom: 0.4em;
  }
  #succession-form{
    background: rgba(255, 255, 255, 0.12);
    padding: 18px;
    border-radius: 14px;
    width: 100%;
    box-shadow: 0 8px 14px rgba(0,0,0,0.3);
  }
  label {
    display: block;
    margin-top: 14px;
    font-weight: 600;
  }
  input, textarea, select {
    width: 100%;
    margin-top: 6px;
    padding: 10px 12px;
    border-radius: 8px;
    border: none;
    font-size: 15px;
    font-family: inherit;
    resize: vertical;
  }
  textarea {
    min-height: 80px;
  }
  .error {
    color: #ff6b6b;
    font-size: 13px;
    margin-top: 4px;
  }
  .btn-link {
    margin-top: 20px;
    width: 100%;
    background-color: #1db954;
    background-image: linear-gradient(315deg, #1db954 0%, #1ed760 74%);
    border: none;
    border-radius: 10px;
    padding: 14px 0;
    font-size: 19px;
    font-weight: 700;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 12px rgb(29 185 84 / 0.6);
    text-align: center;
    text-decoration: none;
    display: inline-block;
  }
  button:hover, button:focus,
  .btn-link:hover, .btn-link:focus {
    background-color: #1ed760;
    outline: none;
  }
  #plans-list {
    margin-top: 28px;
    width: 100%;
    max-height: 320px;
    overflow-y: auto;
    list-style: none;
    padding-left: 14px;
  }
  #plans-list li {
    background: rgba(255, 255, 255, 0.15);
    margin-bottom: 12px;
    padding: 16px 18px;
    border-radius: 14px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.21);
    position: relative;
  }
  #plans-list li strong {
    display: block;
    font-size: 17px;
    margin-bottom: 6px;
  }
  #plans-list li small {
    font-size: 13px;
    color: #d0d0d0cc;
  }
  .actions {
    position: absolute;
    top: 16px;
    right: 18px;
  }
  .actions a, .actions form {
    display: inline-block;
    margin-left: 10px;
  }
  .actions form {
    margin: 0;
  }
  .btn-small {
    background: transparent;
    box-shadow: none;
    color: #d0d0d0cc;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    border-radius: 6px;
    padding: 4px 8px;
    transition: background-color 0.2s ease;
    text-decoration: none;
  }
  .btn-small:hover, .btn-small:focus {
    background-color: rgba(29, 215, 105, 0.3);
    color: #ffffff;
    outline: none;
  }
  @media (max-width: 360px) {
    body {
      max-width: 90vw;
    }
    #plans-list {
      max-height: 260px;
    }
  }
</style>
</head>
<body>
  <body class="bg-light">
  <?php 
        include __DIR__.'/components/sidebar.php'
  ?>
  <div id="main" class="ps-0 rounded-end visually-hidden">
    <?php 
        include __DIR__.'/components/header.php'
    ?>
    <div class="container">

      <h1>Succession Management</h1>
      <form id="succession-form" method="post" action="" autocomplete="off" novalidate>
        <label for="role-id">Role ID</label>
        <input type="text" id="role-id" name="role_id" maxlength="20" required placeholder="Unique Role ID" value="<?= htmlspecialchars($input['role_id']); ?>" />
        <?php if (isset($errors['role_id'])): ?><div class="error"><?= $errors['role_id'] ?></div><?php endif; ?>
      
        <label for="role-name">Role Name</label>
        <input type="text" id="role-name" name="role_name" maxlength="60" required placeholder="Role Name" value="<?= htmlspecialchars($input['role_name']); ?>" />
        <?php if (isset($errors['role_name'])): ?><div class="error"><?= $errors['role_name'] ?></div><?php endif; ?>
      
        <label for="candidate-name">Candidate Name</label>
        <input type="text" id="candidate-name" name="candidate_name" maxlength="60" required placeholder="Candidate Name" value="<?= htmlspecialchars($input['candidate_name']); ?>" />
        <?php if (isset($errors['candidate_name'])): ?><div class="error"><?= $errors['candidate_name'] ?></div><?php endif; ?>
      
        <label for="skills">Skills / Competencies</label>
        <textarea id="skills" name="skills" maxlength="300" placeholder="Relevant skills, competencies, notes..."><?= htmlspecialchars($input['skills']); ?></textarea>
        <?php if (isset($errors['skills'])): ?><div class="error"><?= $errors['skills'] ?></div><?php endif; ?>
      
        <label for="status">Succession Status</label>
        <select id="status" name="status" required>
          <option value="" disabled <?= $input['status'] === '' ? 'selected' : '' ?>>Select status</option>
          <option value="Ready" <?= $input['status'] === 'Ready' ? 'selected' : '' ?>>Ready</option>
          <option value="Developing" <?= $input['status'] === 'Developing' ? 'selected' : '' ?>>Developing</option>
          <option value="Not Ready" <?= $input['status'] === 'Not Ready' ? 'selected' : '' ?>>Not Ready</option>
        </select>
        <?php if (isset($errors['status'])): ?><div class="error"><?= $errors['status'] ?></div><?php endif; ?>
      
        <!-- Hidden inputs for edit mode -->
        <input type="hidden" name="edit_mode" value="<?= $editMode ? '1' : '0'; ?>" />
        <input type="hidden" name="edit_original_role_id" value="<?= htmlspecialchars($editOriginalRoleId); ?>" />
      
        <button type="submit" class="btn-link"><?= $editMode ? 'Update Plan' : 'Add Plan'; ?></button>
        <?php if ($editMode): ?>
          <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn-link" style="margin-top: 10px; display: block; font-size: 14px; font-weight: 600; background: rgba(255 255 255 / 0.15); box-shadow: none; text-align:center;">Cancel Edit</a>
        <?php endif; ?>
      </form>
      
      <ul id="plans-list" aria-live="polite" aria-label="List of succession plans">
        <?php if (empty($_SESSION['succession_plans'])): ?>
          <li style="text-align: center; color: rgba(255,255,255,0.7);">No succession plans added yet.</li>
        <?php else: ?>
          <?php foreach ($_SESSION['succession_plans'] as $plan): ?>
            <li>
              <strong><?= $plan['role_name'] ?> (ID: <?= $plan['role_id'] ?>)</strong>
              <div style="margin-bottom: 8px;"><?= $plan['skills'] ?: 'No skills or notes provided.' ?></div>
              <small>
                Candidate: <?= $plan['candidate_name'] ?><br />
                Status: <?= $plan['status'] ?>
              </small>
              <div class="actions">
                <a href="?edit=<?= urlencode($plan['role_id']) ?>" class="btn-small" aria-label="Edit succession plan for <?= htmlspecialchars($plan['role_name']) ?>">Edit</a>
                <form method="get" onsubmit="return confirm('Are you sure you want to delete this succession plan?');" style="display: inline;">
                  <input type="hidden" name="delete" value="<?= htmlspecialchars($plan['role_id']) ?>" />
                  <button type="submit" class="btn-small" aria-label="Delete succession plan for <?= htmlspecialchars($plan['role_name']) ?>" style="background:none; border:none; padding:0; cursor:pointer; color:#d0d0d0cc;">Delete</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <script src="js/sidebar.js"></script>
</body>
</html>
