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

    // Redirect to filtered URL
    statusLinks.forEach(link => {
        link.addEventListener('click', function(event){
            event.preventDefault();
            const selectedStatusValue = this.dataset.status;

            dropdownContent.style.display = 'none';

            const url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('filter', selectedStatusValue);
            url.searchParams.delete('e');  // remove complaint ID if changing filter
            window.location.href = url.toString();
        });
    });

    // Toggle dropdown visibility
    statusFilterButton.addEventListener('click', function(event){
        event.stopPropagation();
        dropdownContent.classList.toggle('show');
    });

    // Close dropdown on click outside
    document.addEventListener('click', function(e){
        if (!statusFilterButton.contains(e.target) && !dropdownContent.contains(e.target)) {
            dropdownContent.classList.remove('show');
        }
    });

    // Set filter button label from URL
    const initialFilterFromURL = getQueryParam('filter') || 'All';
    let displayFilterText = initialFilterFromURL;

    if (initialFilterFromURL === 'Rejected by Officer') {
        displayFilterText = 'Rejected';
    }

    statusLinks.forEach(link => {
        if (link.dataset.status === initialFilterFromURL || (initialFilterFromURL === 'Rejected by Officer' && link.dataset.status === 'Rejected')) {
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
    } else if(complaintDetailsDiv) {
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
document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('universalLoadingOverlay');

    if (loadingOverlay) {
        const allForms = document.querySelectorAll('form');
        allForms.forEach(form => {
            form.addEventListener('submit', function(event) {
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
