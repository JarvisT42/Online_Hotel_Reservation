<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Management - Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }
        
        /* Topbar */
        .topbar {
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .page-title {
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        
        /* Card Styles */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eef2f7;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        
        /* Table Styles */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 15px 20px;
            border-top: 1px solid #eef2f7;
        }
        
        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border-top: 1px solid #eef2f7;
        }
        
        .table tbody tr:last-child td {
            border-bottom: 1px solid #eef2f7;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-paid {
            background-color: #e6f4ea;
            color: #0d6832;
        }
        
        .badge-pending {
            background-color: #fef3e2;
            color: #92400f;
        }
        
        .badge-partial {
            background-color: #ebf5ff;
            color: #1a56db;
        }
        
        /* Action Buttons */
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 3px;
        }
        
        .btn-view {
            background-color: #ebf5ff;
            color: #3b82f6;
        }
        
        .btn-print {
            background-color: #f0fdf4;
            color: #22c55e;
        }
        
        .btn-edit {
            background-color: #fef3e2;
            color: #f59e0b;
        }
        
        /* Stats */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        
        .stat-content h3 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        
        .stat-content p {
            color: #718096;
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar .nav-text {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .topbar {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="text-center mb-0"><i class="fas fa-hotel me-2"></i> Horizon Inn</h4>
            <p class="text-center text-white-50 mb-0">Administration</p>
        </div>
        
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-home"></i> <span class="nav-text">Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-bed"></i> <span class="nav-text">Rooms</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-calendar-check"></i> <span class="nav-text">Bookings</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fas fa-file-invoice-dollar"></i> <span class="nav-text">Billing</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-users"></i> <span class="nav-text">Guests</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> <span class="nav-text">Reports</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-cog"></i> <span class="nav-text">Settings</span></a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h1 class="page-title"><i class="fas fa-file-invoice-dollar me-2"></i>Billing Management</h1>
                <p class="text-muted mb-0">View and manage all billing records</p>
            </div>
            
            <div class="user-info">
                <div class="notification position-relative">
                    <i class="fas fa-bell fs-5 text-muted"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </div>
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin" class="user-avatar">
                <div>
                    <div class="fw-bold">John Doe</div>
                    <div class="text-muted small">Administrator</div>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-content">
                    <h3>18</h3>
                    <p>Total Bills</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>₱28,560</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>5</h3>
                    <p>Pending Payments</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-content">
                    <h3>72%</h3>
                    <p>Collection Rate</p>
                </div>
            </div>
        </div>
        
        <!-- Billing Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list me-2"></i>Billing Records</span>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Create New Bill
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Bill ID</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#B-1025</td>
                                <td>Michael Johnson</td>
                                <td>Deluxe 301</td>
                                <td>Jun 15, 2023</td>
                                <td>Jun 18, 2023</td>
                                <td>₱5,400.00</td>
                                <td><span class="badge badge-paid">Paid</span></td>
                                <td>
                                    <button class="btn btn-action btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-action btn-print"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#B-1024</td>
                                <td>Sarah Williams</td>
                                <td>Suite 201</td>
                                <td>Jun 14, 2023</td>
                                <td>Jun 17, 2023</td>
                                <td>₱7,200.00</td>
                                <td><span class="badge badge-pending">Pending</span></td>
                                <td>
                                    <button class="btn btn-action btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-action btn-print"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#B-1023</td>
                                <td>Robert Brown</td>
                                <td>Standard 105</td>
                                <td>Jun 12, 2023</td>
                                <td>Jun 15, 2023</td>
                                <td>₱3,600.00</td>
                                <td><span class="badge badge-paid">Paid</span></td>
                                <td>
                                    <button class="btn btn-action btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-action btn-print"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#B-1022</td>
                                <td>Jennifer Davis</td>
                                <td>Deluxe 302</td>
                                <td>Jun 10, 2023</td>
                                <td>Jun 13, 2023</td>
                                <td>₱8,100.00</td>
                                <td><span class="badge badge-partial">Partial</span></td>
                                <td>
                                    <button class="btn btn-action btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-action btn-print"><i class="fas fa-print"></i></button>
                                    <button class="btn btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#B-1021</td>
                                <td>Thomas Miller</td>
                                <td>Suite 202</td>
                                <td>Jun 8, 2023</td>
                                <td>Jun 11, 2023</td>
                                <td>₱6,750.00</td>
                                <td><span class="badge badge-paid">Paid</span></td>
                                <td>
                                    <button class="btn btn-action btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-action btn-print"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>Recent Billing Activity
            </div>
            <div class="card-body">
                <div class="d-flex border-bottom pb-3 mb-3">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                            <i class="fas fa-file-invoice fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">New bill created for Jennifer Davis</h6>
                        <p class="mb-0 text-muted">Deluxe Room 302 - ₱8,100.00</p>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                </div>
                
                <div class="d-flex border-bottom pb-3 mb-3">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar bg-success bg-opacity-10 text-success rounded-circle p-2">
                            <i class="fas fa-money-bill-wave fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Payment received from Robert Brown</h6>
                        <p class="mb-0 text-muted">Standard Room 105 - ₱3,600.00</p>
                        <small class="text-muted">Yesterday, 4:30 PM</small>
                    </div>
                </div>
                
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                            <i class="fas fa-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Payment reminder sent to Sarah Williams</h6>
                        <p class="mb-0 text-muted">Suite 201 - ₱7,200.00 (Due today)</p>
                        <small class="text-muted">Jun 16, 9:15 AM</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple notification animation
        document.querySelector('.notification').addEventListener('click', function() {
            this.querySelector('.fa-bell').classList.add('fa-shake');
            setTimeout(() => {
                this.querySelector('.fa-bell').classList.remove('fa-shake');
            }, 500);
        });
        
        // Add hover effect to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.transform = 'scale(1.01)';
                row.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.05)';
            });
            
            row.addEventListener('mouseleave', () => {
                row.style.transform = 'scale(1)';
                row.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>