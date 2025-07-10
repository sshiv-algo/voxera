<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voxera - Speak Up</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav>
  <div class="logo">Voxera</div>
  <div class="links">
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
  </div>
</nav>

<!-- Home Section -->
<section id="home">
  <div class="home-content">
    <div class="home-text">
      <p>Speak Up. Be Heard. Get Action with</p>
      <h1>VOXERA.</h1>
      <div class="home-buttons">
        <a href="login.php">Student Login</a>
        <a href="register.php">Register</a>
        <a href="faculty_login.php">Faculty Login</a>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section id="about">
  <h2>About Voxera</h2>
  <p>Voxera is a secure, student-centric complaint management platfrorm buit to empower voices, ensure accountability,and drive action. From anonymous reporting to private handling of sensitive issues, Voxera bridges the gap between studets and campus authorities with clarity, speed, and trust.</p>

  <div class="features-grid">
    <div class="feature-card">
      <img src="images/anonymity.png" alt="Anonymous Icon">
      <h3>Submit Anonymous Complaints</h3>
    </div>
    <div class="feature-card">
      <img src="images/search.png" alt="Track Status Icon">
      <h3>Track Status of Complaints</h3>
    </div>
    <div class="feature-card">
      <img src="images/admin.png" alt="Panel Icon">
      <h3>Admin & Faculty Panels</h3>
    </div>
    <div class="feature-card">
      <img src="images/attach.png" alt="Attach Photo Icon">
      <h3>Attach Evidence Photos</h3>
    </div>
    <div class="feature-card">
      <img src="images/ui.png" alt="Responsive UI Icon">
      <h3>Simple, User-Friendly, Responsive UI</h3>
    </div>
    <div class="feature-card">
      <img src="images/creativity.png" alt="Responsive UI Icon">
      <h3>Faster Resolutions, Greater Impact</h3>
    </div>
    <div class="feature-card">
      <img src="images/privacy.png" alt="Responsive UI Icon">
      <h3>Private Handling of Sensitive Complaints</h3>
    </div>
    <div class="feature-card">
      <img src="images/faculty.png" alt="Responsive UI Icon">
      <h3>Faculty Complaint Handling</h3>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact">
  <div class="contact-form-wrapper">
    <h2 class="form-heading">Say Hello</h2>
    <form class="contact-form" action="https://script.google.com/macros/s/AKfycbxi_hYx0fJsCSrOFwfodqTUFh_AgfBl_J1nSOWm4ju2zMns3ZEhUAHKOEecGDN1qSj0qw/exec" method="POST" id="contactForm">
      <div class="form-row">
        <input type="text" name="first_name" placeholder="First name" required>
        <input type="text" name="full_name" placeholder="Full name" required>
      </div>
      <input type="email" name="email" placeholder="Email address" required>
      <input type="text" name="subject" placeholder="Subject of the message" required>
      <textarea name="message" rows="5" placeholder="Type your message here.." required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
</section>

<!-- Footer -->
<footer>
  &copy; 2025 Voxera. All rights reserved.
</footer>

</body>
</html>
