<?php
  include __DIR__.'/components/session.php';

  if (!isset($_SESSION['courses'])) {
      $_SESSION['courses'] = [];
  }

  // Handle cancelling edit by removing editing course ID from session
  if (isset($_GET['cancel_edit'])) {
      unset($_SESSION['editing_course_id']);
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
  }

  $errors = [];
  $input = [
      'id' => '',
      'title' => '',
      'description' => '',
      'startDate' => '',
      'instructor' => ''
  ];

  $editMode = false;
  $editOriginalId = null;

  function formatDate($dateStr) {
      $d = DateTime::createFromFormat('Y-m-d', $dateStr);
      if (!$d) return $dateStr;
      return $d->format('M j, Y');
  }

  function findCourseIndexById($courses, $id) {
      foreach ($courses as $index => $course) {
          if ($course['id'] === $id) return $index;
      }
      return false;
  }

  // Handle Delete action
  if (isset($_GET['delete'])) {
      $delId = $_GET['delete'];
      $idx = findCourseIndexById($_SESSION['courses'], $delId);
      if ($idx !== false) {
          array_splice($_SESSION['courses'], $idx, 1);
          // If currently editing this course, cancel edit as well
          if (isset($_SESSION['editing_course_id']) && $_SESSION['editing_course_id'] === $delId) {
              unset($_SESSION['editing_course_id']);
          }
      }
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
  }

  // Handle Edit action: load course data and set editing state in session
  if (isset($_GET['edit'])) {
      $editId = $_GET['edit'];
      $idx = findCourseIndexById($_SESSION['courses'], $editId);
      if ($idx !== false) {
          $_SESSION['editing_course_id'] = $editId;
          $editMode = true;
          $editOriginalId = $_SESSION['courses'][$idx]['id'];
          $input = $_SESSION['courses'][$idx];
      } else {
          // If invalid edit id, clear editing session
          unset($_SESSION['editing_course_id']);
      }
  } elseif (isset($_SESSION['editing_course_id'])) {
      // When loading with editing in session but no GET edit param
      $editId = $_SESSION['editing_course_id'];
      $idx = findCourseIndexById($_SESSION['courses'], $editId);
      if ($idx !== false) {
          $editMode = true;
          $editOriginalId = $_SESSION['courses'][$idx]['id'];
          $input = $_SESSION['courses'][$idx];
      } else {
          unset($_SESSION['editing_course_id']);
      }
  }

  // Handle form submission (create or update)
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Determine edit mode from session
      $editMode = isset($_SESSION['editing_course_id']);
      $editOriginalId = $editMode ? $_SESSION['editing_course_id'] : null;

      $input['id'] = isset($_POST['course_id']) ? trim($_POST['course_id']) : '';
      $input['title'] = isset($_POST['course_title']) ? trim($_POST['course_title']) : '';
      $input['description'] = isset($_POST['course_description']) ? trim($_POST['course_description']) : '';
      $input['startDate'] = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
      $input['instructor'] = isset($_POST['instructor']) ? trim($_POST['instructor']) : '';

      // Validation
      if ($input['id'] === '') {
          $errors['id'] = 'Course ID is required.';
      } elseif (strlen($input['id']) > 20) {
          $errors['id'] = 'Course ID must be at most 20 characters.';
      } else {
          foreach ($_SESSION['courses'] as $course) {
              if ($course['id'] === $input['id']) {
                  if (!$editMode || ($editMode && $input['id'] !== $editOriginalId)) {
                      $errors['id'] = 'Course ID must be unique.';
                      break;
                  }
              }
          }
      }

      if ($input['title'] === '') {
          $errors['title'] = 'Title is required.';
      } elseif (strlen($input['title']) > 60) {
          $errors['title'] = 'Title must be at most 60 characters.';
      }

      if (strlen($input['description']) > 200) {
          $errors['description'] = 'Description must be at most 200 characters.';
      }

      if ($input['startDate'] === '') {
          $errors['startDate'] = 'Start Date is required.';
      } elseif (!DateTime::createFromFormat('Y-m-d', $input['startDate'])) {
          $errors['startDate'] = 'Start Date must be a valid date.';
      }

      if ($input['instructor'] === '') {
          $errors['instructor'] = 'Instructor is required.';
      } elseif (strlen($input['instructor']) > 50) {
          $errors['instructor'] = 'Instructor must be at most 50 characters.';
      }

      if (empty($errors)) {
          $cleanCourse = [
              'id' => htmlspecialchars($input['id']),
              'title' => htmlspecialchars($input['title']),
              'description' => htmlspecialchars($input['description']),
              'startDate' => $input['startDate'],
              'instructor' => htmlspecialchars($input['instructor'])
          ];

          if ($editMode) {
              $idx = findCourseIndexById($_SESSION['courses'], $editOriginalId);
              if ($idx !== false) {
                  $_SESSION['courses'][$idx] = $cleanCourse;
              }
              unset($_SESSION['editing_course_id']); // Clear editing session after update
          } else {
              $_SESSION['courses'][] = $cleanCourse;
          }

          // Redirect to avoid resubmission and reset edit mode
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
    <title>Learning Management System (PHP CRUD) - No Hidden Edit Mode</title>
    <link rel="stylesheet" href="css/app.css"/>
    <link rel="stylesheet" href="css/sidebar.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
      /* Base and reset */
      * {
        box-sizing: border-box;
      }
      h1 {
        font-weight: 700;
        margin-bottom: 0.4em;
      }
      #learning-form {
        background: rgba(255, 255, 255, 0.1);
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
      input, textarea {
        width: 100%;
        margin-top: 6px;
        padding: 10px 12px;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        font-family: inherit;
        resize: vertical;
      }
      input[type="date"] {
        padding-left: 10px;
      }
      textarea {
        min-height: 70px;
      }
      .error {
        color: #ffb3b3;
        font-size: 13px;
        margin-top: 4px;
      }
      .btn-link {
        margin-top: 20px;
        width: 100%;
        background-color: #11998e;
        background-image: linear-gradient(315deg, #11998e 0%, #38ef7d 74%);
        border:none;
        border-radius: 10px;
        padding: 14px 0;
        font-size: 19px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        transition: background-color 0.2s ease;
        box-shadow: 0 4px 12px rgb(56 239 125 / 0.6);
        text-align: center;
        text-decoration: none;
        display: inline-block;
      }
      
      #course-list {
        margin-top: 28px;
        width: 100%;
        max-height: 320px;
        overflow-y: auto;
        list-style: none;
        padding-left: 14px;
      }
      #course-list li {
        background: rgba(255,255,255,0.17);
        margin-bottom: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        box-shadow: 0 3px 9px rgba(0,0,0,0.18);
        position: relative;
      }
      #course-list li strong {
        display: block;
        font-size: 17px;
        margin-bottom: 6px;
      }
      #course-list li small {
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
        background-color: rgba(56,239,125,0.25);
        color: #ffffff;
        outline: none;
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
      <h1>Learning Management</h1>
      <form id="learning-form" method="post" action="" autocomplete="off" novalidate>
        <label for="course-id">Course ID</label>
        <input 
          type="text" 
          id="course-id" 
          name="course_id" 
          placeholder="Unique Course ID" 
          maxlength="20" 
          required
          value="<?php echo htmlspecialchars($input['id']); ?>" 
        />
        <?php if (isset($errors['id'])): ?><div class="error"><?php echo $errors['id']; ?></div><?php endif; ?>
  
        <label for="course-title">Title</label>
        <input 
          type="text" 
          id="course-title" 
          name="course_title" 
          placeholder="Course Title" 
          maxlength="60" 
          required
          value="<?php echo htmlspecialchars($input['title']); ?>"
        />
        <?php if (isset($errors['title'])): ?><div class="error"><?php echo $errors['title']; ?></div><?php endif; ?>
  
        <label for="course-description">Description</label>
        <textarea 
          id="course-description" 
          name="course_description" 
          placeholder="Brief Course Description" 
          maxlength="200"
        ><?php echo htmlspecialchars($input['description']); ?></textarea>
        <?php if (isset($errors['description'])): ?><div class="error"><?php echo $errors['description']; ?></div><?php endif; ?>
  
        <label for="start-date">Start Date</label>
        <input 
          type="date" 
          id="start-date" 
          name="start_date" 
          required
          value="<?php echo htmlspecialchars($input['startDate']); ?>"
        />
        <?php if (isset($errors['startDate'])): ?><div class="error"><?php echo $errors['startDate']; ?></div><?php endif; ?>
  
        <label for="instructor">Instructor</label>
        <input 
          type="text" 
          id="instructor" 
          name="instructor" 
          placeholder="Instructor name" 
          maxlength="50" 
          required
          value="<?php echo htmlspecialchars($input['instructor']); ?>"
        />
        <?php if (isset($errors['instructor'])): ?><div class="error"><?php echo $errors['instructor']; ?></div><?php endif; ?>
  
        <button type="submit" class="btn-link" id="submit-btn"><?php echo $editMode ? 'Update Course' : 'Add Course'; ?></button>
        <?php if ($editMode): ?>
          <a href="?cancel_edit=1" class="btn-link" style="margin-top:10px; display:block; font-size:14px; font-weight:600; background: rgba(255 255 255 / 0.15); box-shadow:none;">Cancel Edit</a>
        <?php endif; ?>
      </form>
  
      <ul id="course-list" aria-live="polite" aria-label="List of courses">
          <?php if (empty($_SESSION['courses'])): ?>
            <li style="text-align:center;">No courses added yet.</li>
          <?php else: ?>
            <?php foreach ($_SESSION['courses'] as $course): ?>
              <li>
                <strong><?= $course['title'] ?> (ID: <?= $course['id'] ?>)</strong>
                <div style="margin-bottom:8px;"><?= $course['description'] ? $course['description'] : 'No description available' ?></div>
                <small>Start: <?= formatDate($course['startDate']) ?> | Instructor: <?= $course['instructor'] ?></small>
                <div class="actions">
                  <a href="?edit=<?= urlencode($course['id']) ?>" class="btn-small" aria-label="Edit course <?= htmlspecialchars($course['title']) ?>">Edit</a>
                  <form method="get" onsubmit="return confirm('Are you sure you want to delete this course?');" style="display:inline;">
                    <input type="hidden" name="delete" value="<?= htmlspecialchars($course['id']) ?>" />
                    <button type="submit" class="btn-small" aria-label="Delete course <?= htmlspecialchars($course['title']) ?>" style="background:none; border:none; padding:0; cursor:pointer; color:#e0e0e0dd;">Delete</button>
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

