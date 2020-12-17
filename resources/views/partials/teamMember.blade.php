<div class="col-12 col-md-6 col-xl-3 d-flex flex-row">
    <img
        src={{ asset('profile_images/' . ($profile->image)) }}
        class="rounded-circle z-depth-1-half avatar-pic new-user-image"
        alt="example placeholder avatar"
    />
    <p style="font-weight: 600; margin-right: 0.25rem;">
        {{ $profile->name }},
    </p>
    <p>{{ $profile->username }}</p>
</div>