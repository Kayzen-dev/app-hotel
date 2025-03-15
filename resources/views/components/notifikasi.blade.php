<div x-data="{open: false}"
x-show="open" 
@notify.window="
Toastify({
    text: $event.detail.message,
    duration: 3000,
    destination: 'https://github.com/apvarun/toastify-js',
    newWindow: true,
    close: true,
    gravity: 'top', 
    position: 'right', 
    stopOnFocus: true, 
    style: {
        background: ($event.detail.title === 'success') ? 'linear-gradient(to right, #00b09b, green)' : 'linear-gradient(to right, pink, red)',
    },
}).showToast();
"
>
</div>