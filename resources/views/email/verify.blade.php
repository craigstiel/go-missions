<!DOCTYPE html>
<html lang="en-US">
<head>
    <style>
        @media screen and (max-width: 600px) {
            .body {
                max-width: 100% !important;
                font-family: "Montserrat", sans-serif;
                background-color: #55107f;
                background-image: url(https://i.pinimg.com/564x/87/b8/e4/87b8e43dbe480299d3a8d21494c834b7.jpg);
            }

            .pad {
                padding: 15px;
            }

            .inside {
                background: white;
                padding: 7px;
            }

            h5 {
                text-align: center;
                font-size: 16px;
                margin-bottom: 5px;
                text-transform: uppercase;
                font-weight: bold;
                color: #55107f;
            }

            h3 {
                text-align: center;
                font-size: 18px;
                margin-bottom: 10px;
                text-transform: uppercase;
                font-weight: bold;
                color: white;
            }

            p {
                font-size: 13px;
                line-height: 1.5em;
            }

            .a {
                background-color: #740094;
                border: none;
                color: white !important;
                padding: 7px 18px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 13px;
                margin-left: 3%;
                cursor: pointer !important;
            }
        }
        @media screen and (min-width: 600px) {
            .body {
                width: 70% !important;
                font-family: "Montserrat", sans-serif;
                background-color: #55107f;
                margin-left: 10%;
                background-image: url(https://i.pinimg.com/564x/87/b8/e4/87b8e43dbe480299d3a8d21494c834b7.jpg);
            }

            h5 {
                text-align: center;
                font-size: 2em;
                margin-bottom: 5px;
                text-transform: uppercase;
                font-weight: bold;
                color: #55107f;
            }
            h3 {
                text-align: center;
                font-size: 4em;
                margin-bottom: 10px;
                text-transform: uppercase;
                font-weight: bold;
                color: white;
            }

            p {
                margin: 0 20px 20px;
                font-size: 16px;
                line-height: 1.5em;
            }

            .a {
                background-color: #740094;
                border: none;
                color: white !important;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin-left: 33%;
                cursor: pointer !important;
            }

            .pad {
                padding: 50px;
            }

            .inside {
                background: white;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="body">
<div class="pad">
    <h3>{{ config('app.name') }}</h3>
    <div class="inside">
        <h5>@lang('mail.hello')</h5>
        <p><span>@lang('mail.thank', array('app' => config('app.name')))</span></p>
        <p><span>@lang('mail.please')</span></p>
{{--        <form method="get" action="{{ url('/api/verify/email', $token)}}" style="cursor: pointer !important;">--}}
            <a href="{{ url('/api/verify/email', $token)}}" class="a">@lang('mail.button')</a>
{{--        </form>--}}
    </div>
</div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
