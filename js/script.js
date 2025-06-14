document.addEventListener('DOMContentLoaded', () =>{
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if(toggleBtn && sidebar && overlay){
        toggleBtn.addEventListener('click', () =>{
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () =>{
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Active link highlight
    const links = document.querySelectorAll('.sidebar-link');
    const currentPage = window.location.pathname.split('/').pop();

    links.forEach(link =>{
        if(link.getAttribute('href') === currentPage){
            link.classList.add('active');
        }
    });

    // Status filter functionality
    if(statusFilterButton && dropdownContent && currentFilterInput && complaintDetailsDiv && complaintTableBody){
        const statusLinks = dropdownContent.querySelectorAll('a');
        const arrowSymbol = ' ▾';

        function getQueryParam(name){
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        function filterComplaintsTable(selectedStatus){
            let visibleComplaintsCount = 0;
            const allTableRows = complaintTableBody.querySelectorAll('tr');

            allTableRows.forEach(row =>{
                if(row.classList.contains('no-complaints')){
                    row.style.display = 'none'; 
                    return;
                }

                const statusCell = row.querySelector('.complaint-status');
                if(statusCell){
                    const rowStatus = statusCell.textContent.trim();
                    if(selectedStatus === 'All'){
                        row.style.display = '';
                        visibleComplaintsCount++;
                    } 
                    else if(rowStatus === selectedStatus){
                        row.style.display = '';
                        visibleComplaintsCount++;
                    }
                    else{
                        row.style.display = 'none';
                    }
                }
            });

            const noComplaintsRow = complaintTableBody.querySelector('.no-complaints');
            if(noComplaintsRow){
                if(visibleComplaintsCount === 0){
                    noComplaintsRow.style.display = '';
                } 
                else{
                    noComplaintsRow.style.display = 'none';
                }
            }
        }

        statusLinks.forEach(link =>{
            link.addEventListener('click', function(event){
                event.preventDefault();
                const selectedDisplayText = this.textContent;
                const selectedStatusValue = this.dataset.status;
                currentFilterInput.value = selectedStatusValue;
                const url = new URL(window.location.origin + window.location.pathname);
                url.searchParams.set('filter', selectedStatusValue);
                url.searchParams.delete('e'); 
                window.location.href = url.toString(); 
            });
        });

        statusFilterButton.addEventListener('click', function(event){
            event.stopPropagation();
            if(dropdownContent.style.display === 'block'){
                dropdownContent.style.display = 'none';
            } 
            else{
                const buttonRect = statusFilterButton.getBoundingClientRect();
                dropdownContent.style.top = (buttonRect.height) + 'px';
                dropdownContent.style.left = '0px';
                dropdownContent.style.display = 'block';
            }
        });

        document.addEventListener('click', function(event){
            if(!statusFilterButton.contains(event.target) && !dropdownContent.contains(event.target)){
                dropdownContent.style.display = 'none';
            }
        });

        complaintTableBody.addEventListener('click', function(event){
            const targetLink = event.target.closest('a[href*="?e="]');
            if(targetLink){
                event.preventDefault();
                const originalHref = targetLink.getAttribute('href');
                const url = new URL(originalHref, window.location.origin + window.location.pathname);
                const currentFilter = currentFilterInput.value;
                url.searchParams.set('filter', currentFilter);
                window.location.href = url.toString();
            }
        });


        let initialFilterValueFromURL = getQueryParam('filter');
        if(!initialFilterValueFromURL){
            initialFilterValueFromURL = 'All';
        }
        let initialFilterDisplayText = 'All';
        statusLinks.forEach(link =>{
            if(link.dataset.status === initialFilterValueFromURL){
                initialFilterDisplayText = link.textContent;
            }
        });

        statusFilterButton.textContent = initialFilterDisplayText + arrowSymbol;
        currentFilterInput.value = initialFilterValueFromURL;
        const isDetailViewActive = document.body.dataset.isDetailViewActive === 'true';
        if(isDetailViewActive){
            if(complaintDetailsDiv){
                complaintDetailsDiv.style.display = "block";
                complaintDetailsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } 
        else{
            if(complaintDetailsDiv){
                complaintDetailsDiv.style.display = 'none';
            }
        }
        filterComplaintsTable(initialFilterValueFromURL);
    }
});
