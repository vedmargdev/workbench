<aside class="col-lg-2 sidebar border-right left-sidebar">
    <div class="dashboard-sidebar">

        <div class="sidebar-user-provile">
            <div class="user-profile-picture">
                {{-- <a href="{{ route('user') }}">
                    <div class="user--image">
                        @if ($school_logo ?? false)
                            <img src="{{ asset_url(getThumbnailImage($school_logo)) }}" alt="{{ $school_name ?? '' }}" />
                        @else
                            <img class="user-image" style="" src="{{ school_placeholder_logo() }}"
                                alt="{{ $school_name ?? '' }}" />
                        @endif
                    </div>
                    <div class="user--info">
                        <?php $role = strtolower(Auth::user()->role ?? ''); ?>
                        <span class="name-fd">Hi, {{ strtolower(Auth::user()->first_name ?? '') }}</span>
                        <span class="user--role">({{ $role == 'user' ? 'Admin' : $role }})</span>
                    </div>
                </a> --}}

            </div>

            <div class="user-profile-name">
                <span>{{ ucwords($school_name ?? '') }}</span>
            </div>
        </div>

        <?php $routeName = \Request::route()?->getName();
        ?>

        <div class="sidebar-nav">
            <ul class="sidebar-menu">



                <li class="inner-child sidebar--item ">
                    <a role="button" title="Sales">
                        {{-- <img src="{{ asset_url('images/icons/offline.png') }}"> --}}
                        <span class="item--label">Contact Us</span>
                    </a>
                    <ul class="dropdown" style="display: none;margin-left:10px;">

                        <li class="inner-child {{ strtolower($routeName) === 'user.offline-test' ? 'active' : '' }}">
                            <a href="{{ Route::has('contact.create') ? route('contact.create') : '#' }}" title="">
                                <img src="">
                                <span class="item--label">Contact Us</span>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="inner-child sidebar--item ">
                    <a role="button" title="Sales">
                        {{-- <img src="{{ asset_url('images/icons/offline.png') }}"> --}}
                        <span class="item--label">Offline Test</span>
                    </a>
                    <ul class="dropdown" style="display: none;margin-left:10px;">

                        <li class="inner-child {{ strtolower($routeName) === 'user.offline-test' ? 'active' : '' }}">
                            <a href="{{ Route::has('user.offline-test') ? route('user.offline-test') : '#' }}"
                                title="">
                                <img src="">
                                <span class="item--label">Offline Test</span>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="inner-child sidebar--item">
                    <a href="{{ Route::has('logout') ? route('logout') : '#' }}">
                        {{-- <img src="{{ asset_url('images/icons/logout.png') }}"> --}}
                        <span class="item--label">Log Out</span>
                    </a>
                </li>
                <li class="divider sidebar--item"></li>
            </ul>
        </div>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function toggleDropdown(event) {
            event.preventDefault();

            const clickedAnchor = event.currentTarget;
            const clickedItem = clickedAnchor.parentElement; // sidebar--item clicked

            // Find dropdown inside the clicked item
            const dropdown = clickedItem.querySelector('.dropdown');
            if (!dropdown) return; // no dropdown, just return

            const isVisible = dropdown.style.display === 'block';

            // Close sibling dropdowns and remove sibling active classes
            const siblings = Array.from(clickedItem.parentElement.children).filter(
                el => el !== clickedItem && el.classList.contains('sidebar--item')
            );

            siblings.forEach(sibling => {
                sibling.classList.remove('active');
                const siblingDropdown = sibling.querySelector('.dropdown');
                if (siblingDropdown) {
                    siblingDropdown.style.display = 'none';
                }
            });

            // Toggle current dropdown
            if (!isVisible) {
                clickedItem.classList.add('active');
                dropdown.style.display = 'block';
            } else {
                clickedItem.classList.remove('active');
                dropdown.style.display = 'none';
            }
        }

        const sidebarItems = document.querySelectorAll('.sidebar--item > a[role="button"]');
        sidebarItems.forEach(item => {
            item.addEventListener('click', toggleDropdown);
        });

    });
</script>
