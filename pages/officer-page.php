<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../css/styles.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
        <title>Admin Page</title>
    </head>
    <body>           
        <!-- navbar+sidebar(from menu.php)+ main content  --> 
        <div class="wrapper">
            <?php include 'menu.php';?>
            <main class="officer-main">
                    <h2>Officer Page</h2>
                        <h2>List of all complaints</h2>
                    <div class="clist-container">
                        <table class="clist-table">
                            <thead>
                                <tr>
                                    <th>Complaint ID</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row, replace with dynamic content -->
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <tr>
                                    <td>12345</td>
                                    <td>Hardware</td>
                                    <td>Printer not working</td>
                                    <td>Pending</td>
                                    <td><button class="view-btn">View Details</button></td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                    <div class="cdetails">
                        <h2>Complaint Details</h2>
                        <p><strong>Complaint ID:</strong> 12345</p>
                        <p><strong>Type:</strong> Hardware</p>
                        <p><strong>Subject:</strong> Printer not working</p>
                        <p><strong>Description:</strong> The printer in the main office is not functioning properly. It jams frequently and prints with faded ink.</p>
                        <p><strong>File:</strong> File.</p>
                        <p><strong>Status:</strong> Pending</p>
                        <button class="close-btn">Close</button>

                    </div>
                </main>
        </div>
    </body>
</html>