<!-- GLOBAL CONFIRMATION MODAL -->
<div id="confirm-modal-overlay"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
        <h3 id="confirm-modal-title" class="text-lg font-semibold text-gray-800 mb-2">Confirm Action</h3>
        <p id="confirm-modal-message" class="text-sm text-gray-600 mb-6">Are you sure you want to proceed?</p>
        <div class="flex justify-end gap-3">
            <button id="confirm-modal-cancel"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
            <button id="confirm-modal-confirm"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Confirm</button>
        </div>
    </div>
</div>

<script>
    (function() {
        const overlay = document.getElementById('confirm-modal-overlay');
        const titleEl = document.getElementById('confirm-modal-title');
        const messageEl = document.getElementById('confirm-modal-message');
        const confirmBtn = document.getElementById('confirm-modal-confirm');
        const cancelBtn = document.getElementById('confirm-modal-cancel');
        let callback = null;

        window.confirmAction = function(title, message, onConfirm) {
            titleEl.textContent = title || 'Confirm Action';
            messageEl.textContent = message || 'Are you sure?';
            callback = onConfirm;
            overlay.classList.remove('hidden');
        };

        function hideModal() {
            overlay.classList.add('hidden');
            callback = null;
        }

        confirmBtn.addEventListener('click', function() {
            if (callback) callback();
            hideModal();
        });

        cancelBtn.addEventListener('click', hideModal);

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) hideModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !overlay.classList.contains('hidden')) hideModal();
        });
    })();
</script>
