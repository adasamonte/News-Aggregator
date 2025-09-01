// Function to handle slide-in animation for the registration page
document.addEventListener("DOMContentLoaded", function() {
    // Add the slide-in class to the registration container on load
    const container = document.querySelector('.form-container');
    container.classList.add('slide-in');

    // Add event listener for the form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        handleSlideInAndSubmit(this); // Call the function to handle animation and submission
    });
});

// Function to handle slide-in animation and form submission
function handleSlideInAndSubmit(form) {
    const container = document.querySelector('.form-container');
    container.classList.remove('slide-in'); // Remove slide-in class
    container.classList.add('slide-out'); // Add slide-out class

    // Wait for the animation to finish before submitting the form
    setTimeout(() => {
        form.submit(); // Submit the form after the animation
    }, 500); // Match this duration with the animation duration
}
