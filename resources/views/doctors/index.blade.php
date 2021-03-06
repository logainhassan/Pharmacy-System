@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>DoctorsTables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">DataTable</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->


    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">DataTable with default features</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Avatar Image</th>
                    <th>Email</th>
                    
                    <th>national id</th>
                    <th>created at</th>
                    <th>is banned </th>
                    <th>Actions</th>
                  </tr>
                </thead>
                  <tbody>
                  @foreach($doctors as $doctor)
                
                  <tr>
                    <td>
                    <a href="{{ route('doctors.show',['doctor'=>$doctor->id]) }}">{{ $doctor->user->name }}</a>
                    </td>
                    <td>
                    @if($doctor->user->image)
            
                <a href="{{ route('doctors.show',['doctor'=>$doctor->id]) }}">        
            <img src="{{asset('storage/'.$doctor->user->image)}}" alt="" height="50" width="50" >
            </a>
            @endif
                    </td>
                    <td>
                    {{ $doctor->user->email }}
                    </td>
                    
                    
                    
                    <td>{{ $doctor->user->national_id }}</td>
                    <td> {{ $doctor->created_at }}</td>
                    <td>
                    {{ $doctor->user->banned_at }}
                    </td>
                    
                    <td>
                    @if ($doctor->user->isNotBanned())                
                      <a  href="{{ route('doctors.banned',['doctor'=>$doctor->id]) }}" class="btn btn-danger "  >Ban</a>
                         @else
                                         
                      <a  href="{{ route('doctors.banned',['doctor'=>$doctor->id]) }}" class="btn btn-primary " >Unban</a>
                      @endif
                    <a href="{{ route('doctors.edit',['doctor'=>$doctor->id]) }}" class="btn btn-success">Edit</a>
                    
                    <form action="{{ route('doctors.destroy',['doctor'=>$doctor->id]) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button  class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this doctor ?')">Delete</button>
                      </form>
                                                
                    </td>
                    
                  </tr>
                  @endforeach
                  
                  </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

@endsection
