
                <footer class="footer">
                    @php
                        $terms = DB::table('terms_conditions')->first();
                        $privacy = DB::table('privacy_policies')->first();
                        $about = DB::table('about_us')->first();
                        $admin = DB::table('users')->where('type', 'SuperAdmin')->first();
                        $site = DB::table('site_settings')->first();
                    @endphp

                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <!-- Left: Links -->
                            <div class="col-sm-4">
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#termsModal">Term & Conditions</a> |
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a> |
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#aboutModal">About Us</a>
                            </div>

                            <!-- Middle: Mobile & WhatsApp -->
                            <div class="col-sm-4 text-center">
                                @if($admin)
                                    <i class="bx bx-phone"></i> {{ $admin->mobile_no ?? '' }} &nbsp;
                                    <i class="bx bxl-whatsapp"></i> {{ $admin->whatsapp_no ?? '' }}
                                @endif
                            </div>

                            <!-- Right: Copyright -->
                            <div class="col-sm-4 text-sm-end d-none d-sm-block">
                                {{ $site->copyright ?? '© '.date('Y').' Your Company' }}
                            </div>
                        </div>
                    </div>
                </footer>

                <!-- Terms Modal -->
                <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {!! $terms->content ?? 'No content available' !!}
                    </div>
                    </div>
                </div>
                </div>

                <!-- Privacy Policy Modal -->
                <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {!! $privacy->content ?? 'No content available' !!}
                    </div>
                    </div>
                </div>
                </div>

                <!-- About Us Modal -->
                <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="aboutModalLabel">About Us</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {!! $about->content ?? 'No content available' !!}
                    </div>
                    </div>
                </div>
                </div>


            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

    

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <!-- apexcharts -->
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- dashboard init -->
        <script src="{{ asset('assets/js/pages/dashboard.init.js')}}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js')}}"></script>
    </body>


</html>