<?php
  include __DIR__.'/components/session.php';

  if (!isset($_SESSION['competencies'])) {
      $_SESSION['competencies'] = [];
  }

  $errors = [];
  $input = [
      'id' => '',
      'name' => '',
      'description' => '',
      'category' => '',
      'level' => ''
  ];

  $editMode = false;
  $editOriginalId = null;

  // Helper to find a competency index by ID
  function findCompetencyIndexById($competencies, $id) {
      foreach ($competencies as $index => $comp) {
          if ($comp['id'] === $id) return $index;
      }
      return false;
  }

  // Handle Delete action
  if (isset($_GET['delete'])) {
      $delId = $_GET['delete'];
      $idx = findCompetencyIndexById($_SESSION['competencies'], $delId);
      if ($idx !== false) {
          array_splice($_SESSION['competencies'], $idx, 1);
      }
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
  }

  // Handle Edit action
  if (isset($_GET['edit'])) {
      $editId = $_GET['edit'];
      $idx = findCompetencyIndexById($_SESSION['competencies'], $editId);
      if ($idx !== false) {
          $editMode = true;
          $editOriginalId = $_SESSION['competencies'][$idx]['id'];
          $input = $_SESSION['competencies'][$idx];
      }
  }

  // Handle form submission (Create or Update)
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $editMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] === '1';
      $editOriginalId = isset($_POST['edit_original_id']) ? $_POST['edit_original_id'] : null;

      $input['id'] = isset($_POST['competency_id']) ? trim($_POST['competency_id']) : '';
      $input['name'] = isset($_POST['competency_name']) ? trim($_POST['competency_name']) : '';
      $input['description'] = isset($_POST['competency_description']) ? trim($_POST['competency_description']) : '';
      $input['category'] = isset($_POST['competency_category']) ? trim($_POST['competency_category']) : '';
      $input['level'] = isset($_POST['competency_level']) ? trim($_POST['competency_level']) : '';

      // Validation
      if ($input['id'] === '') {
          $errors['id'] = 'Competency ID is required.';
      } elseif (strlen($input['id']) > 20) {
          $errors['id'] = 'Competency ID must be at most 20 characters.';
      } else {
          foreach ($_SESSION['competencies'] as $comp) {
              if ($comp['id'] === $input['id']) {
                  if (!$editMode || ($editMode && $input['id'] !== $editOriginalId)) {
                      $errors['id'] = 'Competency ID must be unique.';
                      break;
                  }
              }
          }
      }

      if ($input['name'] === '') {
          $errors['name'] = 'Name/Title is required.';
      } elseif (strlen($input['name']) > 60) {
          $errors['name'] = 'Name must be at most 60 characters.';
      }

      if (strlen($input['description']) > 300) {
          $errors['description'] = 'Description must be at most 300 characters.';
      }

      if (strlen($input['category']) > 50) {
          $errors['category'] = 'Category must be at most 50 characters.';
      }

      $allowedLevels = ['Beginner', 'Intermediate', 'Advanced'];
      if ($input['level'] === '') {
          $errors['level'] = 'Proficiency Level is required.';
      } elseif (!in_array($input['level'], $allowedLevels)) {
          $errors['level'] = 'Proficiency Level is invalid.';
      }

      if (empty($errors)) {
          $cleanComp = [
              'id' => htmlspecialchars($input['id']),
              'name' => htmlspecialchars($input['name']),
              'description' => htmlspecialchars($input['description']),
              'category' => htmlspecialchars($input['category']),
              'level' => $input['level']
          ];

          if ($editMode) {
              $idx = findCompetencyIndexById($_SESSION['competencies'], $editOriginalId);
              if ($idx !== false) {
                  $_SESSION['competencies'][$idx] = $cleanComp;
              }
          } else {
              $_SESSION['competencies'][] = $cleanComp;
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
  <title>Competency Management System</title>
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
  #competency-form {
    background: rgba(255,255,255,0.12);
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
    color: #ffb3b3;
    font-size: 13px;
    margin-top: 4px;
  }
  .btn-link {
    margin-top: 20px;
    width: 100%;
    background-color: #8e2de2;
    background-image: linear-gradient(315deg, #8e2de2 0%, #4a00e0 74%);
    border: none;
    border-radius: 10px;
    padding: 14px 0;
    font-size: 19px;
    font-weight: 700;
    color: white;
    cursor: pointer;
    transition: background-color 0.2s ease;
    box-shadow: 0 4px 12px rgb(74 0 224 / 0.6);
    text-align: center;
    text-decoration: none;
    display: inline-block;
  }
  .btn-link:hover, .btn-link:focus {
    background-color: #4a00e0;
    outline: none;
  }
  #competency-list {
    margin-top: 28px;
    width: 100%;
    max-height: 320px;
    overflow-y: auto;
    list-style: none;
    padding-left: 14px;
  }
  #competency-list li {
    background: rgba(255,255,255,0.18);
    margin-bottom: 12px;
    padding: 14px 16px;
    border-radius: 14px;
    box-shadow: 0 3px 9px rgba(0,0,0,0.18);
    position: relative;
  }
  #competency-list li strong {
    display: block;
    font-size: 17px;
    margin-bottom: 6px;
  }
  #competency-list li small {
    font-size: 13px;
    color: #e0e0e0dd;
  }
  .actions {
    position: absolute;
    top: 14px;
    right: 16px;
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
    color: #e0e0e0dd;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    border-radius: 6px;
    padding: 4px 8px;
    transition: background-color 0.2s ease;
    text-decoration: none;
  }
  .btn-small:hover, .btn-small:focus {
    background-color: rgba(74,0,224,0.25);
    color: #ffffff;
    outline: none;
  }
  @media (max-width: 360px) {
    body {
      max-width: 90vw;
    }
    #competency-list {
      max-height: 260px;
    }
  }
