<div>
    <div class="col-md-8 col-lg-6 mx-auto">
        <div class="bg-white px-3 py-2">
            <h2 class="py-3 text-center">Create new account!</h2>
            <form wire:submit.prevent='registerUser()'>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input wire:model='name' type="text" id="name" class="form-control" placeholder="full name">
                @error('name')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username </label>
                <input wire:model='username' type="text" id="username" class="form-control" placeholder="username">
                @error('name')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email </label>
                <input wire:model='email' type="text" id="email" class="form-control" placeholder="email@mail.com">
                @error('email')
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
            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input wire:model='password_confirmation' type="password" id="cpassword" class="form-control" placeholder="****">
                @error('password_confirmation')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="text-center">
                <button class="col-12 btn btn-warning text-white" type="submit">Register</button>
                <a class="" href="{{route('login')}}" wire:navigate>Already have an account?</a>
            </div>
        </form>
    </div>
</div>
</div>