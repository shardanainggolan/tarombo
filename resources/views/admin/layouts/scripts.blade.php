<script src="{{ asset('admin/js/jquery.js') }}"></script>
<script src="{{ asset('admin/js/popper.js') }}"></script>
<script src="{{ asset('admin/js/bootstrap.js') }}"></script>
<script src="{{ asset('admin/js/node-waves.js') }}"></script>
<script src="{{ asset('admin/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('admin/js/hammer.js') }}"></script>
{{-- <script src="{{ asset('admin/js/i18n.js') }}"></script> --}}
<script src="{{ asset('admin/js/typeahead.js') }}"></script>
<script src="{{ asset('admin/js/menu.js') }}"></script>

<!-- Vendors JS -->
{{-- <script src="{{ asset('admin/js/apexcharts.js') }}"></script> --}}

<!-- Main JS -->
<script src="{{ asset('admin/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('admin/js/app-ecommerce-dashboard.js') }}"></script>

@stack('scripts')

@if(session('success'))
    <script>
        Swal.fire({
            title: "Sukses",
            text: "{{ session('success') }}",
            icon: "success",
            customClass: {
                confirmButton: "btn btn-primary waves-effect waves-light"
            },
            buttonsStyling: !1,
            timer: 3500
        })
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            title: "Error",
            text: "{{ session('error') }}",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-primary waves-effect waves-light"
            },
            buttonsStyling: !1,
            timer: 3500
        })
    </script>
@endif