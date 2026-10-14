@props(['url']) 
<tr> 
    <td class="header"> 
        <a href="{{ $url }}" style="display: inline-block;"> 
            @if (trim($slot) === 'Laravel') 
            <img src="{{ asset('vendor/img/logo-sf.png') }}" class="logo" alt="San Felipe Logo" style="width: 200px; height:auto; display:block; margin:auto;"> 
            @else {{ $slot }} 
            @endif 
        </a> 
    </td> 
</tr>