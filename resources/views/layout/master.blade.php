<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('master.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.js"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        $(document).ready(function() {
            $('.sidebar .logo-details').click(function() {
                $('.sidebar').toggleClass('close');
            });
        });
    </script>
</head>

<body style='background-color:#F5F6FA'>
    <div class="sidebar close">
        <div class="logo-details">
            <i class='bx bx-menu' id='menuToggle'></i>
            <span class="logo-name">Adamson University</span>
        </div>
        <ul class="nav-links">
            <!-- Admin Links -->
            @if (session()->get('user_level') === 'AD')
                <li>
                    <a href="{{ route('viewAdminDashboard') }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="link_name">Dashboard</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewAdminDashboard') }}">Dashboard</a></li>
                    </ul>
                </li>
                <li id="appraisalsNav">
                    <a href="{{ route('viewAdminAppraisalsOverview') }}">
                        <i class='bx bx-file'></i>
                        <span class="link_name">Appraisal Overview</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewAdminAppraisalsOverview') }}">Appraisal
                                Overview</a>
                        </li>
                    </ul>
                </li>
                <li id='employeesNav'>
                    <a href="{{ route('viewEmployeeTable') }}">
                        <i class='bx bx-user-plus'></i>
                        <span class="link_name">Employees</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewEmployeeTable') }}">Employees</a>
                        </li>
                    </ul>
                </li>
                <li id='evalYearNav'>
                    <a href="{{ route('viewEvaluationYears') }}">
                        <i class='bx bx-box'></i>
                        <span class="link_name">Evaluation<br>Year</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewEvaluationYears') }}">Evaluation Year</li>
                    </ul>
                </li>
                <li id='editableFormsNav'>
                    <div class="icon-link">
                        <a href="#">
                            <i class='bx bx-book-alt'></i>
                            <span class="link_name">Editable Forms</span>
                        </a>
                        <i class='bx bxs-chevron-down arrow'></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a href="{{ route('viewEditableAppraisalForm') }}">Appraisal Form</a></li>
                        <li><a href="{{ route('viewEditableInternalCustomerForm') }}">Internal Customers<br>Form</a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Immediate Superior Links -->
            @if (session()->get('user_level') === 'IS')
                <li>
                    <a href="{{ route('viewISDashboard') }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="link_name">Dashboard</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewISDashboard') }}">Dashboard</a></li>
                    </ul>
                </li>
                <li id="appraisalsNav">
                    <a href="{{ route('viewISAppraisalsOverview') }}">
                        <i class='bx bx-file'></i>
                        <span class="link_name">Appraisals</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewISAppraisalsOverview') }}">Appraisals</a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Permanent Employee Links-->
            @if (session()->get('user_level') === 'PE')
                <li>
                    <a href="{{ route('viewPEDashboard') }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="link_name">Dashboard</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewPEDashboard') }}">Dashboard</a></li>
                    </ul>
                </li>
                <li id="appraisalsNav">
                    <a href="{{ route('viewPEAppraisalsOverview') }}">
                        <i class='bx bx-file'></i>
                        <span class="link_name">Appraisal</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewPEAppraisalsOverview') }}">Appraisal</a>
                        </li>
                    </ul>
                </li>
                <li id='internalCustomersNav'>
                    <a href="{{ route('viewICOverview') }}">
                        <i class='bx bx-group'></i>
                        <span class="link_name">Internal Customer</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('viewICOverview') }}">Internal Customers</a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Contractual Employee Link -->
            @if (session()->get('user_level') === 'CE')
                <li>
                    <a href="{{ route('viewCEDashboard') }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="link_name">Dashboard</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="viewCEDashboard">Dashboard</a></li>
                    </ul>
                </li>
                <li id='internalCustomersNav'>
                    <a href="{{ route('ce.viewICOverview') }}">
                        <i class='bx bx-group'></i>
                        <span class="link_name">Internal Customer<br>Appraisal</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('ce.viewICOverview') }}">Internal Customers</a></li>
                    </ul>
                </li>
            @endif

            <li>
                <a href="{{ route('viewSettings') }}">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Settings</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="{{ route('viewSettings') }}">Settings</a>
                    </li>
                </ul>
            </li>

            <li>
                <div class="profile-details">
                    <div class="profile-content">
                        <!--<img src="image/profile.jpg" alt="profileImg">-->
                    </div>
                    <div class="name-job">
                        <div class="profile_name text-capitalize">
                            <?php echo "<h6 style='padding-left: 10px;padding-top:7px;'>" . session()->get('full_name') . '</h6>'; ?>
                        </div>
                        <div class="job">
                            <?php echo "<h6 style='padding-left: 10px;font-size:12px'>" . session()->get('title') . '</h6>'; ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link" data-bs-toggle="modal"
                        data-bs-target="#logout-confirmation-modal">
                        <i class='bx bx-log-out'></i>
                    </button>
                </div>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <section class="content-section">
        <div class="content-title">
            <span class="text" id="title-heading">
                @yield('title')
            </span>
        </div>
        <hr class="content-divider">
        <div class="content-body">
            @yield('content')
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="logout-confirmation-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="logout-confirmation-label">Logout Confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?
                    </p>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" href="{{ route('viewLogin') }}">Logout</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>
<script>
    function toggleSubMenu(arrow) {
        let arrowParent = arrow.parentElement.parentElement;
        arrowParent.classList.toggle("showMenu");

        // Adjust padding when submenu is open
        let iconLink = arrow.parentElement;
        let subMenu = arrowParent.querySelector(".sub-menu");
        if (arrowParent.classList.contains("showMenu")) {
            let paddingRight = subMenu.clientWidth - 10;
            iconLink.style.paddingRight = paddingRight + "px";
        } else {
            iconLink.style.paddingRight = "10px"; // Set the default padding when submenu is closed
        }
    }

    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
            toggleSubMenu(e.target);
        });
    }
</script>

</html>
