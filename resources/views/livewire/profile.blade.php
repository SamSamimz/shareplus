<div>
    <div class="container">
        <div class="bg-white px-2 py-3">
            {{-- top section --}}
            <div class="d-md-flex align-items-center {{$this->user->id == auth()->user()->id ? 'justify-content-around' : 'justify-content-center gap-5'}} ">
                <div id="profile-pic">
                    <img width="300" height="240" class="rounded" src="{{$this->user->profile->image ? asset('storage/'.$this->user->profile->image) : asset('noimage1.jpg')}}" alt="">
                </div>
                <div id="profile-info">
                    <h5>{{$this->user->name}} ({{$this->username}})</h5>
                    {{-- Num of post --}}
                    <div class="pb-1">
                        <i>{{$this->user->posts->count(). ' ' . Str::plural('post',$this->user->posts->count())}}</i>
                    </div>
                    <p>Intro : 
                        <q>{{$this->user->profile?->bio}}</q>
                    </p>
                    <p><i class="fas fa-briefcase"></i>  Work at : {{$this->user->profile?->work_at ?? 'N/A'}}</p>
                    <p><i class="fas fa-location-dot"></i> From : {{$this->user->profile?->address ?? 'N/A'}}</p>
                    <p><i class="fas fa-calendar"></i> Born in : {{$this->user->profile->birthdate ? date('d-M-Y',strtotime($this->user->profile->birthdate)) : 'N/A'}}</p>
                    {{-- social links --}}
                    <div>
                        <div>
                            <i class="fab fa-facebook"></i>
                            @if ($this->user->profile?->facebook)
                            <a class="text-black" target="_blank" href="{{@$this->user->profile->facebook()}}">{{@$this->user->profile->facebook}}</a>
                            @endif
                        </div>
                        <div>
                            <i class="fab fa-linkedin"></i>
                            @if ($this->user->profile?->linkedin)
                            <a class="text-black" target="_blank" href="{{@$this->user->profile->linkedin()}}">{{@$this->user->profile->linkedin}}</a>
                            @endif
                        </div>
                        <div>
                            <i class="fab fa-github"></i>
                            @if ($this->user->profile?->github)
                            <a class="text-black" target="_blank" href="{{@$this->user->profile->github()}}">{{@$this->user->profile->github}}</a>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($this->user->id === auth()->user()->id)
                <div>
                    <button data-bs-toggle="modal" data-bs-target="#mainModal" class="btn btn-primary">Edit Profile</button>
                </div>
                @endif
            </div>
        </div>
        {{-- <hr> --}}
        <div class="bg-white px-2 py-3">
            <div>
                <div class="col-md-8 mx-auto">
                    @foreach ($posts as $post)
                        <div class="bg-light p-3 mb-2 rounded">
                          <div class="d-flex align-items-center justify-content-between">
                            {{-- Author box --}}
                            <div id="author-box" class="d-flex align-items-center gap-2">
                              <a href="{{route('profile.show',$post->user->username)}}" wire:navigate>
                                <img class="author-image" src="{{$post->user->profile->image ? asset('storage/'.$post->user->profile->image) : asset('noimage1.jpg')}}" alt="">
                              </a>
                              <div>
                                <h6><a class="author-name" href="{{route('profile.show',$post->user->username)}}" wire:navigate >{{$post->user->name}}</a> @if ($post->feeling)
                                  <span class="feeling-text">{{$this->showFeeling($post->feeling)}}</span>
                                @endif </h6>
                                <div class="text-secondary">{{$post->created_at->diffForHumans()}}</div>
                              </div>
                            </div>
                            {{-- right dropdown --}}
                            <div>
                              <div class="dropdown">
                                <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fas fa-ellipsis-vertical fa-lg"></i>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a href="{{route('post.show',[$post->user->username,$post->slug])}}" wire:navigate class="dropdown-item"><i class="fas fa-eye"></i> View Post</a></li>
                                  <li wire:click='savePost({{$post}})'><a class="dropdown-item {{$this->saved($post) ? 'text-primary' : null}}"><i class="fas fa-bookmark"></i> {{$this->saved($post) ? 'Unsave' : 'Save Post'}}</a></li>
                                  <li onclick="copyLink({{json_encode(route('post.show',[$post->user->username,$post->slug]))}})"><a class="dropdown-item"><i class="fas fa-code"></i> Copy Link</a></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <hr>
                          {{-- Post box --}}
                          <div id="post-box" class="p-3 rounded">
                            <div class="pb-2">{{Str::limit($post->caption,100)}}</div>
                            <div class="col-md-8 mx-auto">
                              <a href="{{route('post.show',[$post->user->username,$post->slug])}}" wire:navigate>
                                <img class="post-image" src="{{asset('storage/'.$post->image)}}" alt="">
                              </a>
                            </div>
                            <div class="d-flex align-items-center justify-content-around pt-2">
                              <span> {{$post->likes->count(). ' ' . Str::plural('like',$post->likes->count())}}</span>
                              <span> {{$post->comments->count(). ' ' . Str::plural('comment',$post->comments->count())}}</span>
                            </div>
                            <hr>
                            {{-- Like comment box --}}
                            <div class="d-flex align-items-center justify-content-around pt-2">
                                <i wire:click='likePost({{$post}})' class="fas fa-heart fa-lg {{$this->likedBy($post) ? 'text-primary' : null}}"></i>
                                <i class="fas fa-comment fa-lg"></i>
                            </div>
            
                          </div>
                        </div>
                    @endforeach
                  </div>
            </div>
        </div>
    </div>


    {{--Main  Modal  --}}
  <!-- Modal -->
  <div class="modal fade" id="mainModal"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="mainModalLabel">Edit Profile</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Profile Info --}}
            <div>
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fs-5">Profile</h6>
                    <button wire:click.prevent="$dispatch('openProfileEditModal')" data-bs-dismiss="modal" class="btn text-primary">Edit</button>
                </div>
                <div class="bg-light rounded p-2">
                    <div class="mb-2 text-center">
                        <img width="200" height="200" style="border-radius: 50%" src="{{$this->user->profile->image ? asset('storage/'.$this->user->profile->image) : asset('noimage1.jpg')}}" alt="">
                    </div>
                    <p>Bio : 
                        <q>{{$this->user->profile?->bio}}</q>
                    </p>
                    <p><i class="fas fa-briefcase"></i> Work at : {{$this->user->profile?->work_at ?? 'N/A'}}</p>
                    <p><i class="fas fa-location-dot"></i> From : {{$this->user->profile?->address ?? 'N/A'}}</p>
                    <p><i class="fas fa-calendar"></i> Born in : {{$this->user->profile->birthdate ? date('d-M-Y',strtotime($this->user->profile->birthdate)) : 'N/A'}}</p>
                    {{-- social links --}}
                    <div>
                        <div>
                            <i class="fab fa-facebook"></i>
                            @if ($this->user->profile?->facebook)
                            <a class="text-black" target="_blank" href="{{@$this->user->profile->facebook()}}">{{@$this->user->profile->facebook}}</a>
                            @endif
                        </div>
                        <div>
                            <i class="fab fa-linkedin"></i>
                            @if ($this->user->profile?->linkedin)
                            <a class="text-black" target="_blank" href="{{@$this->user->profile->linkedin()}}">{{@$this->user->profile->linkedin}}</a>
                            @endif
                        </div>
                        <div>
                            <i class="fab fa-github"></i>
                            @if ($this->user->profile?->github)
                            <a class="text-black" target="_blank" href="{{@$this->user->profile->github()}}">{{@$this->user->profile->github}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- Personal Info --}}
            <div>
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fs-5">Personal Info</h6>
                    <button wire:click.prevent="$dispatch('openPersonalinfoEditModal')" class="btn text-primary">Edit</button>
                </div>
                <div class="bg-light rounded p-2">
                    <div>Username : {{$this->user->username}}</div>
                    <div>Full Name : {{$this->user->name}}</div>
                    <div>Email : {{$this->user->email}}</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Information Modal -->
  <div wire:ignore.self class="modal fade" id="profileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="profileModalLabel">Edit Profile Info</h1>
          <button wire:click.prevent="$dispatch('closeProfileEditModal')" type="button" class="btn-close"></button>
        </div>
        <div class="modal-body">
            <h6 class="py-2">Profile</h6>
            <form wire:submit.prevent='profileUpdate()'>
            <div id="profile-pic">
                <img width="200" height="160" class="rounded" src="{{$this->user->profile->image ? asset('storage/'.$this->user->profile->image) : asset('noimage1.jpg')}}" alt="">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Change profile image</label>    
                <input wire:model.live='newImage' type="file" class="form-control">
            </div>
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>    
                <textarea wire:model='bio' class="form-control" id="bio" cols="30" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="work_at" class="form-label">Work at</label>    
                <input wire:model='work_at' type="text" id="work_at" class="form-control">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>    
                <input wire:model='address' type="text" id="address" class="form-control">
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Birthdate</label>    
                <input wire:model='birthdate' type="date" id="birthdate" class="form-control">
            </div>
            <hr>
            <h6>Social links key</h6>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Note : </strong>You must enter your username only. Don't submit the full url.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook</label>    
                <input wire:model='facebook' type="text" id="facebook" class="form-control">
            </div>
            <div class="mb-3">
                <label for="github" class="form-label">Github</label>    
                <input wire:model='github' type="text" id="github" class="form-control">
            </div>
            <div class="mb-3">
                <label for="linkedin" class="form-label">Linkedin</label>    
                <input wire:model='linkedin' type="text" id="linkedin" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Personal Info Modal -->
  <div wire:ignore.self class="modal fade" id="personalInfoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="personalInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Edit Persoanal Info</h1>
          <button wire:click.prevent="$dispatch('closePersonalinfoEditModal')" type="button" class="btn-close"></button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent='personalnfoUpdate()'>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>    
                <input wire:model='username' type="text" id="username" class="form-control">
                @error('username')
                    <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>    
                <input wire:model='name' type="text" id="name" class="form-control">
                @error('name')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input wire:model='email' type="text" id="email" class="form-control">
                @error('email')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          Livewire.on('openProfileEditModal',function() {  
            $('#profileModal').modal('show');
        });
          Livewire.on('closeProfileEditModal',function() {  
            $('#profileModal').modal('hide');
            $('.modal-backdrop').remove();
        });
          Livewire.on('openPersonalinfoEditModal',function() {  
            $('#personalInfoModal').modal('show');
        });
          Livewire.on('closePersonalinfoEditModal',function() {  
            $('#personalInfoModal').modal('hide');
            $('.modal-backdrop').remove();
        });
    });
  </script>

</div>