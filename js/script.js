document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Active link highlight
    const links = document.querySelectorAll('.sidebar-link');
    const currentPage = window.location.pathname.split('/').pop();
    links.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
});


// Fetch and display complaints
// fetch('get_complaints.php')
//     .then(response => response.json())
//     .then(data => {
//         const complaintList = document.getElementById('complaint-list');
//         complaintList.innerHTML = '';

//         if (data.length === 0) {
//             complaintList.innerHTML = '<p>No complaints found.</p>';
//             return;
//         }

//         data.forEach(comp => {
//             const listItem = document.createElement('li');
//             listItem.className = 'complaint-item';
//             listItem.innerHTML = `
//                 <span class="comp-id">#${comp.id}</span>
//                 <span class="comp-type">${comp.ctype}</span>
//                 <span class="comp-subject">${comp.sub}</span>
//                 <span class="comp-status">${comp.status}</span>
//                 <button class="view-btn" data-id="${comp.id}">View</button>
//             `;
//             complaintList.appendChild(listItem);
//         });
//     });
