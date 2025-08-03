  <div class="sidebar" id="sidebar">
      <div class="sidebar-header">
          <a href="../index.php" style="text-decoration: none; color: inherit;">
              <h4>SHIOJI <span>APARTELLE</span></h4>
          </a>

          <button class="close-sidebar" id="closeSidebar">
              <i class="fas fa-times"></i>
          </button>
      </div>
      <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
      <ul class="nav flex-column mt-3">
          <u>&nbsp;&nbsp;Main</u>

          <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'dashboard.php') echo 'active'; ?>" href="dashboard.php">
                  <i class="fas fa-tachometer-alt"></i>
                  <span class="nav-text">Dashboard</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'bookings.php') echo 'active'; ?>" href="bookings.php">
                  <i class="fas fa-calendar-check"></i>
                  <span class="nav-text">Bookings</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'rooms.php') echo 'active'; ?>" href="rooms.php">
                  <i class="fas fa-bed"></i>
                  <span class="nav-text">Rooms</span>
              </a>
          </li>
          <li class="nav-item ">
              <a class="nav-link <?php if ($currentPage == 'room_pricing.php') echo 'active'; ?>" href="room_pricing.php">
                  <i class="fas fa-sign-out-alt"></i>
                  <span class="nav-text">Add Room Type/Pricing</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'guest.php') echo 'active'; ?>" href="guest.php">
                  <i class="fas fa-users"></i>
                  <span class="nav-text">Guest</span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'reports.php') echo 'active'; ?>" href="reports.php">
                  <i class="fas fa-chart-bar"></i>
                  <span class="nav-text">Reports</span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'history.php') echo 'active'; ?>" href="history.php">
                  <i class="fas fa-users"></i>
                  <span class="nav-text">Guest History</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link <?php if ($currentPage == 'settings.php') echo 'active'; ?>" href="setting.php">
                  <i class="fas fa-cog"></i>
                  <span class="nav-text">Settings</span>
              </a>
          </li>

          <u class="mt-4">&nbsp;&nbsp;Finance</u>

          <li class="nav-item ">
              <a class="nav-link <?php if ($currentPage == 'billing.php') echo 'active'; ?>" href="billing.php">
                  <i class="fas fa-sign-out-alt"></i>
                  <span class="nav-text">Billing</span>
              </a>
          </li>
          <li class="nav-item ">
              <a class="nav-link <?php if ($currentPage == 'invoice.php') echo 'active'; ?>" href="invoice.php">
                  <i class="fas fa-sign-out-alt"></i>
                  <span class="nav-text">Transactions</span>
              </a>
          </li>

          <li class="nav-item mt-4">
              <a class="nav-link <?php if ($currentPage == 'logout.php') echo 'active'; ?>" href="logout.php">
                  <i class="fas fa-sign-out-alt"></i>
                  <span class="nav-text">Logout</span>
              </a>
          </li>
      </ul>

      <div class="sidebar-header mt-auto">
          <button class="collapse-btn" id="collapseSidebar">
              <i class="fas fa-chevron-left"></i>
          </button>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const sidebar = document.getElementById('sidebar');
          const sidebarToggle = document.getElementById('sidebarToggle');
          const closeSidebar = document.getElementById('closeSidebar');
          const sidebarOverlay = document.getElementById('sidebarOverlay');
          const collapseBtn = document.getElementById('collapseSidebar');

          // Toggle sidebar on button click
          sidebarToggle.addEventListener('click', function() {
              sidebar.classList.toggle('expanded');
              sidebarOverlay.classList.toggle('active');
          });

          // Collapse sidebar
          collapseBtn.addEventListener('click', function() {
              sidebar.classList.toggle('collapsed');
          });

          // Close sidebar when clicking the close button
          closeSidebar.addEventListener('click', function() {
              sidebar.classList.remove('expanded');
              sidebarOverlay.classList.remove('active');
          });

          // Close sidebar when clicking outside
          sidebarOverlay.addEventListener('click', function() {
              sidebar.classList.remove('expanded');
              sidebarOverlay.classList.remove('active');
          });

          // Handle window resize
          function handleResize() {
              if (window.innerWidth > 992) {
                  sidebar.classList.remove('expanded');
                  sidebarOverlay.classList.remove('active');
              }
          }

          // Add resize event listener
          window.addEventListener('resize', handleResize);

          // Initialize with correct state
          handleResize();

          // Time period buttons
          const timeButtons = document.querySelectorAll('.time-btn');
          timeButtons.forEach(btn => {
              btn.addEventListener('click', function() {
                  timeButtons.forEach(b => b.classList.remove('active'));
                  this.classList.add('active');
              });
          });

          // Initialize charts
          initCharts();
      });
  </script>