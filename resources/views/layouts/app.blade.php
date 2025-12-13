<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microsass Dashboard</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    
  
    
</head>
<body>

<div class="page-layout">

    <div class="sidebar-placeholder"></div>
    <x-sidebar />
    
    <main class="dashboard-container">
        <x-header />
        <div class="scrollable-content">
          @yield('content')
        </div>
    </main> 
</div>

<x-footer />

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RMR1I5W2TOWUv+IqD0sQ4nFznhlT2w65XW/f0G5w4cM6bC9g0mF" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTC0wYlKqVnIqP6f5xG5l/zF0G5g5jT5s6k5t3t0aB5i1g0p4f" crossorigin="anonymous"></script>

<script src="{{ asset('assets/js/dashboard.js') }}"></script>
{{-- Dans layouts/app.blade.php (fin du <body>) --}}

{{-- 🚨 AJOUT 2 : JQUERY (Doit rester le premier) --}}
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

{{-- ✅ AJOUT 3 : POPPER.JS (INTÉGRITÉ SUPPRIMÉE) ✅ --}}
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

{{-- ✅ AJOUT 4 : JAVASCRIPT de Bootstrap (INTÉGRITÉ SUPPRIMÉE) ✅ --}}
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

{{-- Votre JS Personnalisé --}}
<script src="{{ asset('assets/js/dashboard.js') }}"></script>




</body>
</html>