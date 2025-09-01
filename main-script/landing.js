// Function to handle slide-in animation for the landing page
document.addEventListener("DOMContentLoaded", function() {
    // Add the slide-in class to the landing container on load
    const container = document.querySelector('.container');
    container.classList.add('slide-in');

    // Add event listener for the "Get Started" button
    document.querySelector('.btn-outline-light').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        const targetUrl = this.getAttribute('href'); // Get the target URL

        // Apply the slide-out animation
        container.classList.remove('slide-in'); // Remove slide-in class
        container.classList.add('slide-out-top'); // Add slide-out to top class

        // Debugging: Log to check if the class is added
        console.log("Slide-out animation triggered");

        // Wait for the animation to finish before redirecting
        setTimeout(() => {
            // Check if the slide-out animation has completed
            if (container.classList.contains('slide-out-top')) {
                window.location.href = targetUrl; // Redirect to the target URL
            }
        }, 500); // Match this duration with the animation duration (0.5s)
    });
});