<?php echo "```"; ?>

{{ strtoupper($judul_notifikasi) }}

@foreach ($data as $label => $value)
{{ str_pad($label, 12) }}: {!! $value !!}
@endforeach

{{ $footer ?? 'Silakan cek sistem untuk informasi lebih lanjut.' }}

<?php echo "```"; ?>
