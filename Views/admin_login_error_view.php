<div id="errorModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <p id="errorMessage"><?php echo htmlspecialchars($error_message ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="admin_login.php">
            <button id="returnLoginBtn">Retour à la page de connexion</button>
        </a>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('errorModal').style.display = 'none';
    }

    document.getElementById('errorModal').style.display = 'block';
</script>
