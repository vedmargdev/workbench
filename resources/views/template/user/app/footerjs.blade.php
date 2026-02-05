{{-- <script type="text/javascript" src="{{ asset_url('assets/public/js/plugins.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/all.js?v=' . time()) }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/inventory.js?v=' . time()) }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/gatepass.js?v=' . time()) }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/hostelmodule.js?v=' . time()) }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/eventmodule.js?v=' . time()) }}"></script> --}}





<script src="https://vedmarg.s3.ap-south-1.amazonaws.com/assets/public/js/plugins.min.js?v=1758005551"></script>
<script src="https://vedmarg.s3.ap-south-1.amazonaws.com/assets/public/js/all.min.js?v=1758005551"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.7/html5-qrcode.min.js"></script>
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>




<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
<!-- Bootstrap JS -->
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> --}}
<!-- Tags Input JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script> --}}



@yield('app-js')

<script type="text/javascript">

    let url = $('input#cdnUrl').val();
    $('.select2').select2();
    $('.chosen').select2();
    $(document).ready(function() {
        $(document).mouseup(function(e) 
        {
            var container = $(".actions--td-col");
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                $(".actions--td-col").find('.btn--fetch-actions').removeClass('active');
                container.find('.action-dropdown').removeClass('show');
            }
            var container = $(".main-header .nav--session");
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                $(".main-header .nav--session").find('.session--dropdown').slideUp();
            }
        });
        if( $('.datatable').length > 0 ) {
            $('.datatable').DataTable({
                order: [[0, 'desc']],
                "lengthMenu": [[20, 50, 100, 200, 500, -1], [20, 50, 100, 200, 500, "All"]]
            });
        }
    });
	
</script>
