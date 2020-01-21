@extends('layout')

@section('title','dashboard')
@section('content')
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#"> </a>
  
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link top" href="/logout"><button class="btn btn-outline-secondary signout"><i class="fas fa-power-off"></i><b> Logout</b></button></a>
    </li>
  </ul>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link @yield('retailA')" href="dash">
              <span data-feather="home"><i class="fas fa-money-check-alt"></i></span>
              Retail <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('salesA')" href="mysales">
              <span data-feather="file"><i class="fas fa-chart-pie"></i></span>
              My sales
            </a>
          </li>
          
        </ul>

        
      </div>
    </nav>

    
    
    </div>
</div>
<footer class="footer myfooter text-center text-white py-3">
            copyright©O'bounce Tech 2020
       </footer>

@endsection