<button onclick="goBack()" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left"></i> Retour
</button>

@push('scripts')
    <script>
        function goBack() {
            if (document.referrer) {
                window.location.href = document.referrer;
            } else {
                window.location.href = "{{ route('dashboard') }}";
            }
        }
    </script>
@endpush
