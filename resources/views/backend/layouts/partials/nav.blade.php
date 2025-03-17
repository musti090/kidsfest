<nav class="main-header navbar navbar-expand navbar-dark navbar-light layout-navbar-fixed">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        {{--    <li class="nav-item d-none d-sm-inline-block">
                <a href="https://akaf.az/" target="_blank" class="nav-link">Sayta qayıt</a>
            </li>--}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
    {{-- <li class="nav-item">
         <a class="nav-link" data-widget="navbar-search" href="#" role="button">
             <i class="fas fa-search"></i>
         </a>
         <div class="navbar-search-block">
             <form class="form-inline">
                 <div class="input-group input-group-sm">
                     <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                     <div class="input-group-append">
                         <button class="btn btn-navbar" type="submit">
                             <i class="fas fa-search"></i>
                         </button>
                         <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                             <i class="fas fa-times"></i>
                         </button>
                     </div>
                 </div>
             </form>
         </div>
     </li>--}}

    {{--    @php
            $questions = \App\Models\Question::where('status',0)->where('read_at',null)->orderBy('created_at','desc')->get();
            $questionCount = $questions->count();
        @endphp
        <!-- Messages Dropdown Menu -->
            @role('admin|developer')
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">{{ $questionCount }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @forelse( $questions as $question)
                        @if( $loop->index == 5) @break @endif
                        <a href="{{ route('backend.questions.show',$question->id) }}" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">

                                    <img src="{{ asset('backend/assets/img/add-image-icon-png-15.jpg') }}" alt=""
                                         class="img-size-50 mr-3 img-circle">

                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        @if(!empty( $question->fullName))
                                            {{ $question->fullName }}
                                        @endif
                                    </h3>
                                    <p class="text-sm">{{ \Illuminate\Support\Str::limit($question->message,25) }}</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ $question->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                    @empty
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-sm"> Not a new message</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforelse
                    <a href="{{ route('backend.questions.index') }}" class="dropdown-item dropdown-footer bg-primary">See All Questions</a>
                </div>
            </li>
            @endrole--}}
    <!-- Notifications Dropdown Menu -->
        {{--   <li class="nav-item dropdown">
               <a class="nav-link" data-toggle="dropdown" href="#">
                   <i class="far fa-bell"></i>
                   <span class="badge badge-warning navbar-badge">15</span>
               </a>
               <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                   <span class="dropdown-header">15 Notifications</span>
                   <div class="dropdown-divider"></div>
                   <a href="#" class="dropdown-item">
                       <i class="fas fa-envelope mr-2"></i> 4 new messages
                       <span class="float-right text-muted text-sm">3 mins</span>
                   </a>
                   <div class="dropdown-divider"></div>
                   <a href="#" class="dropdown-item">
                       <i class="fas fa-users mr-2"></i> 8 friend requests
                       <span class="float-right text-muted text-sm">12 hours</span>
                   </a>
                   <div class="dropdown-divider"></div>
                   <a href="#" class="dropdown-item">
                       <i class="fas fa-file mr-2"></i> 3 new reports
                       <span class="float-right text-muted text-sm">2 days</span>
                   </a>
                   <div class="dropdown-divider"></div>
                   <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
               </div>
           </li>--}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
                <i class="	fa fa-caret-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @role('developer|superadmin')
                <a href="{{ route('backend.users.edit',auth()->user()->id) }}" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">

                        @php
                            $user = auth()->user()->avatar
                        @endphp

                        <img style="width:55px;height:55px;" src="{{ asset($user)}}" class="img-circle elevation-2"
                             alt="İstifadəçinin şəkli">
                        <div class="media-body text-center">
                            <h2 class="dropdown-item-title ">
                                @if( auth()->check())
                                    {{ auth()->user()->name }}
                                @endif
                            </h2>

                            @if( auth()->check())
                                @forelse ( auth()->user()->roles as $role)
                                    <span class="badge @if($role->name == 'developer') badge-success
                                                             @elseif($role->name == 'superadmin') badge-info
                                                             @else
                                        badge-secondary
@endif  ">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @empty
                                    <span class="badge badge-danger">
                                         Not assigned
                                    </span>
                                @endforelse
                            @endif

                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>
                                @if( Auth::check())
                                    {{ date('d-m-Y', strtotime(auth()->user()->created_at))}}
                                @endif
                            </p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                @endrole

               @role('admin')
                <a href="javavoid:0" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body text-center">
                            <h2 class="dropdown-item-title ">
                                @if( auth()->check())
                                    {{ auth()->user()->username }}
                                @endif
                            </h2>
                        </div>
                    </div>
                </a>

               @endrole
                <div class="dropdown-divider"></div>
                {{--<a href="{{ route('backend.users.edit',auth()->user()->id) }}" class="dropdown-item dropdown-footer">Edit my profile</a>--}}
                {{--<a href="#" class="dropdown-item dropdown-footer">Home</a>--}}
                <button onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        class="dropdown-item dropdown-footer bg-primary">
                    <i class="fas fa-sign-out-alt mr-1"></i>Çıxış
                </button>
                <form id="logout-form" action="{{ route('backend.logout.submit') }}" method="post"
                      style="display: none">
                    @csrf
                </form>
            </div>
        </li>
        {{--  <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                 --}}{{-- <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image">--}}{{--
                  <span class="hidden-xs">{{ Auth::guard('admin')->user()->username }}</span>
              </a>
              <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                     --}}{{-- <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image">--}}{{--

                      <p>
                          {{ Auth::guard('admin')->user()->name }} - {{ Auth::guard('admin')->user()->roles->pluck('name') }}
                          <small>{{ \Carbon\Carbon::parse(Auth::guard('admin')->user()->created_at)->formatLocalized('%d %B, %Y')  }}</small>
                      </p>
                  </li>

                  <li class="user-footer">
                      <div class="pull-left">
                          <a href="#" class="btn btn-default btn-flat">Profile</a>
                      </div>
                      <div class="pull-right">
                          <a href="" class="btn btn-default btn-flat">Sign out</a>
                      </div>
                  </li>
              </ul>
          </li>--}}
        {{-- <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>--}}
    </ul>
</nav>
