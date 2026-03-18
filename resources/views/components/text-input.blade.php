@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#185696] focus:ring-[#185696] rounded-md shadow-sm']) }}>
