<!-- GLOBAL IMAGE PREVIEW MODAL (dark blurred backdrop) -->
<div id="image-preview-modal"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
    <div class="relative max-w-4xl" onclick="event.stopPropagation();">
        <div class="flex justify-between items-center mb-2">
            <a id="image-preview-open" href="#" target="_blank"
                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Open in New Tab
            </a>
            <button onclick="closeImagePreview()"
                class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <img id="image-preview-img" src=""
            class="max-w-full max-h-[80vh] object-contain mx-auto bg-white shadow-lg rounded-lg">
    </div>
</div>

<script>
    function openImagePreview(src) {
        document.getElementById('image-preview-img').src = src;
        document.getElementById('image-preview-open').href = src;
        document.getElementById('image-preview-modal').classList.remove('hidden');
    }

    function closeImagePreview() {
        document.getElementById('image-preview-modal').classList.add('hidden');
    }

    // Open preview when clicking any image inside admin main content
    document.addEventListener('click', function(e) {
        if (e.target.closest('#admin-main img')) {
            e.preventDefault();
            openImagePreview(e.target.src);
        }
    });

    // Close preview when clicking outside the image container
    document.getElementById('image-preview-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });

    // Close preview on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('image-preview-modal').classList.contains('hidden')) {
            closeImagePreview();
        }
    });
</script>
