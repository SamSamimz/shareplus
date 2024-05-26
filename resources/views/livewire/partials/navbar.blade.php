<nav class="navbar bg-body-tertiary fixed-top">
    <div class="container">
      <a class="navbar-brand" href="/" wire:navigate>{{__('Share\Plus')}}</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">{{auth()->user()?->username}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link {{request()->routeIs('home') ? 'active' : null}}" href="/" wire:navigate>Home 
                <i class="fas fa-home"></i>
              </a>
              </li>
              <li class="nav-item">
                <a class="nav-link  {{request()->routeIs('profile.show') ? 'active' : null}}" href="{{route('profile.show',auth()->user()->username)}}" wire:navigate>Profile 
                  <i class="fas fa-user"></i>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('savedPost.show')}}" wire:navigate class="nav-link" >Save Posts 
                  <i class="fas fa-bookmark"></i>
                </a>
              </li>
              <li class="nav-item">
                <button wire:click='logoutUser()' class="nav-link">Logout 
                  <i class="fas fa-sign-out-alt"></i>
                </button>
              </li>
            </ul>
        </div>
      </div>
    </div>
  </nav>