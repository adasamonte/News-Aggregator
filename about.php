<?php
// Start the session and include necessary files
session_start();
include("connect.php");

// Set page title and additional CSS files
$page_title = "About | News For You";
$additional_css = ["style/about.css", "style/universal.css"]; // Add any specific CSS for about page

// Add favicon
echo '<link rel="icon" type="image/png" href="uploads/assets/logo-ico-tab.png">';

// Include the universal header
include("includes/header.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Your about page content goes here
?>

<div class="main-content">
    <!-- About Page Content -->
    <div class="container">
        <h1>About Us</h1>
        
        <!-- Organization Title and Description -->
        <div class="org-info">
            <h2>Future Business Teachers' Organization</h2>
            <p class="org-description">A student organization of Bachelor of Technology and Livelihood Education</p>
        </div>

        <!-- Members Section -->
        <div class="members-section">
            <!-- Row 1 -->
            <div class="members-row">
                <!-- President -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(PRESIDENT) NICKO S. BARRO.jpg" alt="President">
                    </div>
                    <div class="member-info">
                        <h3>President</h3>
                        <p class="member-name">NICKO S. BARRO</p>
                        <p>The President is the chief executive officer of the organization and is in charge of running things and ensuring policies are followed. Some of the most important duties are running meetings, setting up services, and signing official documents and emails. The President gives the go-ahead for cash transfers and ensures company rules are followed after speaking with other officers. Furthermore, the President may establish specific committees and allocate responsibilities to ensure the organization's smooth operation.</p>
                    </div>
                </div>

                <!-- VP Internal -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(VP FOR INTERNAL AFFAIRS) JESSEL P. JUNIOSA.jpg" alt="VP Internal">
                    </div>
                    <div class="member-info">
                        <h3>Vice President for Internal Affairs</h3>
                        <p class="member-name">JESSEL P. JUNIOSA</p>
                        <p>The Vice President for Internal Affairs ensures that the organization's internal operations run efficiently. This includes managing the organization's finances with the President and Auditor, monitoring office buildings and resources, keeping accurate records, and managing member perks and incentives. The vice president guarantees that internal accounting and auditing procedures are followed and fulfills any other responsibilities the President may assign.</p>
                    </div>
                </div>

                <!-- VP External -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(VP FOR EXTERNAL AFFAIRS) ANTHONY ACE U. MAGALLANES.jpg" alt="VP External">
                    </div>
                    <div class="member-info">
                        <h3>Vice President for External Affairs</h3>
                        <p class="member-name">ANTHONY ACE U. MAGALLANES</p>
                        <p>The Vice President for External Affairs develops and manages the organization's outreach efforts. This includes creating strategies, policies, and priorities for the FBTO Outreach Community Program and preparing and updating financial and progress reports about its operation. The Vice President also performs and fulfills other obligations as designated by the President.</p>
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="members-row">
                <!-- Secretary -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(SECRETARY) MARY VALERIE N. BISPO.jpg" alt="Secretary">
                    </div>
                    <div class="member-info">
                        <h3>Secretary</h3>
                        <p class="member-name">MARY VALERIE N. BISPO</p>
                        <p>The Secretary is responsible for maintaining certain documents and communicating on behalf of the organization. This includes meeting minutes, organizational document tracking, appointment reminders, communication creation and filing, and event attendance tracking. The Secretary also carries out additional duties as assigned by the President.</p>
                    </div>
                </div>

                <!-- Treasurer -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(TREASURER) KRISTINE NOELLA S. BUERE.jpg" alt="Treasurer">
                    </div>
                    <div class="member-info">
                        <h3>Treasurer</h3>
                        <p class="member-name">KRISTINE NOELLA S. BUERE</p>
                        <p>The Treasurer is responsible for managing the organization's financial affairs. This includes maintaining accurate financial records, collecting fees and funds, providing regular financial reports, and performing other duties as assigned by the President.</p>
                    </div>
                </div>

                <!-- Auditor -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(AUDITOR) DONALD C. NARADA.jpg" alt="Auditor">
                    </div>
                    <div class="member-info">
                        <h3>Auditor</h3>
                        <p class="member-name">DONALD C. NARADA</p>
                        <p>The Auditor is responsible for ensuring the financial integrity of the organization. This includes checking and verifying accounts and expenditures, reviewing the Treasurer's financial reports, coordinating with the President and Treasurer on fund management, and performing other duties as assigned by the President.</p>
                    </div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="members-row">
                <!-- PRO -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="uploads/assets/members/(P.R.O.) JAY MARK D.C. RAMOS.jpg" alt="PRO">
                    </div>
                    <div class="member-info">
                        <h3>Public Relations Officer</h3>
                        <p class="member-name">JAY MARK D.C. RAMOS</p>
                        <p>The Public Relations Officer (PRO) manages the organization's public image and communication. This includes serving as the official spokesperson, liaising with external organizations, developing and implementing public relations strategies, creating promotional materials, and performing other duties the President assigns.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facebook Feed and Contact Container -->
        <div class="social-contact-container">
            <!-- Facebook Feed Section -->
            <div class="facebook-feed-section">
                <h2>Follow Us on Facebook</h2>
                <div class="facebook-embed-container">
                    <div class="fb-page" 
                         data-href="https://www.facebook.com/pupqc.futureeducator" 
                         data-tabs="timeline" 
                         data-width="500" 
                         data-height="800" 
                         data-small-header="false" 
                         data-adapt-container-width="true" 
                         data-hide-cover="false" 
                         data-show-facepile="true">
                        <blockquote cite="https://www.facebook.com/pupqc.futureeducator" 
                                  class="fb-xfbml-parse-ignore">
                            <a href="https://www.facebook.com/pupqc.futureeducator">Future Business Teachers' Organization</a>
                        </blockquote>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <h2>Contact Us</h2>
                <div class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <span>pupqcfbtoofficial@gmail.com</span>
                </div>
                
                <form class="contact-form" action="process_contact.php" method="POST">
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="concern">Your Concern</label>
                        <textarea id="concern" name="concern" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>

        <!-- Location Section -->
        <div class="location-section">
            <h2>Our Location</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.8024419999997!2d121.06751877600001!3d14.699999999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b0f8a74d0fb1%3A0x3db5ed80e0beeef5!2sDon%20Fabian%20St%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1648123456789!5m2!1sen!2sph"
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>

<!-- Include the universal footer -->
<?php include("includes/footer.php"); ?>

<script src="js/about.js"></script>

<!-- Facebook SDK -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" nonce="YOUR_NONCE"></script>
</body>
</html>
