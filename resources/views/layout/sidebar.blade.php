<div class="vertical-menu">

    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Tork</li>
                    <li>
                        <a href="{{ url('') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span key="t-dashboards">Dashboard</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fa-solid fa-building"></i>
                            <span key="t-bank">Employee</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('employee.index') }}" key="t-products">Employee List</a>
                                </li>
                                <li>
                                    <a href="{{ route('employee.create') }}" key="t-products">Add New Employee</a>
                                </li>
                        </ul>
                    </li>

            </ul>
        </div>
    </div>
</div>
