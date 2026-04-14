<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'MPG Solution | Admin Dashboard'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        .toggle-icon {
            transition: transform 0.3s ease, color 0.3s ease; /* Smooth transition */
            cursor: pointer; /* Pointer cursor for better UX */
        }
        
        .toggle-icon:hover {
            transform: scale(1.3); /* Enlarge effect */
            color: #f39c12; /* Highlight color on hover */
        }
        
        .collapse-icon {
            display: inline-block; /* Initially shown */
        }
        
        .expand-icon {
            display: none; /* Initially hidden */
        }
        .sidebar {
            width: 300px;
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            position: fixed;
            transition: all 0.3s ease;
            overflow-y: auto;
            padding-top: 10px;
            z-index: 1000;
            left: 0;
        }

        .sidebar.collapsed {
    width: 80px; /* Sidebar को चौडाई सानो */
    overflow: hidden; /* Boundary भित्र content लुकाउने */
}

.sidebar.collapsed .custom-profile-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 0; /* Spacing for profile picture */
}

.sidebar.collapsed .profile-details,
.sidebar.collapsed .rate-card {
    display: none; /* Profile details र rate card लुकाउने */
}

.sidebar.collapsed .profile-image-wrapper img {
    width: 50px; /* Profile picture छोटो */
    height: 50px;
    border: 3px solid #ffffff;
    transition: all 0.3s ease; /* Smooth transition */
}

.sidebar.collapsed .menu-text {
    display: none; /* Menu text लुकाउने */
}


        .custom-profile-card {
    background: linear-gradient(to bottom, #4e73df, #2e4c72);
    color: white;
    text-align: center;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    transition: transform 0.3s ease;
}

.custom-profile-card:hover {
    transform: translateY(-5px);
}

.profile-image-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}

.profile-image-wrapper img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.profile-image-wrapper img:hover {
    transform: scale(1.1);
}

.profile-details {
    margin-bottom: 15px;
}

.customer-name {
    font-size: 24px; /* Font size ठूलो */
    font-weight: bold; /* Bold style */
    text-align: center; /* Center alignment */
    margin-bottom: 10px;
}

.customer-info {
    font-size: 14px; /* अरु जानकारी सानो font size */
    text-align: left; /* Left alignment */
    margin: 5px 0; /* थोरै gap */
    display: flex; /* Icon र text align गर्न */
    align-items: center;
}

.customer-info i {
    margin-right: 8px; /* Icon र text बीच gap */
    color: #f39c12; /* Icon को रंग */
}

.rate-card {
    padding: 10px 15px;
    border-radius: 8px;
    background: #f39c12;
    color: white;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
}

.rate-card i {
    margin-right: 5px;
}


        .sidebar.collapsed .custom-profile-card img {
            width: 50px;
            height: 50px;
        }

        .menu-divider {
            height: 2px;
            background-color: #212529;
            margin: 10px 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px;
            text-decoration: none;
            color: white;
            font-size: 17px;
            margin-left: 13px;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 300px;
            padding: 0px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        .content.collapsed {
            margin-left: 80px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70%;
                left: -100%;
                position: fixed;
            }

            .sidebar.open {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }

        .navbar {
            margin-bottom: 20px;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: auto;
            width: 100%;
            position: relative;
        }
      
    </style>
</head>
<body>
    <div class="sidebar">
        <?php if(isset($customer)): ?>
    <div class="custom-profile-card">
        <div class="profile-image-wrapper">
            <img src="<?php echo e($customer->profile_picture ? asset('uploads/customers/' . $customer->profile_picture) : asset('uploads/customers/default.jpg')); ?>" alt="Profile Picture">
        </div>
        <div class="profile-details">
            <h3 class="customer-name"><?php echo e($customer->name); ?></h3>
            <p class="customer-info"><i class="fas fa-user-circle"></i> <?php echo e($customer->display_name); ?></p>
            <p class="customer-info"><i class="fas fa-envelope"></i> <?php echo e($customer->email); ?></p>
            <p class="customer-info"><i class="fas fa-phone-alt"></i> <?php echo e($customer->phone); ?></p>
            <p class="customer-info"><i class="fas fa-map-marker-alt"></i> <?php echo e($customer->address); ?></p>
        </div>
        <div class="rate-card">
            <p>Rate: <strong><?php echo e($usdRate); ?></strong></p>
        </div>
    </div>
<?php else: ?>
    <div class="custom-profile-card">
        <div class="profile-image-wrapper">
            <img src="<?php echo e(asset('uploads/customers/default.jpg')); ?>" alt="Default Profile Picture">
        </div>
        <div class="profile-details">
            <h3 class="customer-name">Guest User</h3>
            <p class="customer-info">No profile information available.</p>
        </div>
    </div>
<?php endif; ?>

        <div class="menu-divider"></div>
        <a href="<?php echo e(url('/portal/dashboard')); ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a>
        <a href="#"><i class="fas fa-shopping-cart"></i> <span class="menu-text">Orders</span></a>
        <a href="<?php echo e(route('portal.adsinsights')); ?>"><i class="fas fa-chart-line"></i> <span class="menu-text">Campaign Reports</span></a>
        <a href="<?php echo e(route('portal.invoices')); ?>"><i class="fas fa-file-invoice"></i> <span class="menu-text">Invoice</span></a>
        <a href="<?php echo e(url('/portal/profile-settings')); ?>"><i class="fas fa-user"></i> <span class="menu-text">Profile</span></a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Logout</span></a>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="toggle-btn" style="font-size: 20px; color: white; font-weight: bold;">
    <i class="fas fa-chevron-left toggle-icon collapse-icon"></i>
    <i class="fas fa-chevron-right toggle-icon expand-icon" style="display: none;"></i>
</div>

    <div class="container">
        <div style="text-decoration: none;color: #695c5c;font-weight: 600;font-size: 25px; margin-right:10px;">| </div>

<a href="<?php echo e(url('/portal/dashboard')); ?>" style="text-decoration: none;color: white;font-weight: 600;font-size: 25px;">Customer Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="https://mpg.com.np/about-us/">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://mpg.com.np/our-team/">Our Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://mpg.com.np/category/blogs/">Blogs</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Services
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="servicesDropdown">
                        <a class="dropdown-item" href="https://mpg.com.np/services-one/">Services One</a>
                        <a class="dropdown-item" href="https://mpg.com.np/terms-services/">Terms of Service</a>
                        <a class="dropdown-item" href="https://mpg.com.np/privacy-policy/">Privacy Policy</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo e($customer->name); ?>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo e(url('/portal/profile-settings')); ?>">Settings</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="<?php echo e(route('portal.logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>


        <?php echo $__env->yieldContent('content'); ?>

        <footer>
            &copy; <?php echo e(date('Y')); ?> Customer Portal. All rights reserved.
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        const toggleBtn = document.querySelector('.toggle-btn');
        const collapseIcon = document.querySelector('.collapse-icon');
        const expandIcon = document.querySelector('.expand-icon');

        // Toggle the sidebar on click
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('open');
            } else {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');

                // Swap icons
                if (sidebar.classList.contains('collapsed')) {
                    collapseIcon.style.display = 'none';
                    expandIcon.style.display = 'inline-block';
                } else {
                    collapseIcon.style.display = 'inline-block';
                    expandIcon.style.display = 'none';
                }
            }
        });

        // Close the sidebar when clicking outside of it on mobile
        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    });
</script>

</body>
</html>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/layouts/customerlayout.blade.php ENDPATH**/ ?>