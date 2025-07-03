# 🛠️ Helpdesk Complaint Management System

The **Helpdesk** is a web-based PHP application working as a Complaint Management System designed to streamline the process of raising, tracking, and resolving complaints within an organization. It supports multiple user roles—such as employees, officers, and administrators—each with specific privileges to manage the complaint lifecycle efficiently. The system is built with a clean, a role-based access structure, and dynamic complaint tracking.
- 👤 **Employees**
  - Can file new complaints.
  - Can view the status and history of their complaints.
  - Can add remarks if the complaint is returned back to them.

- 🕵️ **Officers**
  - Can lodge complaints (same as employees).
  - Can view and manage complaints assigned to them.
  - Can return complaints with remarks or forward them to admin for further action.

- 🛠️ **Admins**
  - Have access to all system data.
  - Can monitor, report on, and manage complaints.
  - Cannot interfere with complaints that are still in employee or officer stages..

It is ideal for institutions, departments, or workplaces aiming to reduce paperwork, improve transparency, and ensure timely resolutions to issues raised internally.

---
## 🔄 Complaint Flow

1. **Lodging a Complaint**:
   - Any logged-in **Employee** or **Officer** can lodge a new complaint through a dedicated form.
   - The complaint is stored in the database with a default status *Pending*.

2. **Initial Review**:
   - The assigned **Officer** reviews the complaint.
   - They can either **forward** it to the admin or **return** it to the submitter for additional remarks or directly reject it.

3. **Returned Complaints**:
   - If a complaint is returned, the submitter can **add remarks** and **resubmit** the complaint.
   - This ensures clarity before it is escalated or resolved.

4. **Resolution / Forwarding**:
   - Officers can **forward** complaints for resolution.
   - The complaint status is updated accordingly and visible to all relevant parties.

5. **Admin Oversight**:
   - The **Admin** has full access to view all complaints passed to them, their statuses, and work on them.
---

## 📌 Features

- 🔐 **Authentication**: User login with secure sessions.
- 🧾 **Dashboard**: User dashboard for a summarised view of complaint data.
- 📝 **Complaint Filing**: Users can lodge new complaints with subject and description.
- 📤 **Forwarding Workflow**: Officers/Admins can return complaints for clarification or forward them to the next level.
- 💬 **Remarks System**: Users can view past and add further remarks when they work on the complaints or are returned to them.
- 📊 **Status Tracking**: View real-time complaint status and remarks history.
- 📂 **Role-based Dashboard**:
  - **Employee**: File, track, and remark complaints.
  - **Officer**: View, file, forward, reject or return complaints.
  - **Admin**: View, close, reject or return complaints.
- 📱 **Responsive UI**: Sidebar toggle on mobile devices, always visible on larger screens.
- ✨ **Wrapped Text Handling**: Long subjects are wrapped neatly to prevent layout breakage.

---

## 📁 Project Structure

```
helpdesk/                    #root folder
│
├── backups/                 # Backup files or older versions
│
├── css/                     # Stylesheets for the UI
│   └── styles.css           # Main stylesheet
│
├── images/                  # Logos and images used
│
├── include/                 # Configuration and utility PHP files
│   └── config.php           # Database connection and config settings
│
├── instr/                   # Project instructions (for my understanding)
│
├── js/                      # JavaScript files for interactivity
│   ├── script.js            
│   └── validation.js        
│
├── master/                  # Has master table
│   ├── Master.csv                       
│
├── pages/                   # Core application pages grouped by functionality
│   ├── admin-page.php       
│   ├── dashboard.php   
│   ├── employee-menu.php    
│   ├── employee-page.php    
│   ├── logout.php           
│   ├── menu.php             
│   ├── officer-page.php     
│   ├── register.php           
│   └── view-status.php
│
├── uploads/                 # Stored complaint file attachments
│
├── index.php                # Entry point or login redirect
├── README.md                # Project documentation
└── table creation.txt       # SQL schema or table structure notes
```

---

## ⚙️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Backend**: PHP (Core PHP, no frameworks)
- **Database**: MySQL

---

## 🛠️ Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/Jbhawal/helpdesk.git
   ```

2. **Import the Database**:
   - Open PHPMyAdmin or your MySQL CLI.
   - Import the `helpdesk.sql` file into a database named `helpdesk`.

3. **Configure Database Connection**:
   - Edit `/include/config.php`:
     ```php
     $host = 'localhost';
     $user = 'root';
     $pass = '';
     $db   = 'helpdesk';
     ```

4. **Run the App**:
   - Place the project folder inside WAMP's `www` directory:
     ```
     C:/wamp64/www/helpdesk/
     ```
   - Start WAMP and open your browser to:
     ```
     http://localhost/helpdesk/index.php
     ```

---

## 🤝 Contribution

If you'd like to contribute to this project, feel free to submit issues or open pull requests.  
All kinds of contributions — from code improvements and bug fixes to documentation enhancements are welcome!

---