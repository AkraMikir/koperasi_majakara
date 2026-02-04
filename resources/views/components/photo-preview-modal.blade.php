<!-- Photo Preview Modal -->
<div id="photo-preview-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-[100] hidden items-center justify-center p-4" onclick="closePhotoPreview(event)">
    <div class="relative max-w-6xl max-h-[90vh] w-full">
        <!-- Close Button -->
        <button onclick="closePhotoPreview()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Image Container -->
        <div class="flex items-center justify-center">
            <img id="preview-image" src="" alt="Preview" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl" onclick="event.stopPropagation()">
        </div>
        
        <!-- Image Info -->
        <div id="preview-info" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 rounded-b-2xl">
            <p class="text-white text-sm text-center"></p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showPhotoPreview(imageSrc, info = '') {
        const modal = document.getElementById('photo-preview-modal');
        const image = document.getElementById('preview-image');
        const infoEl = document.getElementById('preview-info').querySelector('p');
        
        image.src = imageSrc;
        if (info) {
            infoEl.textContent = info;
            document.getElementById('preview-info').classList.remove('hidden');
        } else {
            document.getElementById('preview-info').classList.add('hidden');
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePhotoPreview(event) {
        if (event && event.target.id !== 'photo-preview-modal') {
            return;
        }
        
        const modal = document.getElementById('photo-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePhotoPreview();
        }
    });
</script>
@endpush
