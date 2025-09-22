<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Queerworx' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<!-- Font Awesome (latest CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-pVI2Pjhw8sxW7iJByQqVYZJ0Aw4/MhLuq7cfRT13Rg0CmPgVZn0MnTs+jLM5UcLJZ5yFGvdc6KNd7rOU8Xd3DQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="shortcut icon" href="{{ asset('storage/images/fav-icon-square.png') }}" type="image/x-icon">


@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
