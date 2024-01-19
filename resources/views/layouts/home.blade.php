<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{--
            SEO
            <meta name="description" content="{{ $description }}">
            <meta name="keywords" content="{{ $keywords }}">
        --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title }}</title>
        <link rel="stylesheet" href="/css/home.style.css">
        <style>
            body {
                background: #444;
            }
        </style>
        <script src="/js/universal/submit.js"></script>
    </head>
    <body>
        <div class="loading show" id="loador">
            <div class="spinner">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
                <div class="bar4"></div>
                <div class="bar5"></div>
                <div class="bar6"></div>
                <div class="bar7"></div>
                <div class="bar8"></div>
                <div class="bar9"></div>
                <div class="bar10"></div>
                <div class="bar11"></div>
                <div class="bar12"></div>
            </div>
        </div>
        <main>
            <menu>
                <menuitem>
                    <a href="{!! $major == "index" ? $current : route( "admin.contest.index" ) !!}">
                        比賽
                    </a>
                </menuitem>
                <menuitem>
                    <a href="{!! $major == "create" ? $current : route( "admin.contest.create" ) !!}">
                        建立比賽
                    </a>
                </menuitem>
                @if( $major == "edit" )
                    <menuitem>
                        <a href="{!! $current !!}">更新比賽內容</a>
                    </menuitem>
                @endif
                <menuitem>
                    <script>
                        timer = function() {
                            var lifetime = {!! env( "SESSION_LIFETIME" ) !!};
                            var time = 0;       //  The default time of the timer
                            var status = 0;     //    Status: timer is running or stoped
                            var timer_id;       //    This is used by setInterval function
                            var currentTime;

                            var hours = false;
                            if( lifetime > 60 ) {
                                hours = true;
                            }
                            this.start = function() {
                                if( status == 0 ) {
                                    status = 1;
                                    timer_id = setInterval(
                                        function() {
                                            var now = new Date().getTime();
                                            time = ( currentTime - now ) / 1000;
                                            if( time <= 0 ) {
                                                this.stop();
                                                window.location.reload();
                                            } else {
                                                generateTime();
                                            }
                                        }, 1000
                                    );
                                }
                            }

                            //  Same as the name, this will stop or pause the timer ex. timer.stop()
                            this.stop =  function() {
                                if( status == 1 ) {
                                    status = 0;
                                    clearInterval( timer_id );
                                }
                            }

                            // Reset the timer to zero or reset it to your own custom time ex. reset to zero second timer.reset(0)
                            this.restart = function( startTime ) {
                                var startTime = new Date( startTime ).getTime();
                                currentTime = new Date( startTime + lifetime * 60 * 1000 ).getTime();
                                this.stop();
                                this.start();
                            }

                            function generateTime() {
                                var second = Math.floor( time ) % 60;
                                var minute = Math.floor( time / 60 ) % 60;

                                second = (second < 10) ? "0" + second : second;
                                minute = (minute < 10) ? "0" + minute : minute;

                                displayTime = minute + ":" + second;

                                if( hours == true ) {
                                    var hour = Math.floor( time / 60 / 60 ) % 24;
                                    hour = hour < 10 ? "0" + hour : hour;
                                    displayTime = hour + ":" + displayTime;
                                }
                                document.getElementById("logoutCountdown").innerHTML = displayTime;
                            }
                        }
                        window.onload = function() {
                            logoutTimer = new timer();
                            logoutTimer.restart( "{!! date( "Y-m-d H:i:s" ) !!}" );
                        }
                    </script>
                    <a href="{!! route( "login" ) !!}">
                        登出 <span id="logoutCountdown">{!! env( "SESSION_LIFETIME" ) > 60 ? "00:00:00" : "00:00" !!}</span>
                    </a>
                </menuitem>
            </menu>
            <article>
                @yield( "content" )
            </article>
        </main>
        <footer>
            <p>Alta &copy; 1998 - {{ date("Y") }},  All RIghts Reserved</p>
        </footer>
        @php
            if( Session::has( "message" ) ) {
                $scriptFooter .= '<script>alert("' . Session::get( "message" ). '");</script>';
                Session::forget( "message" );
            }
        @endphp
        {!! $scriptFooter !!}
        <script>
            const loador = document.getElementById( "loador" );
            openLoader = function() {
                loador.className = "loading show";
            }
            closeLoader = function() {
                loador.className = "loading";
            }
            closeLoader();
        </script>
    </body>
</html>
