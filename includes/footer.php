    </div> <!-- End of main-content -->

    <!-- Universal Footer -->
    <footer class="universal-footer">
        <p>
            Powered by <img src="uploads/assets/mapua_Icowhite.png" alt="Mapua" class="footer-logo"> Mapúa University and 
            <img src="uploads/assets/FBTO_Icowhite.png" alt="Future Business Teachers' Organization" class="footer-logo"> Future Business Teachers' Organization.
        </p>
    </footer>

    <?php if (isset($additional_scripts) && is_array($additional_scripts)): ?>
        <?php foreach ($additional_scripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 