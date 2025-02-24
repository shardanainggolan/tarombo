<!DOCTYPE html>
<html 
    lang="id-ID" 
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" 
    dir="ltr"
    data-theme="theme-semi-dark" 
    data-assets-path="../../admin/"
    data-template="vertical-menu-template-semi-dark">

@include('admin.layouts.head')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('admin.layouts.sidebar')

            <div class="layout-page">

                @include('admin.layouts.topbar')
                
                <div class="content-wrapper">

                    @yield('content')
                    
                    @include('admin.layouts.footer')

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div>

    @include('admin.layouts.scripts')

</body>

</html>