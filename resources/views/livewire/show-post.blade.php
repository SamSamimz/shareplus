<div class="container">
    <div class="col-md-8 mx-auto">

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
              <span style="text-decoration: underline" wire:click.prevent="$dispatch('openModal')"> {{$post->likes->count(). ' ' . Str::plural('like',$post->likes->count())}}</span>
             
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


    <div class="bg-light p-3 mb-2 rounded">
        <h6 class="py-2">Leave a Comment :</h6>
        @if ($post->comments->count() == 0)
            <div class="alert alert-warning">Be the first one who comment.</div>
        @endif
        {{-- Comment Form --}}
        <div>
            <form wire:submit.prevent='addComment({{$post}})'>
                <div class="mb-3">
                    <textarea wire:model='text' class="form-control" placeholder="Write your comment." cols="30" rows="2" autofocus></textarea>
                    @error('text')
                        <p class="text-danger">{{$message}}</p>
                    @enderror
                </div>
                    <button type="submit" class="btn btn-primary">Post comment</button>
            </form>
        </div>
        {{-- Comment Show --}}
        <div class="pt-3">
            <h6 class="py-2">All Comments :</h6>
            @foreach ($post->comments as $comment)
            <div class="d-flex align-items-center gap-2 mb-2">
              <div>
                <img class="comment-author-img"  src="{{$comment->user->profile->image ? asset('storage/'.$comment->user->profile->image) : asset('noimage1.jpg')}}" alt="">
              </div>
              <div class="d-flex align-items-center gap-4 bg-white rounded p-2">
                <div>
                  <a class="text-black fw-bold" href="{{route('profile.show',$comment->user->username)}}" wire:navigate>{{$comment->user->name}}</a>
                  <div>{{$comment->text}}</div>
                </div>
                  @if ($this->postAuthor($post) || $this->commentAuthor($comment))
                  <div class="dropdown">
                    <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                      @if ($this->commentAuthor($comment))
                      <li><a class="dropdown-item">Edit</a></li>
                      @endif
                      <li wire:click='deleteComment({{$comment}})'><a class="dropdown-item">Delete</a></li>
                    </ul>
                  </div>
                  @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>


    
  <!-- Modal -->
  <div wire:ignore.self class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="postModalLabel">People who likes</h1>
          <button type="button" class="btn-close" wire:click.prevent="$dispatch('closeModal')"></button>
        </div>
          <div class="modal-body max-h-300">
            <div class="bg-light rounded">
                @foreach ($post->likes as $liker)
                <div class="p-2 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <img width="30" height="30" style="border-radius: 50%" src="{{$liker->user->profile->image ? asset('storage/'.$liker->user->profile->image) : asset('noimage1.jpg')}}">
                        <div class="">{{$liker->user->name}}</div>
                    </div>
                    <div>
                        <a class="btn-transparent" href="{{route('profile.show',$liker->user->username)}}" wire:navigate>View Profile</a>
                    </div>
                </div>
                @endforeach
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click.prevent="$dispatch('closeModal')">Close</button>
          </div>
      </div>
    </div>
  </div>


</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
      Livewire.on('openModal',function() {  
        $('#modal').modal('show');
      });
      Livewire.on('closeModal',function() {  
        $('#modal').modal('hide');
        $('.modal-backdrop').remove();
    });
});
</script>
</div>