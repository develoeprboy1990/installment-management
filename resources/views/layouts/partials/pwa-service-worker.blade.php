<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('service-worker.js') }}').catch(function (error) {
                console.error('Service worker registration failed:', error);
            });
        });
    }
</script>
