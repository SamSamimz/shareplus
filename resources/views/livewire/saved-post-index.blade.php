<div class="container">
    <div>
        <div class="col-md-8 mx-auto">
            <div class="bg-light rounded p-2">
                <h5 class="p-2">Saved Post</h5>
                <div>
                    @forelse ($this->posts as $post)
                    <div class="bg-white p-2 mb-2">
                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <img width="80" height="60" class="rounded" src="{{asset('storage/'.$post->post->image)}}" alt="{{$post->post->caption}}">
                                </div>
                                <div>
                                    <a class="text-black" href="{{route('post.show',[$post->post->user->username,$post->post->slug])}}" wire:navigate>
                                        <div class="fs-6 fw-bold">{{Str::limit($post->post->caption,80)}}</div>
                                    </a>
                                    <div class="text-secondary">{{$post->user->name}}</div>
                                </div>
                            </div>
                            <div>
                                <div class="dropdown">
                                    <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                      <i class="fas fa-ellipsis-vertical fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="{{route('post.show',[$post->post->user->username,$post->post->slug])}}" wire:navigate class="dropdown-item"><i class="fas fa-eye"></i> View Post</a></li>
                                      <li wire:click='deleteSavedPost({{$post->post}})'><a class="dropdown-item"><i class="fas fa-bookmark"></i> Unsave</a></li>
                                      <li onclick="copyLink({{json_encode(route('post.show',[$post->post->user->username,$post->post->slug]))}})"><a class="dropdown-item"><i class="fas fa-code"></i> Copy Link</a></li>
                                    </ul>
                                  </div>
                            </div>
                        </div>
                    </div>
                        
                    @empty
                        <div class="alert alert-warning">No saved item.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
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