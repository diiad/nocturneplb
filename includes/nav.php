<nav>
    <ul>
        <?php if($_SESSION['role'] != 0) { ?>
        <li>
            <a href="stats.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 24 24" fill="white">
                    <path d="M18 18H6" stroke="white" stroke-linecap="round"/>
                    <rect x="7" y="12" width="2" height="6" stroke="#000000" stroke-linejoin="round"/>
                    <rect x="11" y="6" width="2" height="12" stroke="#000000" stroke-linejoin="round"/>
                    <rect x="15" y="9" width="2" height="9" stroke="#000000" stroke-linejoin="round"/>
                    <rect x="7" y="12" width="2" height="6" stroke="#000000" stroke-linejoin="round"/>
                    <rect x="11" y="6" width="2" height="12" stroke="#000000" stroke-linejoin="round"/>
                    <rect x="15" y="9" width="2" height="9" stroke="#000000" stroke-linejoin="round"/>
                    <path d="M18 18H6" stroke="white" stroke-linecap="round"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="users.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25" height="25" fill="white">
                    <path class="st0" d="M326.32,332.67c24.74,0,44.79,20.05,44.79,44.79l-0.03,24.62c2.99,56.03-38.67,84.38-113.43,84.38
                    c-74.45,0-116.88-27.89-116.88-83.41v-25.59c0-24.74,20.05-44.79,44.79-44.79H326.32z M44.79,204.7l112,0
                    c-2.11,8.18-3.23,16.75-3.23,25.59c0,28.55,11.69,54.37,30.54,72.94l4.15,3.87l-2.69-0.03c-7.71,0-15.14,1.24-22.08,3.53
                    c-22.13,7.31-39.41,25.3-45.71,47.87l-0.88,0.02C42.43,358.49,0,330.6,0,275.08v-25.59C0,224.75,20.05,204.7,44.79,204.7z
                    M467.09,204.7c24.74,0,44.79,20.05,44.79,44.79l-0.03,24.62c2.99,56.03-38.67,84.38-113.43,84.38l-4.31-0.04
                    c-6.11-21.85-22.51-39.41-43.65-47.13c-6.02-2.2-12.43-3.6-19.09-4.07l-5.04-0.18l-2.7,0.03c21.27-18.76,34.69-46.22,34.69-76.81
                    c0-8.84-1.12-17.41-3.23-25.59L467.09,204.7z M255.94,153.51c42.41,0,76.78,34.38,76.78,76.78c0,42.41-34.38,76.78-76.78,76.78
                    s-76.78-34.38-76.78-76.78C179.16,187.89,213.53,153.51,255.94,153.51z M115.17,25.54c42.41,0,76.78,34.38,76.78,76.78
                    c0,42.4-34.38,76.78-76.78,76.78s-76.78-34.38-76.78-76.78C38.39,59.92,72.77,25.54,115.17,25.54z M396.71,25.54
                    c42.41,0,76.78,34.38,76.78,76.78c0,42.4-34.38,76.78-76.78,76.78s-76.78-34.38-76.78-76.78C319.92,59.92,354.3,25.54,396.71,25.54
                    z"/>
                </svg>
            </a>
        </li>
        <?php } ?>
        <li>
            <a href="dashboard.php">
                <img src="img/home-icon-silhouette-svgrepo-com.svg" width="25" height="25">
            </a>
        </li>
        <?php if($_SESSION['role'] != 0){ ?>
        <li><a href="paiement.php">
                <img src="img/reshot-icon-heap-money-XB9FLV365E.svg" width="25" height="25" alt="heap money icon" style="filter: invert(1);">
            </a>
        </li>
        <?php } ?>
        <li>
            <a href="profil.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25" height="25" fill="white">
                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/>
                </svg>
            </a>
        </li>
    </ul>


</nav>
