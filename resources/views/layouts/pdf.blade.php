<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Benchmark</title>
</head>
<style>
    /* @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap'); */

    @page {
        margin: 0px; 
    } 
    
    html { 
        margin: 0px
    }

    body{
        /* background: #424448; */
        padding: 20px;
        margin: 0px;
        box-sizing: border-box;
    }

    .logo{
        margin-left: 38%;
        padding: 5px;
    }

    .container1 {
        /* border: 2px solid #fff; */
    }
    .content{
        padding-top: 10px;
        /* color: #fff; */
    }
    table{
        /* border: 2px solid #fff; */
        width: 100%;
        padding: 0 !important;
    }
    th, td{
        padding: 0 !important;
        text-align: center;
        text-transform: capitalize;
    }

    .header{
        height: 80px;
        width: 100%;
        /* background: #424448; */
    }

</style>
<body>
    <div class="container1">
        <div class="header">
            <div class="logo">
                <img src="assets/images/black-logo.png" alt="">
            </div>
        </div>
        <div class="content">
            {!! $content !!}
        </div>
    </div>
</body>
</html>