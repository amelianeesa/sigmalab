@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label font-weight-bold">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    
    @if($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" rows="3" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}>{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}>
    @endif
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
