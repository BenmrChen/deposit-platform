@foreach (trans("{$langPrefix}.lines") as $template)
<p>{{ Illuminate\Mail\Markdown::parse(trans($template, $meta)) }}</p>
@endforeach
<br/>