</style>
</head>
<body class="bg-light">
  <?php 
        include __DIR__.'/components/sidebar.php'
  ?>
  <div id="main" class="ps-0 rounded-end visually-hidden">
    <?php 
        include __DIR__.'/components/header.php'
    ?>
    <div class="container">
      <h1>Competency Management</h1>
      <form id="competency-form" method="post" action="" autocomplete="off" novalidate>
        <label for="competency-id">Competency ID</label>
        <input type="text" id="competency-id" name="competency_id" maxlength="20" required placeholder="Unique Competency ID" value="<?php echo htmlspecialchars($input['id']); ?>" />
        <?php if (isset($errors['id'])): ?><div class="error"><?= $errors['id'] ?></div><?php endif; ?>
  
        <label for="competency-name">Name/Title</label>
        <input type="text" id="competency-name" name="competency_name" maxlength="60" required placeholder="Competency Name or Title" value="<?php echo htmlspecialchars($input['name']); ?>" />
        <?php if (isset($errors['name'])): ?><div class="error"><?= $errors['name'] ?></div><?php endif; ?>
  
        <label for="competency-description">Description</label>
        <textarea id="competency-description" name="competency_description" maxlength="300" placeholder="Brief description (optional)"><?php echo htmlspecialchars($input['description']); ?></textarea>
        <?php if (isset($errors['description'])): ?><div class="error"><?= $errors['description'] ?></div><?php endif; ?>
  
        <label for="competency-category">Category</label>
        <input type="text" id="competency-category" name="competency_category" maxlength="50" placeholder="Category or domain (optional)" value="<?php echo htmlspecialchars($input['category']); ?>" />
        <?php if (isset($errors['category'])): ?><div class="error"><?= $errors['category'] ?></div><?php endif; ?>
  
        <label for="competency-level">Proficiency Level</label>
        <select id="competency-level" name="competency_level" required>
          <option value="" disabled <?= $input['level'] === '' ? 'selected' : '' ?>>Select level</option>
          <option value="Beginner" <?= $input['level'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
          <option value="Intermediate" <?= $input['level'] === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
          <option value="Advanced" <?= $input['level'] === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
        </select>
        <?php if (isset($errors['level'])): ?><div class="error"><?= $errors['level'] ?></div><?php endif; ?>
  
        <input type="hidden" name="edit_mode" value="<?= $editMode ? '1' : '0'; ?>" />
        <input type="hidden" name="edit_original_id" value="<?= htmlspecialchars($editOriginalId); ?>" />
  
        <button type="submit" class="btn-link" id="submit-btn"><?= $editMode ? 'Update Competency' : 'Add Competency'; ?></button>
        <?php if ($editMode): ?>
          <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn-link" style="margin-top:10px; display:block; font-size:14px; font-weight:600; background: rgba(255 255 255 / 0.15); box-shadow:none;">Cancel Edit</a>
        <?php endif; ?>
      </form>
  
      <ul id="competency-list" aria-live="polite" aria-label="List of competencies">
      <?php if (empty($_SESSION['competencies'])): ?>
        <li style="text-align:center;">No competencies added yet.</li>
      <?php else: ?>
        <?php foreach ($_SESSION['competencies'] as $comp): ?>
          <li>
            <strong><?= $comp['name'] ?> (ID: <?= $comp['id'] ?>)</strong>
            <div style="margin-bottom:8px;"><?= $comp['description'] ?: 'No description available' ?></div>
            <small>
              Category: <?= $comp['category'] ?: 'None' ?> | 
              Level: <?= $comp['level'] ?>
            </small>
            <div class="actions">
              <a href="?edit=<?= urlencode($comp['id']) ?>" class="btn-small" aria-label="Edit competency <?= htmlspecialchars($comp['name']) ?>">Edit</a>
              <form method="get" onsubmit="return confirm('Are you sure you want to delete this competency?');" style="display:inline;">
                <input type="hidden" name="delete" value="<?= htmlspecialchars($comp['id']) ?>" />
                <button type="submit" class="btn-small" aria-label="Delete competency <?= htmlspecialchars($comp['name']) ?>" style="background:none; border:none; padding:0; cursor:pointer; color:#e0e0e0dd;">Delete</button>
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

