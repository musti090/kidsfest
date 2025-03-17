<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
       {{-- Anything you want--}}
    </div>
    <!-- Default to the left -->
{{--    <strong>Copyright &copy; --}}{{--2016---}}{{--{{ date('Y') }} --}}{{--<a href="https://massolutions.az" target="_blank">Massolutions</a>--}}{{--.</strong>--}} {{--All rights reserved--}}
<div class="row">
    <div class="col-sm-8">

        <img style="width:190px;height:66px;object-fit: contain" src="{{ asset('storage/downloads/logo.png') }}"  alt="Logo">

    </div>
    <div class="col-sm-3 text-right">
        <strong>www.uif.az</strong>
    </div>
</div>


</footer>
</div>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('backend/assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend/assets/dist/js/adminlte.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $(".gel").removeClass('d-flex');
        $(".gel").addClass('d-none');
        $(".umumi-getir").removeClass('d-flex');
        $(".umumi-getir").addClass('d-none');
        $(".yuklenir").click(function(){
            $(".wrapper").hide();
            $(".gel").removeClass('d-none');
            $(".gel").addClass('d-flex');
        });
        $(".umumi-yuklenir").click(function(){
            $("section").hide();
            $(".umumi-getir").removeClass('d-none');
            $(".umumi-getir").addClass('d-flex');
        });
    });
</script>
@stack('customJs')
</body>
</html>




