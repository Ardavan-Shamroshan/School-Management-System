@props([
	'icon',
	'tag',
	'type'      => 'info',
    'colors'    => [
        'success'   => 'is-success border-emerald-100',
        'error'     => 'is-danger border-red-100',
        'info'      => 'is-info border-sky-100',
        'warning'   => 'is-warning border-amber-100',
        'primary'   => 'is-primary border-violet-100'
    ],
])

<small {{ $attributes->class(["font-size-normal is-light shadow tag font-semibold border $colors[$type]"]) }}>
    @isset($icon)

        <span><i class="fe fe-{{ $icon }}"></i></span>

    @endisset


    @isset($tag)

            {{ $tag }}

    @endisset

        {{ $slot }}
</small>
