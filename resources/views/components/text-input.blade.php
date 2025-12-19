@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-700 bg-slate-900 text-slate-100 focus:border-sky-500 focus:ring-sky-500 placeholder-slate-500 rounded-xl shadow-sm']) !!}>