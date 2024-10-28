{{--@extends('frontend.layouts.app')--}}
@extends('backend.layouts.layout')

@section('content')
    <style>

        /*progrees bar for restructure backup json file*/
        .loader-container {
            /*background: rgba(0, 0, 0, .5);*/

            opacity: 0.75;
            -moz-opacity: 0.75;
            filter: alpha(opacity=75);

            display: flex;
            align-items: center;
            justify-content: center;

            /*position: fixed;*/
            position: relative;

            width: 100%;
            height: 100%;
        }

        .loader {
            z-index: 3;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }


        /* Safari */

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /*for progrees bar*/



    </style>

@php
    //return file uploaded via uploader
    if (!function_exists('uploaded_assetx')) {
    function uploaded_assetx($id)
    {
    if (($asset = \App\Models\Upload::find($id)) != null) {
    return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
    }
    return static_asset('assets/img/bg.jpg');
    }
    }



    $crontaskatts = array(
        'linkkey' => $linkkey,
        'test' => $test,
    );
    $AECmodals['cron'] = $crontaskatts;

@endphp


    <section class="py-4">
    <div class="container text-left">
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="position-absolute" id="particles-js"></div>
                <div class="h-100 bg-cover bg-center py-5 d-flex align-items-center"
                     style="background-image: url({{ uploaded_assetx(get_setting('admin_login_background')) }})">

                    <div class="container main-form">
                        <div class="row">
                            <div class="col-lg-12 col-xl-12 mx-auto">
                                <div class="card text-left">
                                    <div class="card-body">

                                        {{--//3 titlse and logo on top off screen--}}
                                        <div class="mb-5 text-center">
                                            @if(get_setting('system_logo_black') != null)
                                                <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}"
                                                     class="mw-100 mb-4" height="80">
                                            @else
                                                <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mb-4"
                                                     height="80">
                                            @endif
                                            <h1 class="fs-20 fs-md-24 fw-700 text-primary">{{ translate('Welcome My Friend')}}</h1>

                                        </div>

                                        <!-- cronjob progress animation-->
                                        <div class="loader-container">
                                            <div style="display:none;" id="PersianGulf_runcronjobtask" class="loader"></div>
                                        </div>

                                        <!-- cronjob task successfuly Confirmation Text-->
                                        <div class="text-center py-4 mb-0">
                                            <div style="display:none;" id="successfuly">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" class=" mb-3">
                                                    <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                                                        <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18" transform="translate(978 481)" fill="#85b567"/>
                                                        <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                                            <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3" rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                                            <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18" rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Cronjob Backup Executed Successfully')}}</h1>
                                            </div>
                                        </div>

                                        <!-- cronjob html generated for task successfuly-->
                                        <div class="form-group" id="cronjobresult_table"></div>

                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>
</section>
@endsection


@section('script')
    <script type="text/javascript">
        AIZ.plugins.particles();
        {{--var AECmodals = '<?php echo json_encode($AECmodals); ?>';--}}
        {{--var AECmodals = JSON.parse(AECmodals);--}}
        var AECmodals;

        $(document).ready(function(){
            AECmodals = '<?php echo json_encode($AECmodals); ?>';
            AECmodals = JSON.parse(AECmodals);

        });


        // window.onload = submitmabna;
        window.onload = function() {
            // show_animation_cronjob();
            AIZ.plugins.particles();
            AECmodals = '<?php echo json_encode($AECmodals); ?>';
            AECmodals = JSON.parse(AECmodals);
            ShowProgressBarruncronjobtask();
            executecronjob();
        };

        function ShowProgressBarruncronjobtask() {
            var progressbardiv = document.getElementById("PersianGulf_runcronjobtask");
            progressbardiv.style.display = (progressbardiv.style.display !== "none") ? "none" : "block";
        }
        function HideProgressBarruncronjobtask() {
            var progressbardiv = document.getElementById("PersianGulf_runcronjobtask");
            progressbardiv.style.display = (progressbardiv.style.display !== "block") ? "block" : "none";
        }
        function ShowSuccessfulydiv() {
            var successfulydiv = document.getElementById("successfuly");
            successfulydiv.style.display = (successfulydiv.style.display !== "none") ? "none" : "block";
        }

        function executecronjob() {

            var test_param_val;
            var linkkey_param_val;

            if (AECmodals.hasOwnProperty('cron')) {
                test_param_val    = AECmodals.cron.test;
                linkkey_param_val = AECmodals.cron.linkkey;
            } else {
                test_param_val    = $('#test_param').val();
                linkkey_param_val = $('#linkkey_param').val();
            }
            console.debug("test_param_val=>"+test_param_val+"         linkkey_param_val=>"+linkkey_param_val);


            if (isNullOrUndefined(test_param_val) || isNullOrUndefined(linkkey_param_val)) {
                console.log("The value is either undefined or null");
                AIZ.plugins.notify('danger', "{{ translate('At least one backup type must be selected') }}");

            } else {
                console.debug('test=>' + test_param_val+'     linkkey=>'+linkkey_param_val);
                $.post('{{ route('backups.cronjobbackup2') }}', {
                    _token: '{{ csrf_token() }}',
                    linkkey: linkkey_param_val,
                    test: test_param_val,
                }, function (data) {

                    data = JSON.parse(data);
                    console.debug(data);

                    if(data.status == '1'){
                        ShowSuccessfulydiv();
                        HideProgressBarruncronjobtask();
                        AIZ.plugins.notify('success', '{{ translate('Cronjob Backup Executed Successfully') }}');
                        $('#cronjobresult_table').html(data.html);
                    }
                    else{
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                        //$('#cronjobresult_table').html(null);
                    }

                });
            }

        }

        function isNullOrUndefined(value) {
            return value === undefined || value === null || value === '' || value.length === 0;
        }


    </script>
@endsection

