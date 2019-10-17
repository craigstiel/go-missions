<!DOCTYPE html>
<html lang="en-US">
<head>
    <style>
        .body {
            font-family: "Montserrat", sans-serif;
            background-color: #55107f;
            width: 70% !important;
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
            margin-left: 35%;
            cursor: pointer !important;
        }
    </style>
</head>
<body>

<div class="body" >
    <div style="padding: 50px">
        <h3>{{ config('app.name') }}</h3>
        <div style="background: white; padding: 20px;">
            <h5>@lang('mail.reset_password')</h5>
            <p style="text-align: center;"><span>@lang('mail.start_new_pas')</span></p>
            <form method="get" action="{{ url('/#/reset/' . $token)}}" style="cursor: pointer !important;">
                <button class="a">@lang('mail.reset')</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
