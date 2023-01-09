<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.hell.sh/basic.css/1.1/basic.css"
        integrity="sha384-t9jp7agVAouhXYQbh8e3NWEQd74qiwIpXk0BUnxCz3DYK8do1LvzXPtPGRh8BM04" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        p {
            margin: 0
        }
    </style>
</head>

<body>
    <section class="text-center">
        <p style="font-size:150px">@yield('code')</p>
        <p><strong>Whoops</strong> @yield('message')</p>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-outline-light mt-3">
            Kembali ke halaman dashboard
        </a>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>
</body>

</html>
