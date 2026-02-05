@props(['id', 'title', 'size' => 'max-w-2xl'])

<!-- Modal Backdrop -->
<div 
    id="{{ $id }}"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4"
    style="display: none;"
>
    <!-- Modal Container -->
    <div 
        class="bg-white rounded-xl shadow-2xl {{ $size }} w-full max-h-[90vh] overflow-hidden transform transition-all"
        onclick="event.stopPropagation()"
    >
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-blue-600">
            <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
            <button 
                type="button"
                onclick="closeModal('{{ $id }}')"
                class="text-white hover:text-gray-200 transition"
            >
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="px-6 py-4 overflow-y-auto max-h-[calc(90vh-140px)]">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('modal-show');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('modal-show');
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 300);
}

// Close on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal"].modal-show').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
});
</script>

<style>
.modal-show {
    animation: fadeIn 0.3s ease-in-out;
}

.modal-show > div {
    animation: slideUp 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>