<!-- resources/views/layouts/navbar.blade.php -->
<nav class="navbar navbar-expand-lg bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{route('dashboard')}}">Mahalaxmi Flex</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('product.create') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('billing.create') }}">Billing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Orders</a>
                </li>
            </ul>
        </div>
    </div>
</nav>