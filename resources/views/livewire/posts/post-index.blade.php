<div>
    <div class="create_post row">
        <div class="d-flex align-items-center justify-content-center py-2">
            <button wire:click='openPostModal()' id="cooking_btn" class="btn">
              <div style="font-size: 60px">
                <i class="fas fa-plus"></i>
              </div>
                <div class="py-1">What's cooking into your life?</div>
            </button>
        </div>
    </div>


    {{-- ########  --}}
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
                      <span class="feeling-text">{{$this->getFeeling($post->feeling)}}</span>
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
    {{-- ########  --}}


  <!-- Modal -->
  <div wire:ignore.self class="modal fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="postModalLabel">Create a Post</h1>
          <button type="button" class="btn-close" wire:click='closePostModal()'></button>
        </div>
        <form wire:submit='addPost()'>
          <div class="modal-body">
            <div>
              <div class="mb-3">
                <label for="caption" class="form-label">Caption :</label>
                <textarea wire:model='caption' class="form-control" id="caption" cols="30" rows="2" placeholder="what's going into your mind."></textarea>
                @error('caption')
                    <p class="text-danger">{{$message}}</p>
                @enderror
              </div>
              <div class="mb-3">
                <label for="image" class="form-label">Add Image :</label>
                <input wire:model='image' type="file" class="form-control">
                @error('image')
                <p class="text-danger">{{$message}}</p>
                @enderror
              </div>
              <div class="mb-3">
                <label for="caption" class="form-label">Feeling :</label>
                <select wire:model='feeling' id="feeling" class="form-select">
                  <option value="" selected>Select how you feel now</option>
                  <option value="happy">Happy😀</option>
                  <option value="sad">Sad😥</option>
                  <option value="angry">Angry😡</option>
                  <option value="thankfull">Thankfull🙏</option>
                  <option value="blessed">Blessed😊</option>
                  <option value="excited">Excited😉</option>
                </select>
                @error('feeling')
                <p class="text-danger">{{$message}}</p>
                @enderror
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click='closePostModal()'>Close</button>
            <button type="submit" class="btn btn-primary">Add Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
        document.addEventListener('DOMContentLoaded', function() {
          Livewire.on('openPostModal',function() {  
            $('#postModal').modal('show');
          });
          Livewire.on('closePostModal',function() {  
            $('#postModal').modal('hide');
            $('.modal-backdrop').remove();
        });
    });
  </script>
  <script>
    function copyLink(link) {
        const textarea = document.createElement('textarea');
        textarea.value = link;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('Post link copied.' );
    }
  </script>
</div>