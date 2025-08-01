  <div class="sidebar" id="sidebar">
      <div class="sidebar-header">
          <h4>SHIOJI <span>APARTELLE</span></h4>
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
                  <span class="nav-text">Invoice</span>
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