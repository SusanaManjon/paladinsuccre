<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Paladín Sucre — Embutidos Artesanales</title>
        <meta name="description" content="Embutidos artesanales de calidad en Sucre, Bolivia. Chorizos, salchichas, jamones y más con tradición desde 2009.">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Google Analytics (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX') }}"></script>
        <script>
            window.GOOGLE_ANALYTICS_ID = "{{ env('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX') }}";
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', window.GOOGLE_ANALYTICS_ID, {
                send_page_view: false
            });
        </script>
        
        <!-- PayPal Integration -->
        <script>
            window.PAYPAL_CLIENT_ID = "{{ env('PAYPAL_CLIENT_ID', 'sb') }}";
        </script>
    </head>
    <body class="font-sans antialiased">
        <div id="app"></div>

        @if(env('TAWK_TO_PROPERTY_ID'))
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/{{ env('TAWK_TO_PROPERTY_ID') }}/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Tawk.to Script-->
        @endif
    </body>
</html>