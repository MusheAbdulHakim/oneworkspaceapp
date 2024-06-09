<div class="favorite-list-item">
    @if(!empty($user->avatar))
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
             style="background-image: url('{{ get_file($user->avatar) }}');">
        </div>
    @else
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
             style="background-image: url('{{ get_file('uploads/users-avatar/avatar.png') }}');">
        </div>
    @endif
    <p>{{ strlen($user->name) > 5 ? substr($user->name,0,6).'..' : $user->name }}</p>
</div>
