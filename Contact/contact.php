<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.ico" type="image/x-icon">
    <title>Contact Us - Agriculture Information Service</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/contact.css">
</head>
<body>
    <header>
        <?php include '../header.php'; ?>
    </header>

    <main>
        <section class="contact">
            <h1>Contact Us</h1>
            <p>If you have any questions or need assistance, please feel free to reach out to us. We are here to help!</p>

            <div class="contact-details">
                <div class="detail">
                    <h3>Phone</h3>
                    <p>+880 1732077391</p>
                </div>
                <div class="detail">
                    <h3>Email</h3>
                     
                    <p><a href="223002088@student.green.ac.bd">223002088@student.green.ac.bd</a></p>
                </div>
                <address class="detail">
                    <h3>Address</h3>
                    <p>Kanchan, Rupganj, Narayanganj-1461, Dhaka, Bangladesh</p>
                </address>
            </div>

            <h2>You Can Direct Message Us</h2>

            <form class="contact-form" action="submit_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>
                </div>

                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Write your message" required></textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </section>
    </main>

       <footer>
        <div class="footer-content">
            <div class="footer-about">
                <h3>Agriculture Information Service</h3>
                <p>
                    Providing useful agricultural information, farming tips,
                    and resources for farmers and agriculture enthusiasts.
                </p>
            </div>

            <div class="footer-links">
                <h3>Quick Links</h3>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
            </div>

            <div class="footer-social">
                <h3>Follow Us</h3>

                <a href="https://www.facebook.com/share/194z9YXrfs/" target="_blank">
                    <i class="fab fa-facebook"></i> Facebook
                </a>

                <a href="https://github.com/MdShajalalsojib" target="_blank">
                    <i class="fab fa-github"></i> GitHub
                </a>

                <a href="mdshahjalalsojib@gmail.com">
                    <i class="fas fa-envelope"></i> Email
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>
                &copy; 2026 Agriculture Information Service. All rights reserved.
            </p>
            <p>
                Developed with ❤️ by <strong>Md. Shajalal</strong>
            </p>
        </div>
    </footer>
</body>
</html>
