@extends('layout.main')
@section('content')
<br/><br/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css" type="text/css">
<div class="container">
    <div class="row">
        <div class="col-md-12">
        
            <div class="card">
                <div class="card-header">Add Years</div>

                <div class="card-body">
                    
                    <form action="{{url('submit_year')}}" method="POST">
                        @csrf
                          <div class="form-group">
                            <label for="exampleInputEmail1">Years</label>
                            <input type="text" name="years" class="form-control"/>
                          </div>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    
                </div>
            </div>
        
            <hr/>
            <div class="card">
                <div class="card-header">Years List</div>

                <div style="padding:50px;" class="card-body">
                    
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="not-exported"></th>
                                <th>Year</th>
                                <th>Current Year</th>
                                <th class="not-exported">{{trans('file.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($years as $key=>$user)
                            <tr data-id="{{$user->id}}">
                                <td>{{$user->id}}</td>
                                <td>{{ $user->year }}</td>
                                <td>{{ $user->current_year}}</td>
                                <td>
                                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$user->id}}">
                                   <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                                    </button>
                                    
                                    <a href="{{url('select_year',$user->year)}}" type="button" class="btn btn-success"> <i class="fa fa-check" aria-hidden="true"></i> Select Year </a>
                                    
                                    <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{$user->year}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                                    <form action="{{url('update_year')}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $user->id }}" class="form-control"/>
                                                      <div class="form-group">
                                                        <label for="exampleInputEmail1">City Name (English)</label>
                                                        <input type="text" name="city_name_english" value="" class="form-control"/>
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="exampleInputEmail1">City Name (Urdu)</label>
                                                        <input type="text" name="city_name_urdu" value="" class="form-control"/>
                                                      </div>
                                                      <button type="submit" class="btn btn-primary">Submit</button>
                                                    </form>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                </td>
                            </tr>
                            <!-- Modal -->
                                                    
                                                    
                            @endforeach
                        </tbody>
                    </table>
    
    
                   
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>
@endsection
