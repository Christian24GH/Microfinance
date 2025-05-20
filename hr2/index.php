<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard with Sidebar</title>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    background: #f4f7fc;
    color: #333;
    display: flex;
    height: 100vh;
    overflow: hidden;
  }

  /* Sidebar */

  .sidebar {
    width: 250px;
    background-color:darkblue;
    color: #fff;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
    box-shadow: 4px 0 8px rgba(0,0,0,0.1);
  }

  .sidebar h2 {
    text-align: center;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #444c56;
    padding-bottom: 1rem;
    font-weight: 700;
  }

  .nav-links {
    list-style: none;
    padding: 0;
    margin: 0;
    flex-grow: 1;
  }

  .nav-links li {
    margin: 0;
  }

  .nav-links a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #c9d1d9;
    padding: 1rem 2rem;
    font-weight: 500;
    border-left: 4px solid transparent;
    transition: background-color 0.3s, border-left-color 0.3s, color 0.3s;
    cursor: pointer;
  }

  .nav-links a:hover,
  .nav-links a.active {
    background-color: #0366d6;
    color: #fff;
    border-left-color: #58a6ff;
  }

  .nav-links a svg {
    margin-right: 12px;
    fill: currentColor;
    width: 20px;
    height: 20px;
  }

  /* Main Content */

  .main-content {
    flex-grow: 1;
    padding: 2rem 3rem;
    overflow-y: auto;
  }

  .main-content h1 {
    font-size: 2.4rem;
    margin-bottom: 1rem;
  }

  .main-content p {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #555;
  }

  /* Responsive */

  @media (max-width: 768px) {
    body {
      flex-direction: column;
    }
    .sidebar {
      width: 100%;
      flex-direction: row;
      overflow-x: auto;
      padding: 0.5rem;
      box-shadow: none;
      border-bottom: 1px solid #ddd;
    }
    .sidebar h2 {
      display: none;
    }
    .nav-links {
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .nav-links li {
      margin: 0 0.25rem;
    }
    .nav-links a {
      padding: 0.75rem 1rem;
      border-left: none;
      border-bottom: 3px solid transparent;
    }
    .nav-links a:hover,
    .nav-links a.active {
      border-left: none;
      border-bottom-color: #58a6ff;
      background-color: transparent;
      color: #0366d6;
    }
    .main-content {
      padding: 1rem 1.5rem;
      height: calc(100vh - 50px);
    }
  }
</style>
</head>
<body>
  <nav class="sidebar">
    <h2>My Dashboard</h2>
    <ul class="nav-links">
      <li><a href="#" class="active" aria-current="page"><svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>Dashboard</a></li>
      <li><a href="training.php"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4S8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>Training</a></li>
      <li><a href="learning.php"><svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.06-.94l2.03-1.58a.5.5 0 00.12-.65l-1.92-3.32a.5.5 0 00-.6-.22l-2.39.96a7.025 7.025 0 00-1.63-.94l-.36-2.54A.5.5 0 0014.87 3h-3.74a.5.5 0 00-.49.42l-.36 2.54a7.034 7.034 0 00-1.63.94l-2.39-.96a.5.5 0 00-.6.22L3.65 9.88a.5.5 0 00.12.65l2.03 1.58c-.04.3-.06.61-.06.94s.02.64.06.94l-2.03 1.58a.5.5 0 00-.12.65l1.92 3.32c.14.24.45.34.71.22l2.39-.96c.5.38 1.05.68 1.63.94l.36 2.54c.05.27.27.46.49.46h3.74c.27 0 .49-.19.49-.46l.36-2.54a7.034 7.034 0 001.63-.94l2.38.96c.26.12.57.02.71-.22l1.92-3.32a.5.5 0 00-.12-.65l-2.02-1.58zM12 15.5a3.5 3.5 0 110-7 3.5 3.5 0 010 7z"/></svg>Learning</a></li>
      <li><a href="competency.php"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4S8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>Competency</a></li>
      <li><a href="succession.php"><svg viewBox="0 0 24 24"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>succession</a></li>
    </ul>
  </nav>
    </ul>
  </nav>
  <main class="main-content">
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Dashboard</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .btn-add { background-color: #28a745; }
        .btn-edit { background-color: #007bff; }
        .btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>
  
</html>