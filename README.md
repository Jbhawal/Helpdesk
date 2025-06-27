# 🛠️ Helpdesk Complaint Management System

A web-based PHP application to streamline the process of submitting, managing, and tracking complaints in an organization. It supports multi-role access with features like complaint forwarding, remarking, and status tracking.

---

## 📌 Features

- 🔐 **Authentication**: Employee login with secure sessions.
- 📝 **Complaint Filing**: Users can lodge new complaints with subject and details.
- 📤 **Forwarding Workflow**: Officers/Admins can return complaints for clarification or forward them to the next level.
- 💬 **Remarks System**: Employees can add remarks when complaints are returned.
- 📊 **Status Tracking**: View real-time complaint status and remarks history.
- 📂 **Role-based Dashboard**:
  - **Employee**: File, track, and remark complaints.
  - **Officer**: View, forward, or return complaints.
  - **Admin**: Full access, reports, and complaint overview.
- 📱 **Responsive UI**: Sidebar toggle on mobile devices, always visible on larger screens.
- ✨ **Wrapped Text Handling**: Long subjects are wrapped neatly to prevent layout breakage.

---

## 📁 Project Structure

```
/helpdesk/
│
├── include/
│   └── config.php           # Database connection and constants
│
├── login/
│   ├── index.php            # Login form
│   └── auth.php             # Login logic
│
├── employee/
│   ├── dashboard.php        # Employee dashboard
│   └── file_complaint.php   # Complaint form
│
├── officer/
│   └── officer_panel.php    # Officer complaint view and actions
│
├── admin/
│   └── admin_panel.php      # Admin-level access and complaint logs
│
├── assets/
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript (sidebar toggle, etc.)
│   └── images/              # Icons and branding
│
├── menu.php                 # Common navbar + sidebar
├── index.php                # Landing or redirection logic
└── README.md                # Project documentation
```

---

## ⚙️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Backend**: PHP (Core PHP, no frameworks)
- **Database**: MySQL
- **Libraries**: (Optional) jQuery, Bootstrap

---

## 🛠️ Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/helpdesk.git
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
   - Place the project folder inside your local server’s `htdocs` directory (e.g., XAMPP).
   - Open in browser:
     ```
     http://localhost/helpdesk/login/index.php
     ```

---

## 📸 Screenshots

> _Add relevant screenshots like complaint form, officer dashboard, etc._

---

## ✅ Future Improvements

- 📥 File attachments for complaints  
- 📧 Email notifications  
- 📈 Complaint analytics and export options  
- 🧪 Unit testing and advanced validation  
- 🔔 Real-time notifications

---

## 🤝 Contributors

- **Joyita Bhawal** – Developer & Designer

---

## 📝 License

This project is licensed under the [MIT License](LICENSE).