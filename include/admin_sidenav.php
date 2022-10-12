
<?php 
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $path = parse_url($actual_link);
    $current_folder = basename(trim($path['path'],  basename($path['path']) ));
?>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <p class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </p>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a 
                        href="<?php 
                            if ($current_folder == 'inpatient'):
                                echo "index.php";
                            else:
                                switch ($current_folder) {
                                    case 'bed':
                                        echo "../../inpatient/index.php";
                                        break;
                                    case 'value':
                                        echo "";
                                        break;
                                    
                                    default:
                                        echo "../inpatient/index.php";
                                        break;
                                }
                            endif;
                        ?>" class="nav-link align-middle px-0">
                        <i class="far fa-procedures"></i> <span class="ms-1 d-none d-sm-inline">Inpatient</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="nav-link px-0 align-middle">
                        <i class="fa-solid fa-person"></i> <span class="ms-1 d-none d-sm-inline">Outpatient</span> </a>
                    </li>

                    <li>
                        <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                        <i class="fa-regular fa-gear"></i> <span class="ms-1 d-none d-sm-inline">Setup</span> </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                            <li class="w-100">
                                <a href=<?php 
                                    if ($current_folder == 'bed'):
                                        echo "index.php";
                                    else:
                                        switch ($current_folder) {
                                            case 'value':
                                                echo "";
                                                break;
                                            
                                            default:
                                                echo "../setup/bed/index.php";
                                                break;
                                        }
                                    endif;
                                ; ?> class="nav-link px-0"> 
                                    <span class="d-none d-sm-inline">
                                        <i class="fa-solid fa-play" style = "font-size: .7rem;"></i> Bed
                                    </span>
                                </a>
                            </li>
                            
                        </ul>
                    </li>

                    <li>
                        <a href="<?php 
                        if ($current_folder == 'admin'):
                            echo "logout.php";
                        else:
                            switch ($current_folder) {
                                case 'bed':
                                    echo "../../admin/logout.php";
                                    break;
                                case 'value':
                                    echo "";
                                    break;
                                
                                default:
                                    echo "../admin/logout.php";
                                    break;
                            }
                        endif;
                        ?>" class="nav-link px-0 align-middle">
                        <i class="fa-sharp fa-solid fa-power-off"></i> <span class="ms-1 d-none d-sm-inline">Logout</span> </a>
                    </li>
                    <hr style="color:red;">
                </ul>
            </div>
        </div>