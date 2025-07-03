document.addEventListener('DOMContentLoaded', function(){
    const statusFilterButton = document.getElementById('statusFilterButton');
    const dropdownContent = document.querySelector('.cstatus-select-content');
    const statusLinks = dropdownContent.querySelectorAll('a');
    const complaintDetailsDiv = document.getElementById("complaintDetails");
    const currentFilterInput = document.getElementById('currentFilter');
    const arrowSymbol = ' ▾';

    function getQueryParam(name){
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

  // --- Dropdown Button Functionality (for opening/closing the dropdown) ---
    if(statusFilterButton){
        // Toggle dropdown visibility on button click
        statusFilterButton.addEventListener('click', function(){
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });

        // Set button text to the current filter on page load
        const urlParams = new URLSearchParams(window.location.search);
        const currentFilter = urlParams.get('filter');
        if(currentFilter){
            statusFilterButton.textContent = currentFilter + ' ▾';
        } 
        else{
            // Default text if no filter is applied (All)
            const allLink = Array.from(statusLinks).find(link => link.dataset.status === 'All');
            if(allLink){
                statusFilterButton.textContent = allLink.textContent + ' ▾';
            }
        }
    }

    // Event Listener for Status Filter Links
    statusLinks.forEach(link => {
        link.addEventListener('click', function(event){
            event.preventDefault(); // Stop the default link behavior
            const selectedStatusValue = this.dataset.status; // Get the chosen status (e.g., "Pending")
            dropdownContent.style.display = 'none'; // Hide the dropdown menu
            const url = new URL(window.location.href); // Get the current page's full URL.
            const currentViewParam = url.searchParams.get('view'); // Extract the 'view' param from the current URL
            url.searchParams.set('view', currentViewParam || 'all'); // Set 'view' in the new URL. If `currentViewParam` is null (no view set), it defaults to 'all'.
            url.searchParams.set('filter', selectedStatusValue);
            url.searchParams.delete('e'); 
            // Redirect the browser to the newly constructed URL
            window.location.href = url.toString();
        });
    });

    // Close Dropdown if clicked outside
    window.addEventListener('click', function(event){
        if(statusFilterButton && dropdownContent){
            if(!event.target.matches('.dropbtn') && !event.target.closest('.cstatus-select-content')){
                dropdownContent.style.display = 'none';
            }
        }
    });

    // Set filter button label from URL
    const initialFilterFromURL = getQueryParam('filter') || 'All';
    let displayFilterText = initialFilterFromURL;

    if(initialFilterFromURL === 'Rejected by Officer'){
        displayFilterText = 'Rejected';
    }

    statusLinks.forEach(link => {
        if(link.dataset.status === initialFilterFromURL || (initialFilterFromURL === 'Rejected by Officer' && link.dataset.status === 'Rejected')){
            displayFilterText = link.textContent;
        }
    });

    statusFilterButton.textContent = displayFilterText + arrowSymbol;
    currentFilterInput.value = initialFilterFromURL;

    // Show complaint details if ?e= exists
    const initialDetailsDisplay = document.body.dataset.showDetails === 'true';

    if(initialDetailsDisplay && complaintDetailsDiv){
        complaintDetailsDiv.style.display = "block";
        complaintDetailsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else if(complaintDetailsDiv){
        complaintDetailsDiv.style.display = 'none';
    }

// for sidebar
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('overlay');

    if(toggleBtn && sidebar && overlay){
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    const links = document.querySelectorAll('.sidebar-link');
    const currentPage = window.location.pathname.split('/').pop();

    links.forEach(link => {
        if(link.getAttribute('href') === currentPage){
            link.classList.add('active');
        }
    });
});

//Buffering
document.addEventListener('DOMContentLoaded', function(){
    const loadingOverlay = document.getElementById('universalLoadingOverlay');

    if(loadingOverlay){
        const allForms = document.querySelectorAll('form');
        allForms.forEach(form => {
            form.addEventListener('submit', function(event){
                loadingOverlay.classList.add('show');
                
                // Disable all submit buttons in the form to prevent double-clicks
                const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                submitButtons.forEach(button => {
                    button.disabled = true;
                });
            });
        });
    }
});
