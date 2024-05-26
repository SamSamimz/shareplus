<div>
<div class="col-md-8 col-lg-6 mx-auto">
    <div class="bg-white px-3 py-2">
        <h2 class="py-3 text-center">Login Now!</h2>
        @if (session()->has('invalid'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error! </strong> {{session('invalid')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        <form wire:submit.prevent='loginUser()'>
            <div class="mb-3">
                <label for="username" class="form-label">Username or Email</label>
                <input wire:model='username' type="text" id="username" class="form-control" placeholder="example@email">
                @error('username')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input wire:model='password' type="password" id="password" class="form-control" placeholder="****">
                @error('password')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="text-center">
                <button class="col-12 btn btn-warning text-white" type="submit">Login</button>
                <a class="" href="{{route('register')}}" wire:navigate>Dons't have an account?</a>
            </div>
        </form>
    </div>
</div>

</div>