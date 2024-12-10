<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>title</title>
    @livewireStyles
</head>

<body>
    <div class="sidebar">
        @livewire('sidebar')
    </div>
    <div class="content">
        {{ $slot }}
    </div>
    @livewireScripts
</body>

</html>