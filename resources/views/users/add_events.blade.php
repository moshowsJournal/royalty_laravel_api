@extends('layout.default')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <h5 class="title">Events</h5>
                </div>
                <div class="card-body">
                    @include('elements.flash-message')
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                            <label>*Title</label>
                            <input name="title" type="text" class="form-control" placeholder="Enter Title" value="{{$edit_event ? $edit_event->title : ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{$edit_event ? '' : '*'}}Image <small>(Accepted formats are png, jpg and png with max size of 5mb)</small></label>
                            <input name="event_photo" type="file" class="form-control" placeholder="Select Image" {{$edit_event ? '' : 'required'}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label>*Description</label>
                            <textarea name="description" rows="4" cols="80" class="form-control" placeholder="Here can be your description" required>{{$edit_event ? $edit_event->description : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-md" type="submit">SAVE</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    
    </div>
    @if(count($events) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Events List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                        <th class="text-center">
                            SN
                        </th>
                        <th class="text-center">
                            Image
                        </th>
                        <th class="text-center">
                            Title
                        </th>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($events as $event)
                                <tr>
                                    <td class="text-center">
                                        {{$counter++}}
                                    </td>
                                    <td class="text-center">
                                        <div class="event-image-container">
                                            <img src="{{$event->image ? $event->image : env('STORAGE_PATH').'assets/img/placeholder.png'}}" />
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{strtoupper($event->title)}}
                                    </td>
                                    <td class="text-center"> 
                                        <a href="/add_events/{{base64_encode($event->id)}}"><span class="fa fa-edit"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>    
        </div>
    @endif
@endsection