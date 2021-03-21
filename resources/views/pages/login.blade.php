@extends('layout.login')
@section('content')
    <div class="content">
        <div class="row">
          <div id="login-form-container" class="col-md-4">
            <div class="logo">
                <img src=""/>
            </div>
            <div class="card"  id="demo-card">
              <div class="card-header pl-0">
                <h5 class="title text-white">Login</h5>
              </div>
              <div class="card-body">
                <form method="post" id="login-form">
                    @csrf
                  <div class="row">
                    <div class="col-md-12 px-1">
                        @include('elements.flash-message')
                        <div class="form-group">
                            <label class="text-white">Email Address</label>
                            <input name="email" type="email" class="form-control" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="col-md-12 px-1">
                        <div class="form-group">
                            <label class="text-white">Password</label>
                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>
                    <!-- <div class="col-md-12 px-1">
                        <div class="form-group">
                            <label class="text-white">Full Name</label>
                            <input name="full_name" type="text" class="form-control" placeholder="Full Name" required>
                        </div>
                    </div> -->
                    <div class="col-md-12 p-0">
                        <button type="submit" class="btn btn-primary btn-block btn-round">
                                Login
                        </button>
                    <div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection