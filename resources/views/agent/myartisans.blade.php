@extends('agent/dashlay')
@section('title','My artisans')
@section('myartA','active')
@section('body')
     
      <div class="container-fluid scroll py-5 mt-5">
        <h3 class="text-center">
          My Artisans
        </h3>
        <div class="container">
            <div class="table-responsive">
                <table class="table data">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Company name</th>
                            <th>Slug</th>
                            <th>Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myartisans as $artisan)
                            <tr>
                                <td>{{$artisan->id}}</td>
                                <td>{{$artisan->companyname}}</td>
                                <td>{{$artisan->slog}}</td>
                                <td><a href="/agent/myartisans/{{$artisan->slog}}"><i class="fas fa-eye fa-2x"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        
    </div>
      

  
  
    <script>
        $(document).ready(function () {
            $('.data').DataTable();
        });
    </script>
            
   
@endsection