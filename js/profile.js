// Profile page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Edit Profile button functionality
    const editProfileBtn = document.getElementById('editProfileBtn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            const sidebar = document.getElementById('updateProfileSidebar');
            sidebar.classList.toggle('active');
        });
    }

    // Close sidebar button functionality
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', function() {
            const sidebar = document.getElementById('updateProfileSidebar');
            sidebar.classList.remove('active');
        });
    }

    // Image preview functionality
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(event) {
            previewImage(event);
        });
    }

    // Function to preview the selected image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('profilePicturePreview');
                if (img) {
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // AJAX functionality for article search and sorting
    const searchInput = document.querySelector('input[name="search"]');
    const sortSelect = document.querySelector('select[name="sort_by"]');

    if (searchInput) {
        searchInput.addEventListener('input', fetchArticles);
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', fetchArticles);
    }

    function fetchArticles() {
        const searchQuery = searchInput ? searchInput.value : '';
        const sortBy = sortSelect ? sortSelect.value : 'date_saved_desc';
        const currentPage = 1; // Reset to the first page when fetching

        // Create a new XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `fetch_articles.php?search=${encodeURIComponent(searchQuery)}&sort_by=${encodeURIComponent(sortBy)}&page=${currentPage}`, true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                const articlesContainer = document.getElementById('articlesContainer');
                if (articlesContainer) {
                    articlesContainer.innerHTML = xhr.responseText;
                }
            } else {
                console.error('Error fetching articles:', xhr.statusText);
            }
        };
        
        xhr.onerror = function() {
            console.error('Network error occurred');
        };
        
        xhr.send();
    }
}); 