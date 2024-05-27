<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu"
                aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="./"><img src="admin/images/logo.png" alt="Logo"></a>
            <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="{{route('dashboard')}}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li>
                    <a href="{{route('category')}}"> <i class="menu-icon fa fa-dashboard"></i>Category</a>
                </li>
                <li>
                    <a href="{{route('act')}}"> <i class="menu-icon fa fa-dashboard"></i>Legislation</a>
                </li>
                {{-- <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Acts</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="{{route('act')}}">Add Act</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="{{route('chapter')}}">Add Chapter</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="{{route('section')}}">Add Section</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="{{route('sub-section')}}">Add Sub Section</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="#">Order</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="#">Article</a></li>
                      
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Rules</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Regulation</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Guidelines</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Orders</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Schemes</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Notification</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Ministry</a>
                            <ul class="sub-menu children dropdown-menu">
                                <li><i class="fa fa-angle-double-right"></i><a href="">Departments</a></li>
        
                            </ul>
                        </li>
                             

                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Circular</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Law Comission Report</a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-angle-double-right"></i><a href="">Buttons</a></li>
                        <li><i class="fa fa-angle-double-right"></i><a href="">Badges</a></li>
                    </ul>
                </li>
                </li> --}}
            </ul>
        </div>
    </nav>
</aside>